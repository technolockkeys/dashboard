<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Seller\CommissionCreateRequest;
use App\Models\Seller;
use App\Models\SellerCommission;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class SellerCommissionController extends Controller
{

    use DatatableTrait;
    use ButtonTrait;

    function index($id)
    {
        if (!permission_can('show commission seller', 'admin')) {
            return abort(403);
        }
        $seller = Seller::find($id) ;
        $filters[] = 'status';

        $datatable_route = route('backend.sellers.commission.datatable', ['id' => $id]);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['from'] = 'from';
        $datatable_columns['to'] = 'to';
        $datatable_columns['commission'] = 'commission';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null,$filters);

        $create_button = $this->create_button(route('backend.sellers.create'), trans('backend.seller.create_new_seller'));
        return view('backend.seller.commission', compact('datatable_script','seller', 'create_button'));
    }

    function datatable(Request $request, $id)
    {
        $commission = SellerCommission::query()->where('seller_id', $id);
        return datatables()->make($commission)
            ->editColumn('from' , function ($q){
                return '<span class="badge badge-success">'.currency($q->from).'</span>';
            })
            ->editColumn('to' , function ($q){
                return '<span class="badge badge-danger">'.currency($q->to).' </span>';
            })
            ->editColumn('commission' , function ($q){
                return '<span class="badge badge-info">'.$q->commission.' %</span>';
            })
            ->addColumn('actions',function ($q){
                    if (permission_can('delete commission seller','admin')){
                        return $this->delete_button(route('backend.sellers.commission.destroy',['id'=>$q->id]), $q->commission ."%");

                    }
            })
            ->rawColumns(['actions', 'from', 'to', 'commission'])
            ->tojson();
    }
    function store(CommissionCreateRequest $request){
            $commission = new SellerCommission();
            $commission->seller_id = $request->seller_id ;
            $commission->from = $request->from ;
            $commission->to = $request->to ;
            $commission->commission = $request->commission ;
            $commission->save();
            return response()->data(trans('backend.global.success_message.created_successfully'));

    }

    #region destroy
    public function destroy($id)
    {
        if (!permission_can('delete commission seller', 'admin')) {
            return abort(403);
        }
        if (SellerCommission::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion


}
