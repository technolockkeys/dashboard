<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\Review\GetRequest;
use App\Http\Requests\Api\User\Order\Review\CreateRequest;
use App\Http\Requests\Api\User\Order\Review\DeleteRequest;
use App\Http\Requests\Api\User\Order\Review\GetByProductRequest;
use App\Models\Admin;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\User;
use Request;

class ReviewController extends Controller
{

    public function get_all(GetRequest $request)
    {
//        $product = Product::where('slug', $request->product_slug)->with('reviews')->first();
//        $length = 12;
//        $page = 1;
        $reviews = Review::query()
            ->select('reviews.*')
            ->join('products', 'reviews.product_id', 'products.id')
            ->where('reviews.status', 1)
            ->where('products.slug', $request->product_slug)
            ->orderBy('reviews.order', 'desc')
            ->get();
        /*
        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $reviews = $product->reviews;
*/
        $res_reviews = [];
        foreach ($reviews as $review) {
            $replies = ReviewReply::query()
                ->whereNotNull('comment')
                ->select('comment', 'id', 'user_type', 'user_id', 'created_at')->where('review_id', $review->id)->get();
            $replies_data = [] ;
            foreach ($replies as $reply) {
                if ($reply->user_type == Admin::class) {
                    $reply->user = Admin::find($reply->user_id)?->name;
                } else {
                    $user = User::find($reply->user_id);
                    $reply->user =$user?->first_name . ' '. $user->last_name;
                }
                $replies_data[]=[
                    'comment'=>$reply->comment,
                    'user_type'=>$reply->user_type,
                    'user'=>$reply->user,
                    'created_at'=>$reply->created_at->format('Y-m-d H:i'),
                ];
            }
            $res_reviews[] = [
                'avatar' => asset($review->user?->avatar ?? 'backend/media/avatars/blank.png'),
                'user_name' => substr_replace($review->user?->name, '****', 3, strlen($review->user?->name)),
                'rating' => $review->rating,
                'comment' => $review->comment,
                'replies' => $replies_data,
                'created_at' => $review->created_at->format('Y-m-d H:i')
            ];
        }
        return response()->api_data(['total' => count($reviews), 'result' => $res_reviews]);

    }

    function create(CreateRequest $request)
    {

        $product = Product::where('slug', $request->product_slug)->first();

        if ($product == null) {
            return response()->api_error([trans('api.cart.coupon.not_found_product')]);
        }

        $check = Order::query()
            ->join('orders_products', 'orders_products.order_id', 'orders.id')
            ->where('user_id', auth('api')->id())
            ->where('product_id', $product->id)
            ->count();
        $check2 = Review::query()
            ->where('product_id', $product->id)
            ->where('user_id', auth('api')->id())->count();
        if ($check == 0) {
            return response()->api_error(trans('api.review.you_cant_review'));
        }
        if ($check2 != 0) {
            return response()->api_error(trans('api.review.already_reviewed'));
        }
        $review = new Review();
        $review->user_id = auth('api')->id();
        $review->product_id = $product->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->status = 0;
        $review->save();

        $product = Product::find($product->id);
        $product->avg_rating = ($product->avg_rating + $request->rating) / ($product->total_reviews + 1);
        $product->total_reviews++;
        $product->save();
        return response()->api_data(['message' => trans('api.review.added_successfully'), 'data' => ['rating' => $review->rating, 'comment' => $review->comment]]);
    }

    function delete(DeleteRequest $request)
    {
        Review::query()->where('id', $request->id)->delete();
        return response()->data(trans('api.review.deleted_successfully'));
    }

    function getByProduct(GetByProductRequest $request)
    {
        $page = $request->page >= 1 ? $request->page : 1;
        $limit = $request->limit >= 1 ? $request->limit : 10;
        $data = Review::query()
            ->where('product_id', $request->id)
            ->orderByDesc('reviews.order')
            ->skip(($page - 1) * $limit)->limit($limit)->with('user')->get();
        return response()->data(['data' => $data]);
    }

    public function get_my_reviews(\Illuminate\Http\Request $request)
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
        $total = $user->reviews()->count();
        $user_reviews = $user->reviews()->skip(($page - 1) * $length)->limit($length)->with('product')->get();

        $reviews = [];
        $currency = Currency::where('is_default', 1)->first();
        foreach ($user_reviews as $review) {
            $replies = ReviewReply::query()
                ->select('comment', 'files', 'created_at')->where('review_id', $review->id)->get();

            $reviews [] = [
                'user_id' => $review->user_id,
                'product_id' => $review->product_id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'product' => $review->product->api_shop_data($currency),
                'replies' => $replies,
                'status' => $review->status,
                'created_at' => $review->created_at->format('Y-m-d H:i'),
            ];
        }


        return response()->api_data(['data' => $reviews, 'total' => $total, 'page' => intval($page), 'total_pages' => ceil($total / $length), 'length' => sizeof($reviews)]);
    }

//    public function getAllWithProduct(Request $request)
//    {
//        $reviews = Review::query()->where('user_id', auth('api')->id());
//        if ($request->length >= 1) {
//            $length = $request->length;
//        }
//        if ($request->page >= 1) {
//            $page = $request->page;
//        }
//        $total = $reviews->count();
//        $reviews = $reviews->skip(($page - 1) * $length)->limit($length)->get();
//        $reviews = $reviews->with('product')->get();
//        return response()->api_data(['data' => $reviews, 'total' => $total, 'page' => intval($page), 'total_pages' => ceil($total / $length), 'length' => sizeof($reviews)]);
//
//
//    }

}
