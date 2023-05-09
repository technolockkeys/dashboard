<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region carts
    public function index()
    {
        $filters[] = 'user';
        $filters[] = 'product';
        $datatable_route = route('backend.carts.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'users.name';
        $datatable_columns['image'] = 'products.image';
        $datatable_columns['title'] = 'products.title';
        $datatable_columns['quantity'] = 'quantity';
        $datatable_columns['price'] = 'price';
        $datatable_columns['discount'] = 'discount';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $users_id = Cart::pluck('user_id');
        $products_id = Cart::pluck('product_id');
        $users = User::whereIn('id', $users_id)->whereNull('deleted_at')->get();
        $products = Product::whereIn('id', $products_id)->whereNull('deleted_at')->get();

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);
        return view('backend.cart.index', compact('datatable_script', 'users', 'products'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show carts', 'admin')) {
            return abort(403);
        }
        $model = Cart::query()
            ->select('carts.*', 'products.image', 'users.name', 'products.title')
            ->join('products', 'carts.product_id', 'products.id')
            ->join('users', 'carts.user_id', 'users.id')
            ->whereNull('users.deleted_at')
            ->whereNull('products.deleted_at');

        if ($request->has('user') && $request->user != null) {
            $model = $model->where('user_id', $request->user);
        }
        if ($request->has('product') && $request->product != null) {
            $model = $model->where('product_id', $request->product);
        }

        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
//                if (permission_can('delete cart', 'admin')) {
//                    $actions .= $this->delete_button(route('backend.cart.destroy', ['ticket' => $q->id]), $q->name);
//                }
//                if (permission_can('show carts', 'admin')) {
//                    $actions .= $this->btn(route('backend.cart.show', ['ticket' => $q->id, 'from' => 'user']), '', 'las la-eye', 'btn-warning btn-show btn-icon');
//                }
                return $actions;
            })
            ->editColumn('image', function ($q) {
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . media_file($q->image) . "'> </div>";
                return $html;
            })
            ->editColumn('title', function ($q) {
                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->product->title . '</span>
                                                    </a>';
            })
            ->editColumn('name', function ($q) {
                return '<a href="' . route('backend.users.show', ['user' => $q->user_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->name . '</span>
                                                    </a>';
            })
            ->editColumn('price', function ($q) {
                return $q->price;
            })
            ->editColumn('discount', function ($q) {
                if ($q->discount != 0 && $q->coupon_code != null) {
                    $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->coupon_code . ': ' . $q->discount . '</span>';
                } else {
                    $html = '<span class="badge badge-light-warning badge-lg fw-bold  ">' . trans('backend.global.not_found') . '</span>';
                }
                return $html;

            })
            ->rawColumns(['actions', 'image', 'title', 'name', 'discount'])
            ->toJson();
    }


    #endregion
}
