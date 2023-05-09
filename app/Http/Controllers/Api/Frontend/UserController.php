<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\User\AddCompareRequest;
use App\Http\Requests\Api\Frontend\User\RemoveCompareRequest;
use App\Http\Requests\Api\User\User\ProfileRequest;
use App\Http\Requests\Api\User\User\UserUpdateRequest;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use App\Models\Coupon;
use App\Models\CouponOfferUser;
use App\Models\CouponUsages;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductsBrand;
use App\Models\User;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use stdClass;

class UserController extends Controller
{
    public function profile(ProfileRequest $request)
    {
        $user = User::find(auth('api')->id());

        $user_info = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => (!empty($user->avatar) && !filter_var($user->avatar, FILTER_VALIDATE_URL)) ? asset($user->avatar) . "?" . time() : $user->avatar,
            'company_name' => $user->company_name,
            'website_url' => $user->website_url,
            'type_of_business' => $user->type_of_business,

        ];

        if (!empty($user->seller)) {
            $user_info ['seller'] = [
                'name' => $user->seller?->name,
                'email' => $user->seller?->email,
                'whatsapp_number' => $user->seller?->whatsapp_number,
                'phone' => $user->seller?->phone,
                'skype' => $user->seller?->skype,
                'facebook' => $user->seller?->facebook,
                'avatar' =>  asset( $user->seller?->avatar),
            ];
        }

        return response()->api_data(['user_info' => $user_info]);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = User::find(auth('api')->id());

        $user->name = $request->name;
        if ($request->avatar_remove == true) {
            $user->avatar = null;
        }
        if (!empty($request->file('avatar'))) {
            $avatar = $request->file('avatar');
            $image_data = $user->StoreAvatarImage('avatar', $user->id, 'user');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $user->avatar = $avatar_link;
            $user->save();
        }

        if ($user->email != $request->email && !empty($request->email)) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        } else {
            $user->email_verified_at = date('Y-m-d h:i:s');
        }
        $user->phone = $request->phone != "null" ? $request->phone : '';
        $user->type_of_business = $request->type_of_business != "null" ? $request->type_of_business : '';
        $user->company_name = $request->company_name != "null" ? $request->company_name : '';
        $user->website_url = $request->website_url != "null" ? $request->website_url : '';
        $user->save();
        return response()->api_data(['user_info' => $user]);

    }

    public function wallet(ProfileRequest $request)
    {
        $user = auth('api')->user();
        $length = 12;
        $page = 1;

        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }

        $wallet = [];
        $query = UserWallet::query();
        $query = $query->select('user_wallet.*', 'orders.uuid', \DB::raw("IFNULL('TR','orders_payments.payment_method')"));
        $query = $query->leftJoin('orders_payments', 'orders_payments.id', 'user_wallet.order_payment_id');
        $query = $query->leftJoin('orders', 'orders.id', 'user_wallet.order_id');
//        $query = $query->groupBy('user_wallet.id');
        $query = $query->where('user_wallet.user_id', auth('api')->id());
        $total = $query->count();
        if ($request->sort == 'newest') {
            $query = $query->orderByDesc('user_wallet.created_at');
        } else {

            $query = $query->orderBy('user_wallet.created_at');
        }

        $wallets = $query->skip(($page - 1) * $length)->limit($length)->get();
        foreach ($wallets as $item) {
            $files = [];
            if (!empty($item->files)) {
                $files = $item->files;

                $files = json_decode($files);
                foreach ($files as $index => $file) {
                    $files[$index] = asset(implode('/', ['storage', $file]));
                }
            }

            $wallet[] = [
                'amount' => $item->amount,
                'type' => $item->type,
                'status' => $item->status,
                'files' => $files,
                'created_at' => $item->created_at->format('Y-m-d H:i'),
                'order' => $item->uuid,
                'payment_method' => empty($item->payment_method) ? "transfer" : $item->payment_method,
//                'note' => $item->note
            ];
        }
        return response()->api_data(['wallet' => $wallet, 'total' => $total, 'page' => intval($page), 'total_pages' => ceil($total / $length), 'length' => sizeof($wallets)]);
    }

    public function coupons(ProfileRequest $request)
    {
        $user = auth('api')->user();
        $length = 12;
        $page = 1;

        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $coupon_offer_user = CouponOfferUser::query()->where('user_id', $user->id)->pluck('coupon_id')->toArray();
        $coupon_used = CouponUsages::query()->where('coupon_usages.user_id', $user->id)->pluck('coupon_id')->toArray();
        $coupons_data = Coupon::query()
            ->whereIn('id', $coupon_offer_user)
            ->orWhereIn('id', $coupon_used)
            ->groupBy('id');
        $total = $coupons_data->count();
        $coupons_data = $coupons_data->skip(($page - 1) * $length)->limit($length)->get();
        $coupons = [];
        foreach ($coupons_data as $coupon) {
            $used = CouponUsages::query()->where('coupon_id', $coupon->id)->where('user_id', $user->id)->count();
            $all_used = CouponUsages::query()->where('coupon_id', $coupon->id)->count();
            $count_available = ($all_used >= $coupon->max_use) ? $coupon->avalible_count = 0 : $coupon->per_user - $used;
            $orders = [];
            if ($used > 0) {
                $orders = Order::query()->where('user_id', $user->id)->where('coupon_id', $coupon->id)->pluck('uuid')->toArray();
            }
            $coupons[] = [
                'user_id' => $coupon->order_user,
                'coupon_code' => $coupon->code,
                'discount' => $coupon->discount,
                'discount_type' => $coupon->discount_type,
                'coupon_type' => $coupon->type,
                'ends_at' => $coupon->ends_at,
                'count_available' => $count_available,
                'is_available' => ($count_available > 0 && strtotime($coupon->ends_at) > time()),
                'count_available' => ($count_available > 0 && strtotime($coupon->ends_at) > time()) ? $count_available : 0,

                'used' => $used,
                'orders' => $orders,
            ];
        }


        return response()->api_data(['total' => $total, 'page' => $page, 'length' => sizeof($coupons), 'coupons' => $coupons]);
    }

    public function add_to_compare(AddCompareRequest $request)
    {
        if (!empty($request->products)) {
            $user = auth('api')->user();
            foreach (explode(',', $request->products) as $productSlug) {
                $product = Product::where('slug', $productSlug)->first();
                $user->compared_products()->syncWithoutDetaching($product->id);
            }
        } else {
            $user = auth('api')->user();
            $product = Product::where('slug', $request->product)->first();
            $user->compared_products()->syncWithoutDetaching($product->id);
        }

        return response()->api_data(['message' => trans('frontend.compare.added_successfully')]);
    }

    public function get_compares()
    {
        $user = auth('api')->user();

        $currency = request()->header('currency');
        $currency = Currency::where(function ($q) use ($currency) {
            $q->where('symbol', $currency)->orWhere('code', $currency);
        })->where('status', 1)->first();
        if (empty($currency)) {
            $currency = Currency::where('is_default', 1)->first();
        }

        $products = $user->compared_products;
        $products_to_compare = [];
        foreach ($products as $product) {
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
            $categories_parent = $product->api_get_categories_parent();
            $products_to_compare[] = [
                'id' => $product->id,
                'title' => $product->title,
                'short_title' => $product->short_title,
                'summary_name' => $product->summary_name,
                'description' => $product->description,
                'sku' => $product->sku,
                'weight' => $product->weight,
                'slug' => $product->slug,
                'price' => !$product->hide_price ? api_currency($product->price, $currency) :
                    ["value" => '-', "currency" => $currency->symbol],
                'stock' => $product->quantity,
                'sale_price' => !$product->hide_price ? ($product->sale_price == 0 ? api_currency($product->price, $currency) : api_currency($product->sale_price, $currency)) : ["value" => '-', "currency" => $currency->symbol],
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
                'has_token' => isset($categories_parent[count($categories_parent) - 1]) && ($categories_parent[count($categories_parent) - 1]['slug'] == 'token' || $categories_parent[count($categories_parent) - 1]['slug'] == 'software'),

                'videos' => $videos,
                'offers' => $offers,
                'pdf' => $pdf,
                'faq' => $product->faq,
                'meta' => [
                    'title' => $product->meta_title,
                    'description' => $product->meta_description,
                ],
                //if discount type is fixed? api_currency : value%
                'discount' => !$product->hide_price ? $product->api_discount_form() : null,
                'gallery' => $gallery,
                'categories' => $categories_parent,
                'manufacturer' => $product->manufacturer?->title,
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
        return response()->api_data(['products' => $products_to_compare]);
    }

    public function delete_from_compares(RemoveCompareRequest $request)
    {
        $user = auth('api')->user();
        $product = Product::where('slug', $request->product)->first();
        $user->compared_products()->detach($product->id);

        return response()->api_data(['message' => trans('frontend.compare.removed_successfully')]);

    }
}
