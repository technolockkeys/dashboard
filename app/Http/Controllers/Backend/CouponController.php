<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Coupon\CreateRequest;
use App\Http\Requests\Backend\Coupon\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Coupon;
use App\Models\Product;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\RandomCodeGeneratorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class CouponController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use RandomCodeGeneratorTrait;

    #region index
    public function index()
    {
        if (!permission_can('show coupons', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $filters[] = 'type';
        $filters[] = 'discount_type';
        $filters[] = 'discount';
        $filters[] = 'product';
        $filters[] = 'ends_at';
        $filters[] = 'starts_at';
        $filters[] = 'per_user';
        $filters[] = 'max_use';


        $datatable_route = route('backend.coupons.datatable');
        $delete_all_route = route('backend.coupons.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['code'] = 'code';
        $datatable_columns['type'] = 'type';
        $datatable_columns['discount'] = 'discount';
        $datatable_columns['discount_type'] = 'discount_type';
        $datatable_columns['max_use'] = 'max_use';
        $datatable_columns['starts_at'] = 'starts_at';
        $datatable_columns['ends_at'] = 'ends_at';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $types = ['Product' => trans('backend.coupon.products'), 'Order' => trans('backend.dashboard.order')];
        $discount_types = ['Amount' => trans('backend.order.amount'), 'Percentage' => trans('backend.product.percent')];

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns,$delete_all_route ,$filters);
        $switch_script = null;
        $switch_route = route('backend.coupons.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = $this->create_button(route('backend.coupons.create'), trans('backend.coupon.create_new_coupon'));
        $products = Product::where('status',1)->get();

        return view('backend.coupon.index', compact('datatable_script', 'switch_script', 'create_button','types', 'discount_types','products'));
    }

    public function datatable(Request $request)
    {

        if (!permission_can('show coupons', 'admin')) {
            return abort(403);
        }
        $model = Coupon::query();
        if ($request->has('status') && $request->status !=-1) {
            $model = $model->where('status', $request->status);
        }
        if($request->has('type') && $request->type != null){
            $model = $model->where('type', $request->type);
        }
        if($request->has('discount_type') && $request->discount_type != null){
            $model = $model->where('discount_type', $request->discount_type);
        }
        if($request->has('discount') && $request->discount != null){
            $model = $model->where('discount', $request->discount);
        }

        if($request->has('starts_at') && $request->starts_at  != null){
            $model = $model->where('starts_at','>' ,$request->starts_at);
        }
        if($request->has('ends_at') && $request->ends_at  != null){
            $model = $model->where('ends_at','<' ,$request->ends_at);
        }
        if($request->has('max_use') && $request->max_use  != null){
            $model = $model->where('max_use',$request->max_use);
        }
        if($request->has('per_user') && $request->per_user  != null){
            $model = $model->where('per_user',$request->per_user);
        }

        if($request->product){
            $model = $model->where('products_ids', 'like', '%"'.$request->product.'"'.'%');
        }

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q){
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';

                if (permission_can('edit coupon', 'admin')) {
                    $actions .= $this->edit_button(route('backend.coupons.edit', ['coupon' => $q->id]));
                }
                if (permission_can('delete coupon', 'admin')) {
                    $actions .= $this->delete_button(route('backend.coupons.destroy', ['coupon' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->editColumn('type', function ($q){
                $badge_type = $q->type == 'Product'? 'badge-light-primary': 'badge-light-warning';
                return '<span class="badge badge-lg '.$badge_type.' fw-bold "  >' . $q->type . '</span>';
            })
            ->editColumn('discount_type', function ($q){
                $badge_type = $q->discount_type == 'Amount'? 'badge-light-primary': 'badge-light-warning';
                return '<span class="badge badge-lg '.$badge_type.' fw-bold "  >' . $q->discount_type . '</span>';
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status coupon', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status','type', 'discount_type'])
            ->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        if (!permission_can('create coupon', 'admin')) {
            return abort(403);
        }

        $code = 'TLCO'.$this->RandomString(9);
        while(!empty(Coupon::where('code',$code)->first())){
            $code = 'TLCO'.$this->RandomString(9);
        }
        $products = Product::all();
        $types = ['Product', 'Order'];
        $discount_types = ['Amount', 'Percentage'];
        return view('backend.coupon.create', compact('products', 'types', 'discount_types', 'code'));
    }

    public function store(CreateRequest $request)
    {
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        if ($request->type == 'Order') {
            $coupon->products_ids = [];
            $coupon->minimum_shopping = $request->minimum_shopping;
        }
        elseif ($request->type == 'Product'){
            $coupon->products_ids = $request->products_ids;
            $coupon->minimum_shopping = '';
        }
        $coupon->discount = $request->discount;
        $coupon->max_use = $request->max_use;
        $coupon->per_user = $request->per_user;
        $coupon->discount_type = $request->discount_type;
        $coupon->starts_at = $request->start_date;
        $coupon->ends_at = $request->end_date;
        $coupon->status = $request->has('status') ? 1 : 0;

        $coupon->save();
        return redirect()->route('backend.coupons.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region edit
    public function edit($id)
    {
        if (!permission_can('edit coupon', 'admin')) {
            return abort(403);
        }
        $coupon = Coupon::findOrFail($id);
        $products = Product::all();
        $types = ['Product', 'Order'];
        $discount_types = ['Amount', 'Percentage'];
        return view('backend.coupon.edit', compact('coupon', 'products', 'types', 'discount_types'));

    }

    public function update(UpdateRequest $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->discount = $request->discount;
        $coupon->discount_type = $request->discount_type;
        if ($request->type == 'Order') {
            $coupon->products_ids = [];
            $coupon->minimum_shopping = $request->minimum_shopping;
        }
        elseif ($request->type == 'Product'){
            $coupon->products_ids = $request->products_ids;
            $coupon->minimum_shopping = '';
        }
        $coupon->max_use = $request->max_use;
        $coupon->per_user = $request->per_user;
        $coupon->starts_at = $request->start_date;
        $coupon->ends_at = $request->end_date;
        $coupon->status = $request->has('status') ? 1 : 0;

        $coupon->save();
        return redirect()->route('backend.coupons.index', $coupon)->with('success', trans('backend.global.success_message.updated_successfully'));

    }

    #endregion

    #region delete
    public function destroy($id)
    {
        if (!permission_can('delete coupon', 'admin')) {
            return abort(403);
        }

        if (Coupon::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete coupon', 'admin')){
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Coupon::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status coupon', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $coupon = Coupon::find($id);
        if ($coupon->status == 1) {
            $coupon->status = 0;
        } else {
            $coupon->status = 1;
        }
        if ($coupon->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion


}
