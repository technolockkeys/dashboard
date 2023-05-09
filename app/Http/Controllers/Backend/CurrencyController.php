<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Currency\CreateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Attribute;
use App\Models\Currency;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\SerializeDateTrait;
use Cache;
use http\Env\Response;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use ButtonTrait;
    use DatatableTrait;

    #region index
    function index()
    {
        if (!permission_can('show currencies', 'admin')) {
            return abort(403);
        }
        $filters = [];
        $filters[] = 'status';
        $datatable_route = route('backend.currencies.datatable');
        $delete_all_route = route('backend.currencies.delete-selected');

        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['code'] = 'code';
        $datatable_columns['value'] = 'value';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['is_default'] = 'is_default';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters,);
        $switch_script = null;
        $switch_route = route('backend.currencies.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $switch_is_default = $this->switch_is_default_script(route('backend.currencies.change-default'), 'default');

        $create_button = '';
        if (permission_can('create currency', 'admin')) {
            $create_button = $this->create_button(route('backend.currencies.create'), trans('backend.currency.create_new_currency'));
        }
        return view('backend.currency.index', compact('datatable_script', 'switch_script', 'create_button','switch_is_default'));

    }

    function datatable(Request $request)
    {
        if (!permission_can('show currencies', 'admin')) {
            return abort(403);
        }

        $model = Currency::query();

        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return "";
            })
            ->editColumn('name', function ($q) {
                return $q->name;
            })
            ->editColumn('status', function ($q) {
                $bool = (!permission_can('change status currency', 'admin') || $q->is_default);
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->editColumn('is_default', function ($q) {
                $bool = (!$q->status) || ($q->is_default);
                return $this->status_switch($q->id, $q->is_default, 'default', $bool);

            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit currency', 'admin') && !$q->is_default) {
                    $actions .= $this->edit_button(route('backend.currencies.edit_cur', $q->id));
                }
                if (permission_can('delete currency', 'admin') && !$q->is_default) {
                    $actions .= $this->delete_button(route('backend.currencies.destroy_cur', $q->id), $q->name);
                }
                return $actions;
            })
            ->rawColumns(['actions', 'status','is_default'])->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        return view('backend.currency.create');
    }

    public function store(CreateRequest $request)
    {

        $name = [];
        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
        }

        $currency = Currency::create($request->all(), ['status' => $request->status ? 1 : 0, 'name' => $name]);

        return redirect()->route('backend.currencies.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region edit
    public function edit($id = 1)
    {
        $currency = Currency::findOrFail($id);

        return view('backend.currency.edit', compact('currency'));
    }

    public function update(CreateRequest $request, $id)
    {

        $currency = Currency::findOrFail($id);
        $name = [];
        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
        }

        $currency->update($request->except('status'));
        $currency->update(['status' => ($request->status == 1 ? 1 : 0),'name' => $name]);

        return redirect()->route('backend.currencies.index')->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    #endregion

    #region delete
    public function destroy($id)
    {
        if (!permission_can('delete currency', 'admin')) {
            return abort(403);
        }
        if (Currency::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));
    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete currency', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Currency::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status currency', 'admin')) {
            return abort(403);
        }

        $id = $request->id;
        $currency = Currency::find($id);
        if ($currency->is_default) {
            return response()->error(trans('backend.global.error_message.cant_updated'));

        }
        if ($currency->status == 1) {
            $currency->status = 0;
        } else {
            $currency->status = 1;
        }
        if ($currency->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

    #region default currency
    #region change default language
    function change_default(ChangeStatusRequest $request)
    {
        $id = $request->id;
        $language = Currency::find($id);
        if ($language->is_default == 0) {
            Currency::query()->update(['is_default' => 0]);
            $language->is_default = 1;
            Cache::forget('currencies');
        } else {
            return response()->error(trans('backend.language.can_not_turn_off_default_language'));
        }

        if ($language->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
    }
    function switch_is_default_script($url, $class)
    {
        return view('backend.language.default_language.switch_is_default', compact('url', 'class'))->render();
    }

    #endregion
}
