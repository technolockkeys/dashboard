<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Seller\CreateRequest;
use App\Http\Requests\Backend\Seller\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeColumnStatusRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerEarning;
use App\Models\User;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SellerController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index()
    {
        if (!permission_can('show sellers', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';

        $datatable_route = route('backend.sellers.datatable');
        $delete_all_route = permission_can('delete seller', 'admin') ? route('backend.sellers.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'sellers.id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['avatar'] = 'avatar';
        $datatable_columns['earnings'] = 'seller_earnings.earnings';
        $datatable_columns['orders'] = 'orders';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['role'] = 'role';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters,
            $data_send = [], $method = "post", $id = "datatable", $status = true, $search = true, $export = true, $name = '', ['orders']);
        $switch_column = route('backend.sellers.change.column');
        $switch_script_manager = $this->change_column_switch_script($switch_column, 'is_manager');

        $switch_script = null;
        $switch_route = route('backend.sellers.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = $this->create_button(route('backend.sellers.create'), trans('backend.seller.create_new_seller'));
        return view('backend.seller.index', compact('datatable_script', 'switch_script', 'create_button', 'switch_script_manager'));
    }

    public function datatable(Request $request)
    {

        if (!permission_can('show sellers', 'admin')) {
            return abort(403);
        }
        $orders = Order::query()
            ->selectRaw('count(*) as orders , seller_id')
            ->whereNull('deleted_at')

            ->groupBy('seller_id');
        $seller_earning = SellerEarning::query()->selectRaw('sum(seller_earning.earnings) as earnings , seller_id')->whereNull('deleted_at')->groupBy('seller_id');
        $model = Seller::query()
            ->select(\DB::raw('orderss.orders'), 'sellers.*', \DB::raw("COALESCE(seller_earnings.earnings,'0')  as earnings "))
            ->leftJoin(DB::raw("(" . $orders->toSql() . ") as orderss"), 'orderss.seller_id', '=', 'sellers.id')
            ->leftJoin(DB::raw("(" . $seller_earning->toSql() . ") as seller_earnings"), 'seller_earnings.seller_id', '=', 'sellers.id')
            ->groupBy('sellers.id')
            ->with('orders');

        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }

        if ($request->order[0]['column'] == 0) {
            $model->orderByDesc('sellers.id');
        }


        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete seller', 'admin')) {
                    $actions .= $this->delete_button(route('backend.sellers.destroy', ['seller' => $q->id]), $q->name);
                }
                if (permission_can('show sellers', 'admin')) {
                    $actions .= $this->edit_button(route('backend.sellers.edit', ['seller' => $q->id]));
                }
                if (permission_can('show commission seller', 'admin')) {
                    $actions .= $this->btn(route('backend.sellers.commission.get', ['id' => $q->id]), trans('backend.order.seller_commission'), 'fa fa-percent', 'btn-primary');
                }
                return $actions;
            })
            ->editColumn('avatar', function ($q) {
                $avatar = $q->avatar ?: media_file();
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . $avatar . "?t=" . time() . "'> </div>";
                return $html;
            })
            ->editColumn('earnings', function ($q) {
                $html = '<span class="badge badge-secondary"><i class="la la-minus"></i> </span>';
                if (!empty($q->earnings))
                    $html = "<span class='ms-2 badge badge-light-primary fw-bold'>" . currency($q->earnings) . " </span>";
                return $html;
            })
            ->editColumn('orders', function ($q) {
                if (empty($q->orders))
                    return '<span class="badge badge-secondary"><i class="la la-minus"></i> </span>';
                return '<span class="badge badge-primary">' . $q->orders . ' </span>';
            })
            ->addColumn('role', function ($q) {
                $roles = $q->getRoleNames();
                $html = '';
                if (empty(json_decode($roles))) {
                    return '<span class="ms-2 badge badge-light-primary fw-bold  ">-</span>';
                }
                foreach ($roles as $role) {
                    $html .= '<span class="ms-2 badge badge-light-primary fw-bold  ">' . $role . '</span>';
                }
                return $html;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status seller', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'avatar', 'earnings', 'role', 'orders', 'balance'])
            ->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        $sellers = Seller::where('is_manager', 1)->get();
        $roles = Role::query()->where('guard_name', 'seller')->get();

        return view('backend.seller.create', compact('sellers', 'roles'));
    }

    public function store(CreateRequest $request)
    {
        $seller = Seller::make($request->except('password'));
        $seller->save();
        if (!empty($request->file('avatar'))) {

            $avatar = $request->file('avatar');
            try {
                if (!file_exists(public_path('storage/seller'))) {
                    mkdir(public_path('storage/seller'), true);
                }
            } catch (\Exception $e) {
            }
            $image_data = $seller->StoreAvatarImage('avatar', $seller->id, 'seller');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $seller->status = $request->has('status') ? 1 : 0;
            $seller->is_manager = $request->has('manager') ? 1 : 0;
            $seller->seller_manger = $request->seller_manger;
            $seller->avatar = $avatar_link;

            $seller->update(['avatar' => $avatar_link]);

        }
        if (!empty($request->password)) {
            $seller->password = Hash::make($request->password);
        }
        $seller->save();
        $role = Role::find($request->role);
        $seller->syncRoles($role);
        return redirect()->route('backend.sellers.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }

    #endregion

    #region edit
    public function edit($id)
    {

        $seller = Seller::findOrFail($id);
        $sellers = Seller::where('is_manager', 1)->get();
        $roles = Role::query()->where('guard_name', 'seller')->get();

        return view('backend.seller.edit', compact('seller', 'roles', 'sellers'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $avatar_link = '';

        if (!empty($request->file('avatar'))) {
            $avatar = $request->file('avatar');
            if (!file_exists(public_path('storage/seller'))) {
                mkdir(public_path('storage/seller'), true);
            }
            $image_data = $seller->StoreAvatarImage('avatar', $seller->id, 'seller');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $seller->avatar = $avatar_link;
        }
        $seller->update($request->except('avatar', 'status','password'));
        $seller->seller_manger = $request->seller_manger;
        $seller->status = $request->has('status') ? 1 : 0;
        $seller->is_manager = $request->has('manager') ? 1 : 0;
        if (!empty($request->password)) {
            $seller->password = Hash::make($request->password);
        }

        $seller->save();

        $role = Role::find($request->role);
        $seller->syncRoles($role);
        return redirect()->route('backend.sellers.index')->with('success', __('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region wallet

    public function wallet($seller_id)
    {
        $seller = Seller::findOrFail($seller_id);

        $withdraw = $seller->wallet()->where('type', 'withdraw')->get();
        $refund = $seller->wallet()->where('type', 'refund')->get();
        $approved_commission = $seller->wallet()->where('type', 'commission')->where('status', 'approve')->get();
        $pending_commission = $seller->wallet()->where('type', 'commission')->where('status', 'pending')->get();
        $waited_commission = $seller->wallet()->where('type', 'commission')->where('status', 'waiting')->get();
        $cancelled_commission = $seller->wallet()->where('type', 'commission')->where('status', 'cancelled')->get();
        $datatable_route = route('backend.sellers.wallet_datatable', ['seller' => $seller_id]);

        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['amount'] = 'amount';
        $datatable_columns['before_balance'] = 'before_balance';
        $datatable_columns['balance'] = 'balance';
        $datatable_columns['type'] = 'type';
        $datatable_columns['status'] = 'status';
        $datatable_columns['created_at'] = 'created_at';

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);

        $statistics = [[
            'svg' => asset('backend/media/icons/duotune/finance/fin003.svg'),
            'sum' => $withdraw->sum('amount'),
            'name' => __('backend.seller.withdraw'),
            'number' => $withdraw->count(),
        ], [
            'svg' => asset('backend/media/icons/duotune/finance/fin003.svg'),
            'sum' => $refund->sum('amount'),
            'name' => __('backend.seller.refund'),
            'number' => $refund->count(),
        ], [
            'svg' => asset('backend/media/icons/duotune/arrows/arr020.svg'),
            'sum' => $approved_commission->sum('amount'),
            'name' => __('backend.seller.approved_commission'),
            'number' => $approved_commission->count(),
        ], [
            'svg' => asset('backend/media/icons/duotune/general/gen012.svg'),
            'sum' => $pending_commission->sum('amount'),
            'name' => __('backend.seller.pending_commission'),
            'number' => $pending_commission->count(),
        ], [
            'svg' => asset('backend/media/icons/duotune/general/gen013.svg'),
            'sum' => $waited_commission->sum('amount'),
            'name' => __('backend.seller.waited_commission'),
            'number' => $waited_commission->count(),
        ], [
            'svg' => asset('backend/media/icons/duotune/arrows/arr015.svg'),
            'sum' => $cancelled_commission->sum('amount'),
            'name' => __('backend.seller.canceled_commission'),
            'number' => $cancelled_commission->count(),
        ]];


        return view('backend.seller.wallet', compact('seller', 'statistics', 'datatable_script'));
    }

    public function wallet_datatable($seller_id)
    {
        $seller = Seller::findOrFail($seller_id);

        $model = $seller->wallet();
        return datatables()->make($model)->toJson();
    }
    #endregion

    #region seller orders
    public function orders($seller_id)
    {
        $datatable_route = route('backend.sellers.orders.datatable', ['seller' => $seller_id]);

        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['uuid'] = 'uuid';
        $datatable_columns['user'] = 'user';
        $datatable_columns['payment_method'] = 'payment_method';
        $datatable_columns['payment_status'] = 'payment_status';
        $datatable_columns['total'] = 'total';
        $datatable_columns['shipping'] = 'shipping';
        $datatable_columns['status'] = 'status';
        $datatable_columns['coupon'] = 'coupon_value';
        $datatable_columns['type'] = 'type';
        $datatable_columns['tracking_number'] = 'tracking_number';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);

        $seller = Seller::findOrFail($seller_id);
        return view('backend.seller.orders', compact('datatable_script', 'seller'));

    }

    public function orders_datatable($seller_id)
    {
        $model = Order::query()
            ->where('orders.seller_id', $seller_id)
            ->join('users', 'orders.user_id', 'users.id')
            ->select('orders.*', 'users.name as user');

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('show orders', 'admin')) {
                    $actions .= $this->btn(route('backend.orders.show', ['order' => $q->id, 'from' => 'user']), '', 'las la-eye', 'btn-warning btn-show btn-icon');
                }
                return $actions;
            })->editColumn('tracking_number', function ($q) {
                $text = $q->tracking_number ?? __('backend.global.not_found');
                return '<span class="badge badge-light-info badge-lg fw-bold  ">' . $text . '</span>';
            })
            ->editColumn('total', function ($q) {
                return '<span class="badge badge-light-info badge-lg fw-bold  ">' . currency($q->total) . '</span>';
            })
            ->editColumn('user', function ($q) {
                $user = $q->user ?? trans('backend.global.not_found');
                $class = $q->user ? 'dark' : 'danger';

                return '<span class="badge badge-light-' . $class . '"> ' . $user . '</span>';
            })
            ->editColumn('shipping', function ($q) {
                return '<span class="badge badge-light-primary badge-lg fw-bold  ">' . currency($q->shipping) . '</span>';
            })
            ->editColumn('payment_method', function ($q) {
                $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->payment_method . '</span>';
                return $html;
            })
            ->editColumn('coupon', function ($q) {
                $html = '<span class="badge badge-secondary badge-lg fw-bold  ">' . currency($q->coupon_value) . '</span>';
                return $html;
            })
            ->editColumn('type', function ($q) {
                $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->type . '</span>';
                return $html;
            })
            ->editColumn('payment_status', function ($q) {
                if ($q->payment_status == 'paid') {
                    $class = 'badge-light-success';
                } elseif ($q->payment_status == 'unpaid') {
                    $class = 'badge-light-warning';
                } else {
                    $class = 'badge-light-error';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  ">' . $q->payment_status . '</span>';
                return $html;
            })
            ->editColumn('status', function ($q) {
                if ($q->status == 'completed') {
                    $class = 'badge-primary';
                } elseif ($q->status == 'on_hold') {
                    $class = 'badge-warning';
                } elseif ($q->status == 'refunded' || $q->status == 'canceled' || $q->status == 'pending_payment') {
                    $class = 'badge-secondary';
                } elseif ($q->status == 'processing') {
                    $class = 'badge-success';
                } elseif ($q->status == 'failed') {
                    $class = 'badge-error';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  ">' . $q->status . '</span>';
                return $html;

            })
            ->rawColumns(['actions', 'status', 'user', 'payment_method', 'payment_status', 'type', 'total', 'shipping', 'coupon', 'tracking_number'])
            ->toJson();
    }

    #endregion

    #region change column
    function change_value_column(ChangeColumnStatusRequest $request)
    {

        $id = $request->id;
        $seller = Seller::find($id);
        if ($seller[$request->column] == 1) {
            $seller[$request->column] = 0;
        } else {
            $seller[$request->column] = 1;
        }
        if ($seller->save()) {
            return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status seller', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $seller = Seller::findOrFail($id);
        if ($seller->status == 1) {
            $seller->status = 0;
        } else {
            $seller->status = 1;
        }
        if ($seller->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion

    #region destroy
    public function destroy($id)
    {
        if (!permission_can('delete seller', 'admin')) {
            return abort(403);
        }
        if (Seller::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete seller', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Seller::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
}
