<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OfferRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Product;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\RandomCodeGeneratorTrait;
use App\Traits\SerializeDateTrait;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use RandomCodeGeneratorTrait;
    use SerializeDateTrait;

    #region index
    public function index()
    {
        if (!permission_can('show offers', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $filters[] = 'type';
        $filters[] = 'coupon_type';

        $datatable_route = route('backend.offers.datatable');
        $delete_all_route = permission_can('delete offer', 'admin') ? route('backend.offers.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['from'] = 'from';
        $datatable_columns['to'] = 'to';
        $datatable_columns['days'] = 'days';
        $datatable_columns['discount'] = 'discount';
        $datatable_columns['discount_type'] = 'discount_type';
        $datatable_columns['type'] = 'type';
        $datatable_columns['free_shipping'] = 'free_shipping';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $discount_types = ['Amount', 'Percentage'];

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.offers.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = $this->create_button(route('backend.offers.create'), trans('backend.offer.create_new_offer'));
        return view('backend.offer.index', compact('datatable_script', 'switch_script', 'create_button', 'discount_types'));
    }

    public function datatable(Request $request)
    {

        if (!permission_can('show offers', 'admin')) {
            return abort(403);
        }
        $model = Offer::query();
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        if ($request->has('discount_type') && $request->discount_type != null) {
            $model = $model->where('discount_type', $request->discount_type);
        }
        $has_edit_permission = permission_can('edit offer', 'admin');
        $has_delete_permission = permission_can('delete offer', 'admin');

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->editColumn('from', function ($q) {
                return currency($q->from);
            })
            ->editColumn('to', function ($q) {
                return currency($q->to);
            })
            ->addColumn('actions', function ($q) use($has_edit_permission,$has_delete_permission ) {
                $actions = '';

                if ($has_edit_permission) {
                    $actions .= $this->edit_button(route('backend.offers.edit', ['offer' => $q->id]));
                }
                if ($has_delete_permission) {
                    $actions .= $this->delete_button(route('backend.offers.destroy', $q->id), $q->name);
                }
                return $actions;
            })
            ->editColumn('discount_type', function ($q) {
                $badge_type = $q->discount_type == 'Amount' ? 'badge-light-primary' : 'badge-light-warning';
                return '<span class="badge badge-lg ' . $badge_type . ' fw-bold "  >' . $q->discount_type . '</span>';
            })
            ->editColumn('type', function ($q) {
                $badge_type = $q->type == 'Product' ? 'badge-light-primary' : 'badge-light-warning';
                return '<span class="badge badge-lg ' . $badge_type . ' fw-bold "  >' . $q->type . '</span>';
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status offer', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'type', 'discount_type'])
            ->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        if (!permission_can('create offer', 'admin')) {
            return abort(403);
        }

        $discount_types = ['Amount', 'Percentage'];
        $types = ['Order', 'Product'];
        $products = Product::where('status', 1)->get();

        return view('backend.offer.create', compact('discount_types', 'products', 'types'));
    }

    public function store(OfferRequest $request)
    {
        if ($request->type == 'Order') {
            $products_ids = [];
            $minimum_shopping = 1;
        } elseif ($request->type == 'Product') {
            $products_ids = $request->products_ids;
            $minimum_shopping = '';
        }
        $offer = Offer::create($request->all(), [
            'minimum_shopping' => $minimum_shopping,
            'products_ids' => $products_ids,
        ]);

        return redirect()->route('backend.offers.index')->with(['success' => trans('backend.global.success_message.created_successfully')]);
    }
    #endregion

    #region edit

    public function edit($offer)
    {
        if (!permission_can('edit offer', 'admin')) {
            return abort(403);
        }

        $offer = Offer::findOrFail($offer);
        $discount_types = ['Amount', 'Percentage'];
        $types = ['Order', 'Product'];
        $products = Product::where('status', 1)->get();

        return view('backend.offer.edit', compact('discount_types', 'products', 'types', 'offer'));
    }

    public function update(OfferRequest $request, $offer)
    {
        $offer = Offer::findOrFail($offer);

        if ($request->type == 'Order') {
            $products_ids = [];
            $minimum_shopping = 1;
        } elseif ($request->type == 'Product') {
            $products_ids = $request->products_ids;
            $minimum_shopping = '';
        }
        $offer->update($request->all(), [
            'minimum_shopping' => $minimum_shopping,
            'products_ids' => $products_ids,
        ]);

        return redirect()->route('backend.offers.index')->with(['success' => trans('backend.global.success_message.updated_successfully')]);

    }

    #endregion

    #region check values

    public function check_values(Request $request)
    {
        if(!empty($request->value) && false){


        $values = Offer::query()->where('from', '<=', $request->value)->where('to', '>=', $request->value)->first();

        if ($values == null) {
            return response()->data(['message' => trans('backend.product.check_slug.you_can_use_this_slug')]);
        }
        return response()->error(trans('backend.offer.the_values_can_not_be_intersected'), []);
        }

    }
    #endregion

    #region delete
    public function destroy($id)
    {
        if (!permission_can('delete offer', 'admin')) {
            return abort(403);
        }

        if (Offer::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete offer', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Offer::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status offer', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $offer = Offer::find($id);
        if ($offer->status == 1) {
            $offer->status = 0;
        } else {
            $offer->status = 1;
        }
        if ($offer->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion

}
