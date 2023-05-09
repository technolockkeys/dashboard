<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    public function index()
    {
        $seller = Seller::findOrFail(auth('seller')->id());

        $withdraw = $seller->wallet()->where('type', 'withdraw')->get();
        $refund = $seller->wallet()->where('type', 'refund')->get();
        $approved_commission = $seller->wallet()->where('type', 'commission')->where('status', 'approve')->get();
        $pending_commission = $seller->wallet()->where('type', 'commission')->where('status', 'pending')->get();
        $waited_commission = $seller->wallet()->where('type', 'commission')->where('status', 'waiting')->get();
        $cancelled_commission = $seller->wallet()->where('type', 'commission')->where('status', 'cancelled')->get();
        $datatable_route = route('seller.wallet.datatable', ['seller' => $seller->id]);

        $datatable_columns = [];
        $datatable_columns['index'] = 'id';
        $datatable_columns['id'] = 'id';
        $datatable_columns['amount'] = 'amount';
        $datatable_columns['before_balance'] = 'before_balance';
        $datatable_columns['balance'] = 'balance';
        $datatable_columns['type'] = 'type';
        $datatable_columns['status'] = 'status';
        $datatable_columns['created_at'] = 'created_at';

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);

        $statistics = [[
            'svg' => asset('backend/media/icons/duotune/arrows/arr065.svg'),
            'sum' => $withdraw->sum('amount'),
            'name' => __('backend.seller.withdraw'),
            'number' => $withdraw->count(),
            'color' => 'danger'
        ], [
            'svg' => asset('backend/media/icons/duotune/arrows/arr065.svg'),
            'sum' => $refund->sum('amount'),
            'name' => __('backend.seller.refund'),
            'number' => $refund->count(),
            'color' => 'danger'
        ], [
            'svg' => asset('backend/media/icons/duotune/arrows/arr066.svg'),
            'sum' => $approved_commission->sum('amount'),
            'name' => __('backend.seller.approved_commission'),
            'number' => $approved_commission->count(),
            'color' => 'success'
        ], [
            'svg' => asset('backend/media/icons/duotune/arrows/arr090.svg'),
            'sum' => $pending_commission->sum('amount'),
            'name' => __('backend.seller.pending_commission'),
            'number' => $pending_commission->count(),
            'color' => 'secondary'
        ], [
            'svg' => asset('backend/media/icons/duotune/coding/cod005.svg'),
            'sum' => $waited_commission->sum('amount'),
            'name' => __('backend.seller.waited_commission'),
            'number' => $waited_commission->count(),
            'color' => 'secondary'
        ], [
            'svg' => asset('backend/media/icons/duotune/arrows/arr011.svg'),
            'sum' => $cancelled_commission->sum('amount'),
            'name' => __('backend.seller.canceled_commission'),
            'number' => $cancelled_commission->count(),
            'color' => 'danger'
        ]];


        return view('seller.wallet.show', compact('seller', 'statistics', 'datatable_script'));
    }

    public function datatable()
    {
        $seller = Seller::findOrFail(auth('seller')->id());

        $model = $seller->wallet();
        return datatables()->make($model)
            ->addColumn('index', function ($q){
                return '';
            })
            ->editColumn('amount', function ($q){
                return currency($q->amount);
            })
            ->editColumn('before_balance', function ($q){
                return currency($q->before_balance);
            })
            ->editColumn('balance', function ($q){
                return currency($q->balance);
            })

            ->editColumn('type', function ($q) {
                $html = '';
                if ($q->type == 'commission') {
                    $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->type . '</span>';

                }elseif ($q->type == 'withdraw') {
                    $html = '<span class="badge badge-light-warning badge-lg fw-bold  ">' . $q->type . '</span>';

                } else {
                    $html = '<span class="badge badge-light-info badge-lg fw-bold  ">' . $q->type . '</span>';

                }
                return $html;
            })
            ->editColumn('status', function ($q) {
                $class = "";
                if ($q->status == 'pending') {
                    $class = 'badge-primary';
                } elseif ($q->status == 'waiting') {
                    $class = 'badge-warning';
                }  elseif ($q->status == 'approve') {
                    $class = 'badge-success';
                } elseif ($q->status == 'cancelled') {
                    $class = 'badge-danger';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  ">' . $q->status . '</span>';
                return $html;

            })->rawColumns([ 'status', 'payment_method', 'type',])
            ->toJson();
    }
}
