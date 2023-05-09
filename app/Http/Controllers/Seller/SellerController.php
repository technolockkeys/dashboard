<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    public function index()
    {
        if (auth('seller')->user()->is_manager != 1) {
            return abort(403);
        }
        $filters[] = 'status';

        $datatable_route = route('seller.sellers.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['index'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['avatar'] = 'avatar';
        $datatable_columns['balance'] = 'balance';
        $datatable_columns['orders'] = 'orders';
        $datatable_columns['seller_product_rate'] = 'seller_product_rate';

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        return view('seller.seller.index', compact('datatable_script'));
    }

    public function datatable(Request $request)
    {
        if (auth('seller')->user()->is_manager != 1) {
            return abort(403);
        }

        $model = Seller::find(auth('seller')->id())->sellers();

        return datatables()->make($model)
            ->editColumn('index', function ($q) {
                return '';
            })
            ->editColumn('avatar', function ($q) {
                $avatar = $q->avatar ?: media_file();
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . $avatar . "'> </div>";
                return $html;
            })
            ->editColumn('balance', function ($q) {
                $html = "<a class='w-40px h-40px overflow-hidden' href='" . route('seller.orders.index', ['seller' => $q->id]) . "'>" . currency($q->balance) . " </a>";
//                $html = currency($q->balance);
                return $html;
            })
            ->editColumn('orders', function ($q) {
                return $q->orders->count();
            })
            ->editColumn('seller_product_rate', function ($q) {
                return $q->seller_product_rate.' %';

            })
            ->rawColumns(['avatar', 'is_manager', 'orders', 'balance'])
            ->toJson();
    }
}
