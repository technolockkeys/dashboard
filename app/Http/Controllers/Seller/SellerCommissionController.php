<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerCommission;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class SellerCommissionController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    function index()
    {
//        if (!permission_can('show commission seller', 'admin')) {
//            return abort(403);
//        }
        $seller = Seller::find( auth('seller')->id()) ;
        $filters[] = 'status';

        $datatable_route = route('seller.commission.datatable', ['id' => $seller->id]);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['index'] = '';
        $datatable_columns['from'] = 'from';
        $datatable_columns['to'] = 'to';
        $datatable_columns['commission'] = 'commission';
        $datatable_columns['created_at'] = 'created_at';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null,$filters);

        $create_button = $this->create_button(route('backend.sellers.create'), trans('backend.seller.create_new_seller'));
        return view('seller.commission.commission', compact('datatable_script','seller', 'create_button'));
    }

    function datatable(Request $request)
    {
        $commission = SellerCommission::query()->where('seller_id', auth('seller')->id());
        return datatables()->make($commission)
            ->editColumn('index' , function ($q){
                return '';
            })
            ->editColumn('from' , function ($q){
                return '<span class="badge badge-success">'.currency($q->from).'</span>';
            })
            ->editColumn('to' , function ($q){
                return '<span class="badge badge-danger">'.currency($q->to).' </span>';
            })
            ->editColumn('commission' , function ($q){
                return '<span class="badge badge-info">'.$q->commission.' %</span>';
            })
            ->rawColumns(['actions', 'from', 'to', 'commission'])
            ->tojson();
    }
}
