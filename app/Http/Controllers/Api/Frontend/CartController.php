<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\GetCartRequest;
use App\Http\Requests\Api\User\Cart\AddRequest;
use App\Http\Requests\Api\User\Cart\ChangeQuantityRequest;
use App\Http\Requests\Api\User\Cart\Coupon\AddRequest as AddCouponRequest;
use App\Http\Requests\Api\User\Cart\Coupon\DeleteRequest as DeleteCouponRequest;
use App\Http\Requests\Api\User\Cart\DeleteRequest;
use App\Mail\OutOfStockMail;
use App\Mail\SendCouponNotification;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Product;
use App\Traits\NotificationTrait;
use App\Traits\PaymentTrait;
use App\Traits\ProductTrait;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    use ProductTrait;
    use PaymentTrait;
    use NotificationTrait;

    function get(GetCartRequest $request)
    {
        $carts = Cart::query()->where('user_id', auth('api')->id())->get();

        $total = 0;
        $coupon_discount = 0;
        $total_before_coupon = 0;
        $list = [];
        $result = [];
        $currency = request()->header('currency');
        $currency = Currency::where(function ($q) use ($currency) {
            $q->where('symbol', $currency)->orWhere('code', $currency);
        })->where('status', 1)->first();
        if (empty($currency)) {
            $currency = Currency::where('is_default', 1)->first();
        }


        if ($request->has('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon->is_valid()) {

                $coupon_values = $this->api_calculate_cart_coupon($coupon, $currency);
                $result = $coupon_values['carts'];
                $coupon_discount = $coupon_values['total_discount'];
                $total_before_coupon = $coupon_values['total_amount'];
                $total = $coupon_values['final_price'];
            } else {
                return response()->api_error(trans('frontend.auth.coupon_not_valid'));
            }
        } else {
            foreach ($carts as $key => $cart) {
                $product = $cart->api_product;
                $item_price = $this->product_price($product, $cart->quantity);
                $total_before_coupon += $product->sale_price != 0 ? $product->sale_price : $product->price;
                $price = api_currency($item_price, $currency);
                $sale_price = api_currency($item_price, $currency);
                $total += $item_price * $cart->quantity;
                $categories_parent = $product->api_get_categories_parent();

                $has_token_input = isset($categories_parent[count($categories_parent)-1]) && ( $categories_parent[count($categories_parent)-1]['slug'] == 'token' ||  $categories_parent[count($categories_parent)-1]['slug'] == 'software');

                $result[] = [
                    'cart_id' => $cart->id,
                    'quantity' => $cart->quantity,
                    'stock' => $product->quantity,
                    'color' => $cart->color?->name,
                    'price' => $price,
                    'sale_price' => $sale_price,
                    'has_token_input' => $has_token_input,
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
                    'gallery' => [get_multisized_image($product->image), get_multisized_image($product->secondary_image)],
                    'accessories' => $product->api_accessories($currency),
                ];
            }

        }

        return response()->api_data([
            'products' => $result,
            'discount_value' => api_currency($coupon_discount, $currency),
            'total_before_coupon' => api_currency($total_before_coupon, $currency),
            'dolar_price' => [
                'value' => number_format($total, 2, '.', ''),
                'currency' => '$'
            ],
            'total' => api_currency($total, $currency)
        ]);
    }

    function add(AddRequest $request)
    {
        if ($request->has('products')) {
            $products = Product::query()->whereIn('sku', explode(',', $request->products))->where('status', 1)->get();
            foreach ($products as $product) {
                $cart = Cart::query()
                    ->where('user_id', auth('api')->id())
                    ->where('product_id', $product->id)
                    ->first();
                if ($cart) {
                    $cart->quantity += $request->quantity;
                } else {
                    $cart = new Cart();
                    $cart->quantity = $request->quantity;
                    $cart->note = $request->note;
                }
                if ($cart->quantity > $product->quantity) {
                    $user = auth('api')->user();
                    $user->outOfStock()->syncWithoutDetaching([$product->id => [
                        'quantity' => $request->quantity
                    ]]);

                    $data = [
                        'text' => trans('backend.notifications.product_out_of_stock', ['sku' => $request->product]),
                        'products' => Product::query()->where('sku', $request->product)->where('status', 1)->get()
                    ];
//                    Mail::to($user)->later(now()->addMinute(), new OutOfStockMail(trans('backend.notifications.out_of_stock'), $data));

                    return response()->api_error(trans('backend.notifications.product_out_of_stock', ['sku' => $request->product]), 403);
//            return response()->error(trans('api.card.the_product_cannot_be_added_because_the_quantity_is_not_available'));
                }
                $cart->product_id = $product->id;
                $cart->user_id = auth('api')->id();

                $cart->price = $this->product_price($product, $cart->quantity);

                $cart->quantity_price = $cart->price * $cart->quantity;
                if ($request->has('serial_number') && !empty($request->serial_number)) {
                    $cart->note = json_encode(['serial_number' => $request->serial_number]);
                }
                $cart->save();
            }
        } else {
            $product = Product::query()->where('sku', $request->product)->where('status', 1)->first();

            if (empty($product)) {
                return response()->error(trans('api.cart.not_found_product'));
            }
            #region cart
            $attributes = [];
            if ($request->has('attributes')) {
                $attributes = \request('attributes');
            }

            $cart = Cart::query()
                ->where('user_id', auth('api')->id())
                ->where('product_id', $product->id)
                ->where('attributes', json_encode($attributes))
                ->first();
            if ($cart) {
                $cart->quantity += $request->quantity;
            } else {
                $cart = new Cart();
                $cart->quantity = $request->quantity;
                $cart->note = $request->note;
                $cart->attributes = json_encode($attributes);
            }
            if ($cart->quantity > $product->quantity) {
                $user = auth('api')->user();
                $user->outOfStock()->syncWithoutDetaching([$product->id => [
                    'quantity' => $request->quantity
                ]]);

                $data = [
                    'user' => $user,
                    'text' => trans('backend.notifications.product_out_of_stock'),
                    'products' => Product::query()->where('sku', $request->product)->where('status', 1)->get()
                ];
                Mail::to($user)->later(now()->addMinute(), new OutOfStockMail(trans('backend.notifications.out_of_stock'), $data));

                //send notification
                $receivers['admins'] = Admin::query()->where('status', 1)->get();
                $data = [
                    'title' => 'Added product to cart is out of stock',
                    'body' => "the user added product ( {$product->sku} - {$product->title} ) is out of stock and the quantity is " . $request->quantity];
                $this->sendNotification($receivers, 'out_of_stock', $data);
                return response()->api_error(trans('backend.notifications.product_out_of_stock', ['sku' => $request->product]), 403);
//            return response()->error(trans('api.card.the_product_cannot_be_added_because_the_quantity_is_not_available'));
            }
            $cart->product_id = $product->id;
            if ($request->has('serial_number') && !empty($request->serial_number)) {
                $cart->note = json_encode(['serial_number' => $request->serial_number]);
                $cart->quantity = count($request->serial_number);
            }
            $cart->user_id = auth('api')->id();

            $cart->price = $this->product_price($product, $cart->quantity);

            $cart->quantity_price = $cart->price * $cart->quantity;

            $cart->save();

        }

        #endregion
        return response()->data(trans('api.cart.product_added_successfully'));
    }

    function delete(DeleteRequest $request, $cart_id)
    {

        Cart::query()->where([
            'user_id' => auth('api')->id(),
            'id' => $cart_id
        ])->delete();
        return response()->data(trans('api.cart.product_deleted_successfully'));

    }

    function change_quantity(ChangeQuantityRequest $request)
    {
        $cart = Cart::query()->where('id', $request->cart_id)->where('user_id', auth('api')->id())->first();
        if (!empty($cart)) {
            $product = $cart->product;
            if ($product->min_purchase_qty > $request->quantity) {
                return response()->error(trans('api.cart.can_not_added_product_because_the_minimum_for_order', ['num' => $product->min_purchase_qty]));
            }
            $cart->quantity = $request->quantity;
//            $cart->price = $request->quantity * $product->price;
            $cart->save();
            return response()->data(trans('api.cart.product_changed_successfully'));
        }
        return response()->api_error(['message' => trans('api.cart.cart_not_found')]);
    }

    function add_coupon(AddCouponRequest $request)
    {
        $carts = Cart::query()->where('user_id', auth('api')->id())->get();
        $total = 0;
        foreach ($carts as $key => $cart) {
            $product = $cart->api_product;
            $total += $this->product_price($product, $cart->quantity) * $cart->quantity;
        }

        $coupon = Coupon::query()
            ->where('starts_at', '<=', date('Y-m-d H:i:s'))
            ->where('ends_at', '>=', date('Y-m-d H:i:s'))
            ->where('code', $request->code)
            ->where('status', 1)->first();;
        if (empty($coupon) || (!empty($coupon->minimum_shopping) && $total < $coupon->minimum_shopping)) {
            return response()->error(trans('api.cart.coupon.coupon_is_not_available'));
        }
        return $this->calculate_coupon(auth('api')->id(), $request->code);
    }

    function delete_coupon(DeleteCouponRequest $request)
    {

        Cart::query()->where('user_id', auth('api')->id())->update(['coupon_applied' => 0, 'coupon_code' => null, 'discount' => 0]);
        return response()->data(trans('api.cart.coupon.deleted_successfully'));

    }
}
