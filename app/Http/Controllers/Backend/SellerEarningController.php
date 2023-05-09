<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use App\Models\SellerEarning;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\OrderTrait;
use Illuminate\Http\Request;

class SellerEarningController extends Controller
{
    use ButtonTrait;
    use OrderTrait;
    use DatatableTrait;

    public function index()
    {
        if (!permission_can('show seller earnings', 'admin')) {
            return abort(403);
        }

        $filters = [];
        $filters[] = 'years';
        $filters[] = 'months';
        $filters[] = 'sellers';
        $filters[] = 'from_value';
        $filters[] = 'to_value';
        $datatable_route = route('backend.sellers.sellers-earning.datatable');
        #region data table  columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['seller'] = 'sellers.name';
        $datatable_columns['date'] = 'date';
        $datatable_columns['total_orders'] = 'total_orders';
        $datatable_columns['commissions'] = 'commissions';
        $datatable_columns['earnings'] = 'earnings';
        $datatable_columns['created_at'] = 'created_at';
        #endregion

        $months = ["January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"];
        $years = SellerEarning::query()->groupBy('year')->pluck('year');
        $sellers = SellerEarning::query()->pluck('seller_id');
        $sellers = Seller::whereIn('id', $sellers)->get();
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);
        return view('backend.seller.sellers_earning', compact('datatable_script', 'months', 'years', 'sellers'));

    }

    public function datatable(Request $request)
    {
        if (!permission_can('show seller earnings', 'admin')) {
            return abort(403);
        }
        $model = SellerEarning::query()->select('seller_earning.*', 'sellers.name', 'sellers.avatar')
            ->join('sellers', 'seller_earning.seller_id', 'sellers.id');

        if ($request->years != null) {
            $model = $model->where('year', $request->years);
        }

        if ($request->months != null) {
            $model = $model->where('month', $request->months);
        }
        if ($request->sellers != null) {

            $model = $model->where('seller_id', $request->sellers);
        }

        if($request->from_value != null || $request->to_value != null){
            $model = $model->whereBetween('total_orders', [$request->from_value??0, $request->to_value]);
        }

        return datatables()->make($model)
            ->editColumn('seller', function ($q) {
                return '<div class="d-flex align-items-center">
																		<div class="symbol symbol-25px me-5">
																			<img src="' . asset($q->avatar) . '" onerror="this.src=' . "'" . asset('backend/media/avatars/blank.png') . "'" . '" alt="">
																		</div>
																		<div class="d-flex justify-content-start flex-column">
																			<a href="'.route('backend.sellers.edit' , $q->seller_id).'"  class="text-dark fw-bolder text-hover-primary fs-6">' . $q->name . '</a>
 																		</div>
																	</div>';

            })
            ->editColumn('id', function ($q) {
                return '<span class="text-dark fw-bold">' . $q->id . '</span>';
            })
            ->editColumn('total_orders', function ($q) {
                return '<span class="   text-dark fw-bold">' . currency($q->total_orders) . '</span>';
            })
            ->editColumn('earnings', function ($q) {
                return '<span class="   text-dark fw-bold">' . currency($q->earnings) . '</span>';
            })

            ->editColumn('commissions', function ($q) {
                return '<span class=" text-dark fw-bold"> <b class="la la-percent"></b>' . $q->commissions . '</span>';
            })
            ->rawColumns(['id', 'seller','earnings', 'commissions', 'total_orders'])
            ->toJson();
    }
}
