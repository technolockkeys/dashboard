<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Http\Requests\Backend\UpdateReviewRequest;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index()
    {
        if (!permission_can('show reviews', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $filters[] = 'product';
        $filters[] = 'user';
        $filters[] = 'rating';

        $datatable_route = route('backend.reviews.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'users.name';
        $datatable_columns['title'] = 'products.title';
        $datatable_columns['rating'] = 'rating';
        $datatable_columns['status'] = 'status';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $switch_route = route('backend.reviews.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);

        $products_id = Review::pluck('product_id');
        $users_id = Review::pluck('user_id');
        $products = Product::whereIn('id', $products_id)->get();
        $users = User::whereIn('id', $users_id)->get();
        $rating = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5];
        return view('backend.review.index', compact('datatable_script', 'switch_script', 'products', 'users', 'rating'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show reviews', 'admin')) {
            return abort(403);
        }
        $model = Review::query()
            ->select('reviews.*', 'products.title', 'users.name')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->whereNull('products.deleted_at')
            ->whereNull('users.deleted_at');

        if ($request->product) {
            $model->where('product_id', $request->product);
        }

        if ($request->user) {
            $model->where('user_id', $request->user);
        }
        if ($request->status != -1) {
            $model->where('reviews.status', $request->status);
        }
        if ($request->rating) {
            $model->where('rating', $request->rating);
        }


        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete review', 'admin')) {
                    $actions .= $this->delete_button(route('backend.reviews.destroy', ['review' => $q->id]), $q->name);
                }
                if (permission_can('show reviews', 'admin')) {
                    $actions .= $this->btn(route('backend.reviews.show', ['review' => $q->id]), '', 'las la-eye', 'btn-warning btn-show btn-icon');
                }
                return $actions;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status review', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->editColumn("title", function ($q) {
                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->product->title . '</span>
                                                    </a>';
            })
            ->editColumn("name", function ($q) {
                return '<a href="' . route('backend.users.show', ['user' => $q->user_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->name . '</span>
                                                    </a>';
            })
            ->rawColumns(['actions', 'name', 'title', 'status'])
            ->toJson();
    }
    #endregion

    #region show

    public function show($id)
    {
        $review = Review::findOrFail($id);
        $switch_route = route('backend.users.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $reviewReplies = ReviewReply::query()->where('review_id', $review->id)->get();
        return view('backend.review.show', compact('review', 'reviewReplies', 'switch_script'));
    }
    #endregion

    #region update
    public function update(UpdateReviewRequest $request, $id)
    {

        $review = Review::findOrFail($id);

        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->status = $request->status ?? 0;
        $review->order = $request->order ?? 0;
        $review->save();

        return redirect()->back()->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    public function storeReply(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'reply' => 'string|nullable',
            'files.*' => 'file|mimes:pdf,png,jpeg,jpg'
        ]);

        $replayNew = new ReviewReply();
        $replayNew->review_id = $request->review_id;
        $replayNew->comment = $request->reply;
        if (!empty($request->file('files'))) {
            $replayNew->files = json_encode($this->save_files($request->file('files'), $request->review_id));
        } else {
            $replayNew->files = json_encode([]);
        }
        $replayNew->user_type = Admin::class;
        $replayNew->user_id = auth('admin')->id();
        $replayNew->save();

        return back()->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region destroy
    public function destroy($id)
    {
        if (!permission_can('delete review', 'admin')) {
            return abort(403);
        }
        if (Review::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status review', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $review = Review::find($id);
        if ($review->status == 1) {
            $review->status = 0;
        } else {
            $review->status = 1;
        }
        if ($review->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #endregion
    private function save_files($files, $reviewID)
    {

        $response = [];
        try {
            if (!file_exists(public_path('storage/reviews'))) {
                mkdir(public_path('storage/reviews'), 0755, true);
            }
        } catch (\Exception $e) {
        }
        try {
            if (!file_exists(public_path('storage/reviews/' . $reviewID))) {
                mkdir(public_path('storage/reviews/' . $reviewID), 0755, true);
            }
        } catch (\Exception $e) {
        }

        foreach ($files as $file) {
            $name = $file->getClientOriginalName();
            $hash_name = $file->hashName();
            $file->move(public_path('storage/reviews/' . $reviewID), $hash_name);

            $obj = [];
            $obj['image_data'] = $name;
            $obj['hashed_name'] = $hash_name;
            $obj['path'] = 'storage/reviews/' . $reviewID . '/';
            $response[] = $obj;
        }

        return $response;

    }
}
