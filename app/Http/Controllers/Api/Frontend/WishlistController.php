<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Wishlist\CreateRequest;
use App\Http\Requests\Api\User\Wishlist\DeleteRequest;
use App\Http\Requests\Api\User\Wishlist\GetRequest;
use App\Models\Currency;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    function all(GetRequest $request)
    {
        $pre_page = $request->pre_page >= 1 ? $request->pre_page : 10;
        $page = $request->page >= 1 ? $request->page : 1;
        $wishlist = Wishlist::query()->limit($pre_page)->skip(($page - 1) * 10)
            ->where('user_id', auth('api')->id())->with('product')->get();
        $currency_symbol = $request->header('currency');
        $currency = request()->header('currency');
        $currency = Currency::where(function ($q) use ($currency) {
            $q->where('symbol', $currency)->orWhere('code', $currency);
        })->where('status', 1)->first();
        if (empty($currency)) {
            $currency = Currency::where('is_default', 1)->first();
        }

        if (empty($wishlist)) {
            return response()->error(trans('api.wishlist.wishlist_is_empty'));
        }
        $wishlist_items = [];
        foreach ($wishlist as $wishlist_item) {
            $product = $wishlist_item->product;
            if ($product == null)
                continue;
            $categories_parent = $product->api_get_categories_parent();
            $wishlist_items[] = [
                'id' => $product->id,
                'stock' => $product->quantity,
                'short_title' => $product->short_title,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'hide_price' => $product->hide_price,
                'best_seller' => $product->is_best_seller,
                'is_super_sales' => $product->is_super_sales,
                'is_saudi_branch' => $product->is_saudi_branch,
                'is_featured' => $product->is_featured,
                'has_token' => isset($categories_parent[count($categories_parent) - 1]) && ($categories_parent[count($categories_parent) - 1]['slug'] == 'token' || $categories_parent[count($categories_parent) - 1]['slug'] == 'software'),

                'is_free_shipping' => $product->is_free_shipping,
                'gallery' => [get_multisized_image($product->image), get_multisized_image($product->secondary_image)],
                'avg_rating' => $product->avg_rating,
                'categories' => $product->api_get_categories_parent(),
                'total_reviews' => $product->total_reviews,
                'price' => !$product->hide_price ? api_currency($product->price, $currency) :
                    ["value" => '-', "currency" => $currency->symbol],
                'sale_price' => !$product->hide_price ? ($product->sale_price == 0 ? api_currency($product->price, $currency) : api_currency($product->sale_price, $currency)) : ["value" => '-', "currency" => $currency->symbol],

            ];
        }
        return response()->api_data(['Wishlist' => $wishlist_items, 'page' => intval($page), 'total_pages' => ceil(auth('api')->user()->wishlists()->count() / $pre_page), 'pre_page' => $pre_page, 'count' => sizeof($wishlist_items)]);
    }

    function create(CreateRequest $request)
    {

        if ($request->has('products') && !empty($request->products)) {
            foreach (explode(',', $request->products) as $item) {

                if (!empty($item)) {
                      $product_id = $item;

                    $count = Wishlist::query()->where('user_id', auth('api')->id())->where('product_id', $product_id)->count();
                    if ($count == 0) {
                        $wishlist = new Wishlist();
                        $wishlist->user_id = auth('api')->id();
                        $wishlist->product_id = $product_id;
                        $wishlist->save();
                    }
                }else{

                }
            }
            return response()->data(trans('api.wishlist.added_successfully'));

        } else {
            $count = Wishlist::query()->where('user_id', auth('api')->id())->where('product_id', $request->product_id)->count();
            if ($count == 0) {
                $wishlist = new Wishlist();
                $wishlist->user_id = auth('api')->id();
                $wishlist->product_id = $request->product_id;
                $wishlist->save();
                return response()->data(trans('api.wishlist.added_successfully'));
            } else {
                return response()->data(trans('api.wishlist.added_successfully'));

            }

        }

    }

    function delete($product_id)
    {
        Wishlist::query()->where('user_id', auth('api')->id())->where('product_id', $product_id)->delete();
        return response()->data(trans('api.wishlist.deleted_wishlist'));
    }
}
