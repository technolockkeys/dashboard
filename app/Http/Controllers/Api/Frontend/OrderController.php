<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\InvoiceRequest;
use App\Http\Requests\Api\User\Order\CreateRequest;
use App\Http\Requests\Api\User\Order\GetRequest;
use App\Http\Requests\Api\User\Order\RepayRequest;
use App\Http\Requests\Api\User\Order\ResponsePaypalRequset;
use App\Http\Requests\Api\User\User\ProfileRequest;
use App\Http\Requests\Frontend\Api\Order\CheckoutRequest;
use App\Mail\PincodeMail;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use App\Models\Card;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\CouponUsages;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\ProductsBrand;
use App\Models\ProductsPackages;
use App\Models\Seller;
use App\Models\SubAttribute;
use App\Models\User;
use App\Models\UserWallet;
use App\Traits\InvoiceTrait;
use App\Traits\NotificationTrait;
use App\Traits\OrderTrait;
use App\Traits\PaymentTrait;
use App\Traits\ProductTrait;
use App\Traits\SetMailConfigurations;
use App\Traits\StripeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use stdClass;
use function Symfony\Component\HttpFoundation\Session\Storage\Handler\read;

class OrderController extends Controller
{
    use ProductTrait;
    use PaymentTrait;
    use StripeTrait;
    use OrderTrait;
    use NotificationTrait;
    use SetMailConfigurations;
    use InvoiceTrait;

    public function orders(ProfileRequest $request)
    {
        $orders = Order::query()
            ->orderByDesc('created_at')
            ->where('user_id', auth('api')->id());

        $length = 12;
        $page = 1;
        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $total = $orders->count();
        $orders = $orders->skip(($page - 1) * $length)->limit($length)->get();
        $result = [];
        foreach ($orders as $order) {
            $products = $order->order_products;
            $images = [];
            foreach ($products as $product) {
                $images[] = get_multisized_image($product->image, ['s']);
            }
            $currency = Currency::query()->where(function ($q) use ($order) {
                $order->currency_id ?
                    $q->where('id', $order->currency_id) : $q->where('is_default', 1);
            })->first();
            $seller = Seller::query()->where('id', $order->seller_id)
                ->select(
                    'email',
                    'whatsapp_number',
                    'facebook',
                    'skype',
                    'phone',
                    'avatar',
                    'name')
                ->first();

            $result[] = [
                'seller' => $seller,
                'order_id' => $order->uuid,
                'payment_method' => $order->payment_method,
                'address' => [
                    'country' => $order->country?->name,
                    'city' => $order->city,
                    'address' => $order->address,
                    'postal_code' => $order?->postal_code,
                    'phone' => $order->phone,
                ],
                'payment_status' => $order->payment_status,
                'total' => api_exc_currency($order->total, empty($order->exchange_rate) ? 1 : $order->exchange_rate, $currency->symbol),
                'images' => $images,
                'shipping' => api_exc_currency($order->shipping, $order->exchange_rate, $currency->symbol),
                'status' => trans('backend.order.' . $order->status),

                'coupon_value' => api_currency($order->coupon_value, $currency),
                'type' => $order->type,
                'note' => ($order->type == Order::$pin_code) ? json_decode($order->note) : "",
                'tracking_no' => $order->tracking_number,
                'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:m:s'),
            ];
        }

        return response()->api_data(['total' => $total, 'page' => intval($page), 'length' => $length, 'total_pages' => ceil($total / $length), 'orders' => $result]);

    }

    public function order(ProfileRequest $request, $uuid)
    {
//        $currency = Currency::where('symbol', $request->header('currency'))->orWhere('is_default', 1)->first();

        $order = Order::where('uuid', $uuid)->where('user_id', auth('api')->id())
            ->with('products')->first();
        if (empty($order)) {
            return response()->error(trans('api.order.not_found_order'));
        }
        $currency = Currency::query()->where(function ($q) use ($order) {
            $order->currency_id ?
                $q->where('id', $order->currency_id) : $q->where('is_default', 1);
        })->first();
        if ($order->type == 'pin_code') {
            $products = json_decode($order->note);
            $brand = Brand::find($products->brand);
            $products->brand = $brand->make;
        } else {
            $products = [];
            foreach ($order->order_products as $product) {
                $attributes = SubAttribute::whereIn('id', json_decode($product->pivot->attributes, true) ?? [])->with('attribute')->groupBy('attribute_id')->get();

                $order_attributes = [];
                foreach ($attributes as $attribute) {
                    $order_attributes[$attribute->attribute->name][] = $attribute->value;
                }

                $brands = [];
                foreach ($product->brands ?? [] as $key => $brand) {
                    $years = ProductsBrand::where('product_id', $product->id)->where('brand_id', $brand->brand_id)->where('brand_model_id', $brand->brand_model_id)->pluck('brand_model_year_id');
                    $brands[] = [
                        'brand' => Brand::where('id', $brand->brand_id)->first()->make,
                        'model' => BrandModel::where('id', $brand->brand_model_id)->first()?->model,
                        'years' => BrandModelYear::whereIn('id', $years)->pluck('year'),
                    ];
                }

                $gallery = [
                    get_multisized_image($product->image),
                    get_multisized_image($product->secondary_image),
                ];
                foreach (json_decode($product->gallery, true) ?? [] as $image)
                    $gallery[] = get_multisized_image($image);
                $pdf = [];
                foreach (json_decode($product->pdf, true) ?? [] as $file) {
                    $data = media_file($file, true);
                    $pdf[] = [
                        'title' => $data->title,
                        'path' => asset(urlencode('storage' . $data->path . $data->title))
                    ];
                }
                $videos = [];
                foreach (json_decode($product->videos, true) ?? [] as $key => $video)
                    $videos[$key] = $video;

                $offers = [];
                foreach ($product->offers as $key => $offer) {
                    $offers[] = [
                        'from' => $offer->from,
                        'to' => $offer->to,
                        'price' => api_currency($offer->price, $currency),
                    ];
                }

                $products[] = [
                    'id' => $product->id,
                    'title' => $product->title,
                    'short_title' => $product->short_title,
                    'summary_name' => $product->summary_name,
                    'description' => $product->description,
                    'serial_numbers' => json_decode($product->pivot->serial_number),
                    'sku' => $product->sku,
                    'slug' => $product->slug,
                    'price' => api_currency($product->pivot->price, $currency),
                    'stock' => $product->pivot->quantity,
                    'quantity' => $product->pivot->quantity,
                    'sale_price' => api_currency($product->pivot->price, $currency),
                    'is_sale' => $product->sale_price == null ? 0 : 1,
                    'avg_rating' => number_format($product->avg_rating, 2),
                    'total_reviews' => $product->total_reviews,
                    'is_best_seller' => $product->is_best_seller,
                    'is_saudi_branch' => $product->is_saudi_branch,
                    'is_featured' => $product->is_featured,
                    'is_free_shipping' => $product->is_free_shipping,
                    'hide_price' => $product->hide_price,
                    'type' => $product->category?->type,
                    'twitter_image' => media_file($product->twitter_image),
                    'videos' => $videos,
                    'offers' => $offers,
                    'pdf' => $pdf,
                    'faq' => $product->faq,
                    'meta' => [
                        'title' => $product->meta_title,
                        'description' => $product->meta_description,
                    ],
                    //if discount type is fixed? api_currency : value%
                    'discount' => $product->api_discount_form(),
                    'gallery' => $gallery,
                    'categories' => $product->api_get_categories_parent(),
                    'accessories' => $product->api_accessories($currency),
                    'bundled' => $product->api_bundleds($currency),
                    'attributes' => $product->api_attributes(),
                    'specifications' => [
                        'manufacturer' => $product->manufacturer?->title,
                        'weight' => $product->weight,
                        'color' => $product->color ? [
                            'name' => $product->color?->name,
                            'hex' => $product->color?->code
                        ] : new stdClass(),
                    ],
                    'brands' => $brands
                ];
            }
        }

        $payment_history = [];
        foreach ($order->order_payment as $index => $payment) {
            $payment_details = null;
            if ($payment->payment_method == Order::$paypal)
                $payment_details = null;
            elseif ($payment->payment_method == Order::$stripe)
                $payment_details[] = [
                    'last_four' => $payment->card?->last_four,
                    'brand' => $payment->card?->brand];
            elseif ($payment->payment_method == Order::$transfer)
                foreach (json_decode($payment->files, true) ?? [] as $index => $file) {
                    $payment_details['files'][] = asset($file);
                }
            elseif ($payment->payment_method == Order::$stripe_link) {

                $details = json_decode($payment->payment_details);
                $payment_details['strip_link'] = $details->link;
            }
            $userWallets = UserWallet::query()->where('order_id', $order->id)->where('order_payment_id', $payment->id)->get();
            foreach ($userWallets as $userWallet) {
                $payment_history[] = [
                    'amount' => api_currency($userWallet->amount, Currency::query()->where('code', 'usd')->first()),
                    'payment_method' => $payment->payment_method,
                    'created_at' => $userWallet->created_at->format('Y-m-d'),
                    'status' => $userWallet->status,
                    'details' => $payment_details,
                ];
            }


        }

        $order_status [Order::$failed] = -1 ;
        $order_status [Order::$on_hold] = 1 ;
        $order_status [Order::$processing] = 2 ;
        $order_status [Order::$completed] = 3 ;
        $order_info = [
            'order_id' => $order->uuid,
            'type' => $order->type,
            'payment_method' => trans('backend.order.' . $order->payment_method),
            'payment_status' => trans('backend.order.' . $order->payment_status),
            'total' => api_exc_currency($order->total, 1, "$"),
            'sub_total' => api_exc_currency($order->total + $order->coupon_value - $order->shipping, 1, "$"),
            'shipping_method' => $order->shipping_method,
            'shipping' => api_exc_currency($order->shipping, 1, "$"),
            'status' => trans('backend.order.' . $order->status),
            'coupon_value' => api_exc_currency($order->coupon_value, 1, "$"),
            'coupon' => $order->coupon?->code,
//            'coupon_value' => ['value'=>$order->coupon_value *  $order->exchange_rate , 'currency'=>$currency->symbol],
            'tracking_no' => $order->tracking_number,
            'weight' => $order->weight,
            'note' => $order->note,
            'order_status' => $order_status[$order->status],
            'print' => route('orders.print', ['uuid' => $order->uuid]),
            'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:m:s'),
            'address' => [
                'country' => $order->country?->name,
                'city' => $order->city,
                'address' => $order->address,
                'postal_code' => $order?->postal_code,
                'phone' => $order->phone,
            ],
            'seller' => [
                'name' => $order->seller?->name,
                'avatar' => !empty($order->seller?->avatar) ? asset($order->seller?->avatar) : media_file(get_setting('default_images')),
                'email' => $order->seller?->email,
                'whatsapp_number' => $order->seller?->whatsapp_number,
                'phone' => $order->seller?->phone,
                'skype' => $order->seller?->skype,
                'facebook' => $order->seller?->facebook,
            ]
        ];
        return response()->api_data(['order' => $order_info, 'order_products' => $products, 'payment_history' => $payment_history]);

    }

    function create(CreateRequest $request)
    {
        $currency = Currency::query()->where('symbol', $request->header('currency'))
            ->orWhere('is_default', 1)
            ->first();


        $card = null;
        if ($request->payment_method == Order::$stripe) {
            $card = $request->card_id;
            if ($card == -1) {
                $card_response = $this->create_card(auth('api')->id(), $request->card_name, $request->card_number, $request->card_exp_month, $request->card_exp_year, $request->card_cvc);
                if ($card_response['success'] == false) {
                    return response()->error($card_response['message']);
                } else {
                    $card = $card_response['card'];
                }
            } else {
                $card = Card::find($request->card_id);
            }
        }
        $address = Address::find($request->address);
        if (!$address) {
            return response()->error(trans('api.order.not_found_address'));
        }


        $user = auth('api')->user();

        $data = $this->create_orders(auth('api')->id(), $request->address, [], $request->payment_method, Order::$order, $request->shipping_method, null, [], $request->note, $request->coupon_code, $card?->card_id);
        return $data;
        if (!$data)
            return response()->error(trans('api.order.cart_is_empty'));
        if (!$data['success'])
            return response()->error($data['message']);

        $user->carts()->delete();
        $order_info = [
            'order_id' => $data['order']->uuid,
            'type' => $data['order']->type,
            'payment_method' => trans('backend.order.' . $data['order']->payment_method),
            'payment_status' => trans('backend.order.' . $data['order']->payment_status),
            'total' => api_currency($data['order']->total, $currency),
            'shipping_method' => $data['order']->shipping_method,
            'shipping' => api_currency($data['order']->shipping, $currency),
            'status' => trans('backend.order.' . $data['order']->status),
            'coupon_value' => api_currency($data['order']->coupon_value, $currency),
            'tracking_no' => $data['order']->tracking_number,
            'created_at' => Carbon::parse($data['order']->created_at)->format('Y-m-d H:m:s'),
            'address' => ['country' => $data['order']->country?->name,
                'city' => $data['order']->city,
                'address' => $data['order']->address,
                'postal_code' => $data['order']?->postal_code,
                'phone' => $data['order']->phone,
            ],
        ];

        return response()->data(['order' => $order_info, 'payment' => $data['order_payment'], 'products' => $user->carts]);
    }

    function paypal_response(ResponsePaypalRequset $requset)
    {
        $order = Order::query()->where('uuid', $requset->order_id)->first();
        if (empty($order)) {
            return response()->error(trans('api.order.order_not_found') . "1");
        }
        $payment_order = OrderPayment::query()
            ->where('order_id', $order->id)
            ->where('user_id', auth('api')->id())
            ->where('payment_method', 'paypal')
            ->where('status', 'pending')
//            ->where('id', $requset->order_payment_id)
            ->first();
        if (empty($payment_order)) {
            return response()->error(trans('api.order.order_not_found'));
        }
        if ($requset->success == 1) {
            $payment_order->status = 'captured';
            $payment_order->save();
            $order->status = Order::$processing;
            $order->payment_status = 'paid';
            $order->save();
            foreach ($order->order_products as $order_product) {
                $product = Product::query()->where('id', $order_product->product_id)->first();
                if (!empty($product)) {
                    $product->quantity = $product->quantity - $order_product->quantity;
                    $product->save();
                }
            }
            $created_by = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
            $created_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
            $this->payment_wallet($order->user_id, $order->total, 'order', 'approve', $created_by, $created_id, $order->id, $payment_order->id);

            if ($order->type == Order::$pin_code) {
                $this->payment_wallet($order->user_id, $order->total * -1, 'order', 'approve', $created_by, $created_id, $order->id, $payment_order->id);

                $this->setMailConfigurations();
                $email = null;
                if (!empty($order->user->email)) {
                    $email = $order->user->email;
                }
                if (!empty($order->user->email)) {
                    $email = $order->user->email;
                }
                $note = json_decode($order->note, true);
                if ($note['contact_channel'] == 'email') {
                    $email = $note['contact_value'];
                }

                if (!empty($email)) {
                    \Mail::to($email)
                        ->queue(new PincodeMail($order->id));

                }

            }


        } else {
            $payment_order->status = 'voided';
            $payment_order->save();
            $order->status = Order::$canceled;
            $order->payment_status = 'unpaid';

            $this->refund_statements_order($order->id, $order, false);
            UserWallet::query()->where('order_id', $order->id)->update(['status' => UserWallet::$cancelled]);
            OrderPayment::query()->where('order_id', $order->id)->update(['status' => UserWallet::$cancelled]);
            $order->status = Order::$canceled;
            $order->save();
            foreach ($order->order_products as $order_product) {
                Cart::create([
                    'user_id' => $order->user_id,
                    'address_id' => $order->address_id,
                    'product_id' => $order_product->product_id,
                    'quantity' => $order_product->quantity,
                ]);
            }


        }
        return response()->data(['success' => true]);
    }

    function repay(RepayRequest $request)
    {
        $order_id = $request->order_id;
        $payment_method = $request->payment_method;
        $order = Order::query()->whereNot('payment_method', 'paid')->where('id', $order_id)->first();
        if (empty($order)) {
            return response()->error(trans('api.order.order_not_found'));
        }
        if ($payment_method == 'stripe') {
            $card = $request->card_id;
            if ($card == -1) {
                $card_response = $this->create_card(auth('api')->id(), $request->card_name, $request->card_number, $request->card_exp_month, $request->card_exp_year, $request->card_cvc);
                if ($card_response['success'] == false) {
                    return response()->error($card_response['message']);
                } else {
                    $card = $card_response['card'];
                }
            } else {
                $card = Card::find($request->card_id);
            }
            if (empty($card)) {
                return response()->error(trans('api.order.card_not_found'));
            }
            $payment = $this->payment_stripe($order->total, $card->card_id, $order->id);
        } else if ($payment_method == 'paypal') {
            $payment = $this->payment_paypal($order->total, $order->id);
        }
        $order = Order::query()->where('id', $order_id)->first();
        return response()->data(['order' => $order, 'payment' => $payment]);
    }

    function test_payment(Request $request)
    {
//        $countries = Country::query()->get();
//        foreach ($countries as $item){
//            $item->zone_id = rand(1 ,10);
//            $item->save();
//        }
//        return [1];
//        for ($i = 1; $i <= 10; $i++) {
//            $weight = 0.5;
//            for ($j = 1; $j <= 100; $j++) {
//                $zone_price = new ZonePrice();
//                $zone_price->zone_id = $i;
//                $zone_price->price = $i * $j;
//                $zone_price->weight = $weight;
//                $zone_price->save();
//                $weight += 0.5;
//            }
//        }

//        $card = Card::find(15);
//        return $this->payment_stripe(100, $card->card_id);
    }

    public function api_calculate_shipping_cost()
    {
        $address = \request()->get('address');
        $currency = Currency::where('symbol', request()->header('currency'))
            ->orWhere('is_default', 1)->first();

        $user = auth('api')->user();

        $country_id = Address::find($address)->country_id;
        $shipping['shipping'] = 0;
        foreach ($user->carts as $cart) {
            $weight = $cart->product->weight * $cart->quantity;
            $shipping['shipping'] += $this->shipping_cost($country_id, $weight, false, 'dhl')['shipping'];
        }

        return response()->api_data(['shipping' => api_currency($shipping['shipping'], $currency)]);
    }

    public function checkout(CheckoutRequest $request)
    {
        $carts = Cart::query()->where('user_id', auth('api')->id())->get();
        $total = 0;
        $total_weight = 0;
        $coupon_discount = 0;
        $total_price = 0;
        $total_before_coupon = 0;
        $list = [];
        $result = [];
        $is_free_shipping = false;
        $currency = Currency::where('symbol', request()->header('currency'))
            ->orWhere('code', request()->header('currency'))->first();
        $country_id = Address::find(request()->get('address'))?->country_id;
        foreach ($carts as $cart) {
            $product = $cart->api_product;
            $product_price = $this->product_price($product, $cart->quantity);
            $total_before_coupon += $product_price * $cart->quantity;
            $price = api_currency($product_price, $currency);
            $total += $product_price * $cart->quantity;

            $total_price += $product_price * $cart->quantity;

            $weight = $cart->product->weight * $cart->quantity;
            $total_weight += $weight;
            $result[] = [
                'cart_id' => $cart->id,
                'quantity' => $cart->quantity,
                'stock' => $product->quantity,
                'color' => $cart->color?->name,
                'price' => $price,
                'short_title' => $product->short_title,
                'title' => $product->title,
                'summary_name' => $product->summary_name,
                'faq' => $product->faq,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'priority' => $product->priority,
                'weight' => $product->weight,
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'category' => $product->category?->name,
                'note' => $cart->note,
                'gallery' => [get_multisized_image($product->image, ['m']), get_multisized_image($product->secondary_image, ['m'])],
                'message' => in_array($country_id, $cart->product->blocked_countries ?? []) ? trans('frontend.product.product_blocked_in_this_country') : null,
                'accessories' => $product->api_accessories($currency),
            ];
        }
        if (request()->has('coupon_code')) {
            $coupon = Coupon::where('code', request()->get('coupon_code'))->first();
            if ($coupon?->is_valid() && (($coupon->minimum_shopping != "" && $total > $coupon->minimum_shopping) || $coupon->minimum_shopping == null)) {
                $coupon_values = $this->api_calculate_cart_coupon($coupon, $currency, $country_id);
                $result = $coupon_values['carts'];
                $coupon_discount = $coupon_values['total_discount'];
                $total_before_coupon = $total_price;
                $total = $total_price - $coupon_values['total_discount'];
            } else {
                return response()->api_error(trans('frontend.auth.coupon_not_valid'));
            }
        }
        $shipping['shipping'] = 0;
        if (get_setting('free_shipping_cost') <= $total_price) {
            $is_free_shipping = true;
            $shipping['shipping'] = 0;
        } else {
            $shipping['shipping'] = $this->shipping_cost($country_id, $total_weight, false, request()->get('shipping_method'))['shipping'];
        }

        $data = [
            'products' => $result,

            'shipping_cost' => api_currency($shipping['shipping'], $currency),
            'dolar_price' => [
                'value' => number_format($total + $shipping['shipping'], 2, '.', ''),
                'currency' => '$'
            ],
            'total' => api_currency($total + $shipping['shipping'], $currency),
            'sub_total' => api_currency($total, $currency),

        ];
        if (request()->has('coupon_code')) {
            $data['discount_value'] = api_currency($coupon_discount, $currency);
            $data['total_before_coupon'] = api_currency($total_before_coupon, $currency);
        }

        return response()->api_data($data);


    }

    public function sendInvoice(InvoiceRequest $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->first();
//        $email = auth('api')->user()->email;
//        $this->sendByEmail($order->id,$email);
//        $this->d
        return response()->api_data(['message' => trans('frontend.order.invoice_sent')]);
    }

    public function donwloadPdf($uuid)
    {
        $order = Order::query()->where('uuid', $uuid)->first();


        if (empty($order)) {
            return abort(404);
        }
        $this->PrintInvoicePDF($order->id);

    }

}
