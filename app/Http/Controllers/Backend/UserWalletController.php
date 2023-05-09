<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OrderPayment;
use App\Models\UserWallet;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserWalletController extends Controller
{
    use DatatableTrait;

    function index()
    {
        if (!permission_can('show user wallet', 'admin')) {
            return abort(403);
        }
        $datatable_route = route('backend.user_wallet.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['user_id'] = 'user_id';
        $datatable_columns['seller_name'] = 'seller_name';
        $datatable_columns['amount'] = 'amount';
        $datatable_columns['status'] = 'status';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';

        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, [], null, null, null, false, false, false);

        return view('backend.user_wallet.index', compact('datatable_script'));
    }

    function datatable(Request $request)
    {
        if (!permission_can('show user wallet', 'admin')) {
            return abort(403);
        }
        $order_pending = UserWallet::query()
            ->select('user_wallet.*',DB::raw('sellers.name as seller_name') ,'orders.seller_id', 'users.name', 'users.avatar', 'users.provider_type', 'orders.uuid', 'orders.payment_method')
            ->where('user_wallet.status', OrderPayment::$pending)
            ->leftJoin('orders', 'orders.id', 'user_wallet.order_id')
            ->leftJoin('sellers', 'orders.seller_id', 'sellers.id')
            ->join('users', 'users.id', 'user_wallet.user_id');
        $default_images = media_file(get_setting('default_images'));
        $permission_approve = permission_can('approve user wallet', 'admin');
        return datatables()->make($order_pending)
            ->editColumn('id', function ($q) {
                $order_payment = OrderPayment::query()->where('id', $q->order_payment_id)->first();
                $view = '';
//                return $order_payment->payment_method;
                if (!empty($order_payment)) {
                    $view = '<span class="badge badge-light-dark mb-3"> ' . trans('backend.order.' . $order_payment->payment_method) . '</span><br>';
                }
                $view .= '<a href="' . route('backend.orders.show', ['order' => $q->order_id]) .
                    '" class="text-gray-800 fw-bolder text-hover-primary small fs-6"><span class="badge badge-light-dark">#' . $q->uuid . '</span></a>';
                return $view;


            })
            ->editColumn('amount', function ($q) {
                return '<span class="badge badge-light-primary"> ' . currency($q->amount) . '</span>';

            })
            ->editColumn('seller_name', function ($q) {
                if (!empty($q->seller_id) && !empty($q ->seller_name )){
                    return '<a href="'.route('backend.sellers.edit' ,$q->seller_id).'" class="badge badge-light-info"> ' .$q ->seller_name . '</a>';
                }
                return '<a class="badge badge-secondary"> <i class="la la-minus"></i></a>';


            })
            ->editColumn('status', function ($q) {
                return '<span class="badge badge-light-success"> ' . trans('backend.order.' . $q->status) . '</span>';
            })
            ->editColumn('created_at', function ($q) {
                return '<span class="small text-dark badge-light-secondary badge">' . $q->created_at . '</span>';
            })
            ->editColumn('user_id', function ($q) use ($default_images) {
                $url = "";
                if ($q->provider_type == 'email') {
                    $url = asset($q->avatar);
                } else {
                    $url = $q->avatar;
                }
                return '<div class="d-flex align-items-center  ">
                            <div class="symbol symbol-30px me-3">
                                <img src="' . $url . '"  onerror="' . $default_images . '">
                            </div>
                            <a href="' . route('backend.users.show', ['user' => $q->user_id]) .
                    '" class="text-gray-800 fw-bolder text-hover-primary small fs-6">
                            <span class="text-gray-600 small ">' . $q->name . '</span></a>
                    </div>';
            })
            ->addColumn('actions', function ($q) use ($permission_approve) {
                $view = '';
                if ($permission_approve) {
//                    if ($q->payment_method == OrderPayment::$stripe_link || $q->payment_method == '')
//                        $view = '<button data-id="' . $q->id . '" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-icon approve_order_payment btn-sm"  ><i class="la la-check"></i></button>';
//                    if ($q->payment_method == OrderPayment::$transfer) {
                    $view = '<button data-id="' . $q->id . '" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary  show_transfer_order btn-sm"  ><i class="la la-wallet"></i> ' . trans('backend.global.show') . '</button>';

//                    }
                }
                return $view;
            })
            ->rawColumns(['actions', 'id', 'created_at','seller_name', 'status', 'amount', 'user_id'])
            ->toJson();
    }
}
