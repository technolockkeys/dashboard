<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Brand\Year\CreateRequest;
use App\Http\Requests\Backend\Brand\Year\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use function abort;
use function datatables;
use function permission_can;
use function redirect;
use function response;
use function route;
use function trans;
use function view;

class BrandModelYearController extends Controller
{

    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index($model_id)
    {
        if (!permission_can('show years', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';

        $datatable_route = route('backend.brands.models.years.datatable', $model_id);
        $delete_all_route = route('backend.brands.models.years.delete-selected');
        $model   = BrandModel::findOrFail($model_id);
        $years = $model->years();

        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['year'] = 'year';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.brands.models.years.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        if (permission_can('create year', 'admin')) {
            $create_button = $this->create_button(route('backend.brands.models.years.create', $model_id), trans('backend.brand.create_year'));
        }

        return view('backend.brand.year.index', compact('datatable_script', 'switch_script', 'create_button', 'model'));
    }

    function datatable(Request $request, $model_id)
    {
        if (!permission_can('show years', 'admin')) {
            return abort(403);
        }
        $brand = BrandModel::findOrFail($model_id);
        $models = $brand->years();
        if ($request->has('status') && $request->status !=-1) {
            $models = $models->where('status', $request->status);
        }
        return datatables()->make($models)
            ->addColumn('placeholder', function ($q){
                return '';
            })
            ->editColumn('status', function ($q) {
                return $this->status_switch($q->id, $q->status, 'status' ,!permission_can('change status year','admin'));
            })
            ->addColumn('actions', function ($q) use ($model_id) {
                $actions = '';
                if (permission_can('delete model', 'admin')) {
                    $actions .= $this->delete_button(route('backend.brands.models.years.destroy', ['year' => $q->id]), $q->name);
                }
                if (permission_can('edit model', 'admin')) {
                    $actions .= $this->edit_button(route('backend.brands.models.years.edit', ['year' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->rawColumns(['actions', 'status'])->toJson();
    }
    #endregion

    #region create

    public function create( $model_id)
    {

        $model = BrandModel::findOrFail($model_id);
        return view('backend.brand.year.create', compact('model'));
    }

    public function store(CreateRequest $request, $model_id){


        $year = BrandModelYear::create([
            'brand_model_id' => $model_id,
            'year' => $request->year,
            'status' => $request->has('status') ? 1 : 0,

        ]);

        return redirect()->route('backend.brands.models.years.create', $model_id)->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    public function edit($id)
    {
        $year = BrandModelYear::findOrFail($id);
        $model = BrandModel::findOrFail($year->brand_model_id);
        return view('backend.brand.year.edit', compact('model', 'year'));

    }

    public function update(UpdateRequest $request, $id)
    {

        $year = BrandModelYear::findOrFail($id);

        $year->update([
            'year' => $request->year,
            'status' => $request->has('status') ? 1 : 0,
        ]);
        return redirect()->route('backend.brands.models.years.index', $year->brand_model_id)->with('success', trans('backend.global.success_message.created_successfully'));
    }

    function destroy($id)
    {
        if(!permission_can('delete year','admin'))
        {
            abort(403);
        }
        if(BrandModelYear::destroy($id))
        {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);

        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }

    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete year', 'admin')){
            abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            BrandModelYear::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status year', 'admin')) {
            return abort(403);
        }
        $id = $request->id;

        $year = BrandModelYear::find($id);
        if ($year->status == 1) {
            $year->status = 0;
        } else {
            $year->status = 1;
        }
        if ($year->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

}
