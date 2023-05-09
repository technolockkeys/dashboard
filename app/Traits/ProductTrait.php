<?php

namespace App\Traits;


use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductsPackages;
use Illuminate\Support\Facades\Log;


trait ProductTrait
{

    function product_price(Product $product, $quantity)
    {
//        $price = $product->price;
        $sale_price = $product->sale_price != 0 ? $product->sale_price : $product->price;

        $discount = $product->api_discount_form();

        if (!empty($discount) &&  !empty($discount['type']))
        {
            $sale_price  = ($discount['type'] == 'fixed') ? $sale_price - $discount['value'] : $sale_price - ($discount['value'] * $sale_price / 100);
        }


        $package = ProductsPackages::query()
            ->where('product_id', $product->id)
            ->where('from', '<=', $quantity)
            ->where('to', '>=', $quantity)
            ->first();
        if (!empty($package)) {
            $sale_price = $package->price;
        }

        return $sale_price;

    }

    function calculate_coupon($user_id, $coupon_code = null)
    {
        $coupon = null;
        if (!isset($coupon_code)) {
            $coupon = Cart::query()->whereNotNull('coupon_code')->where('user_id', $user_id)->first();
            if (!empty($coupon)) {
                $coupon_code = $coupon->coupon_code;
            }
        }

        if (isset($coupon_code)) {
            $coupon = Coupon::query()
                ->where('status', 1)
                ->where('code', $coupon_code)
                ->where(function ($q) {
                    $q->where('starts_at', "<=", date('Y-m-d H:i:s'));
                    $q->where('ends_at', ">=", date('Y-m-d H:i:s'));
                })->first();
            if (empty($coupon)) {
                return response()->error(trans('api.cart.coupon.coupon_is_not_available'));
            }
            $carts = Cart::query()->where('user_id', $user_id)->get();
            $total_quantity = 0;
            $total_amount = 0;
            foreach ($carts as $cart) {
                if ($coupon->type == Coupon::$Product && in_array($cart->product_id, $coupon->products_ids)) {
                    $total_quantity += $cart->quantity;
                } else {
                    $total_quantity += $cart->quantity;
                }
                $total_amount += $cart->price * $cart->quantity;
            }
            if ($coupon->minimum_shopping > $total_amount) {
                return response()->error(trans('api.cart.coupon.can_not_use_coupon_because_the_minimum_order_is', ['num' => $coupon->minimum_shopping]));
            }
            foreach ($carts as $cart) {
                if ($coupon->type == Coupon::$Order) {
                    if ($coupon->discount_type == Coupon::$Amount) {
                        $cart->discount = number_format(($coupon->discount / $total_quantity) * $cart->quantity, 2);
                    } else {
                        $cart->discount = number_format(($coupon->discount * $cart->price / 100), 2);
                    }
                    $cart->coupon_code = $coupon->code;
                    $cart->coupon_applied = 1;
                    $cart->save();
                } else {
                    if (in_array($cart->product_id, $coupon->products_ids)) {
                        if ($coupon->discount_type == Coupon::$Amount) {
                            $cart->discount = number_format(($coupon->discount / $total_quantity) * $cart->quantity, 2);
                        } else {
                            $cart->discount = number_format(($coupon->discount * $cart->price / 100), 2);
                        }
                        $cart->coupon_code = $coupon->code;
                        $cart->coupon_applied = 1;
                        $cart->save();
                    }
                }
            }
            return response()->data(trans('api.cart.coupon.coupon_is_applied'));
        }
        return response()->data(['status' => false]);
    }


    function generate_sku_code($i = 1)
    {

        $sku = "TL" . (Product::withTrashed()->max('id') + $i);
        $check = Product::withTrashed()->where('sku', $sku)->count();
        if ($check == 0) {
            return $sku;
        }
        return $this->generate_sku_code($i + 1);
    }

    public function api_calculate_cart_coupon(Coupon $coupon, Currency $currency, $country = null)
    {

        $user_id = auth('api')->id();

        $carts = Cart::query()->where('user_id', $user_id)->get();
        $final_price = 0;
        $before_coupon_total_amount = 0;
        $total_discount = 0;
        $total_quantity = $carts->sum('quantity');
        $result = [];

        foreach ($carts as $cart) {
            $before_coupon_total_amount += $cart->price * $cart->quantity;
            if ($coupon->type == Coupon::$Order) {
                if ($coupon->discount_type == Coupon::$Amount) {
                    $cart->discount = number_format(($coupon->discount / $total_quantity) * $cart->quantity, 2);
                } else {
                    $cart->discount = number_format(($coupon->discount * $cart->price / 100), 2) * $cart->quantity;
                }
                $cart->coupon_code = $coupon->code;
                $cart->coupon_applied = 1;
            } else {
                if (in_array($cart->product_id, $coupon->products_ids)) {
                    if ($coupon->discount_type == Coupon::$Amount) {
                        $cart->discount = number_format(($coupon->discount) * $cart->quantity, 2);
                    } else {
                        $cart->discount = number_format(($coupon->discount * $cart->price / 100) * $cart->quantity, 2);
                    }
                    $cart->coupon_code = $coupon->code;
                    $cart->coupon_applied = 1;
                } else {
                    $cart->discount = 0;

                }
            }
            $cart->quantity_price = ($cart->price) * $cart->quantity - $cart->discount;

            $total_discount += $cart->discount;
            $final_price += $cart->quantity_price;
//            $cart->quantity_price = api_currency(($cart->price )* $cart->quantity  - $cart->discount, $currency);
            $price = api_currency($cart->price, $currency);
//            dd($cart->product->sku);
            $cart->save();
            $product = $cart->api_product;
            $result[] = [
                'cart_id' => $cart->id,
                'quantity' => $cart->quantity,
                'stock' => $product->quantity,
                'color' => $cart->color?->name,
                'price' => $price,
                'discount' => api_currency($cart->discount, $currency),
                'sale_price' => api_currency($cart->quantity_price - $cart->discount, $currency),
                'short_title' => $product->short_title,
                'title' => $product->title,
                'summary_name' => $product->summary_name,
                'product_id' => $product->id,
                'faq' => $product->faq,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'priority' => $product->priority,
                'weight' => $product->weight,
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'category' => $product->category?->name,
                'gallery' => [get_multisized_image($product->image, ['m']), get_multisized_image($product->secondary_image, ['m'])],
                'message' => in_array($country, $cart->product->blocked_countries ?? []) ? trans('frontend.product.product_blocked_in_this_country') : null,
                'accessories' => $product->api_accessories($currency),
            ];
        }
        return ['carts' => $result, 'total_discount' => $total_discount,
            'total_amount' => $before_coupon_total_amount, 'final_price' => $final_price];
    }
}
