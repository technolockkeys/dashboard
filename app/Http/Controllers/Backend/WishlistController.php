<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;


    #region tickets
    public function index()
    {
        $filters[] = 'product';
        $filters[] = 'user';
        $datatable_route = route('backend.wishlists.datatable');
        $delete_all_route = permission_can('delete wishlist', 'admin') ? route('backend.wishlists.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['user_id'] = 'user_id';
        $datatable_columns['product'] = 'product';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $products_id = Wishlist::pluck('product_id');
        $users_id = Wishlist::pluck('user_id');
        $products = Product::whereIn('id', $products_id)->get();
        $users = User::whereIn('id', $users_id)->get();

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        return view('backend.wishlist.index', compact('datatable_script', 'users', 'products'));
    }

    public function datatable(Request $request)
    {

        if (!permission_can('show wishlists', 'admin')) {
            return abort(403);
        }
        $model = Wishlist::query()
            ->select('wishlists.*', 'users.name', 'products.title')
            ->join('users', 'wishlists.user_id', '=', 'users.id')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->whereNull('users.deleted_at')
            ->whereNull('products.deleted_at')
            ->orderBy('user_id')
            ->groupBy('user_id', 'product_id');

        if ($request->has('product') && $request->product != null) {
            $model = $model->where('product_id', $request->product);
        }
        if ($request->has('user') && $request->user != null) {
            $model = $model->where('user_id', $request->user);
        }
        return datatables()->make($model)->addColumn('placeholder', function ($q) {
            return '';
        })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete wishlist', 'admin')) {
                    $actions .= $this->delete_button(route('backend.wishlists.destroy', ['wishlist' => $q->id]));
                }
                return $actions;
            })
            ->addColumn('product', function ($q) {
                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->product->title . '</span>
                                                    </a>';
            })
            ->editColumn('user_id', function ($q) {
                return '<a href="' . route('backend.users.show', ['user' => $q->user_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->name . '</span>
                                                    </a>';
            })
            ->rawColumns(['actions', 'product', 'user_id'])
            ->toJson();
    }

    #endregion

    public function destroy($id)
    {
        if (Wishlist::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete wishlist', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Wishlist::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
}
