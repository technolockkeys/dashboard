<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Brand\Model\CreateRequest;
use App\Http\Requests\Backend\Brand\Model\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Brand;
use App\Models\BrandModel;
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

class BrandModelController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;


    #region index
    public function index($brand_id)
    {
        $models = BrandModel::where('brand_id', $brand_id)->get();
        if (!permission_can('show models', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $datatable_route = route('backend.brands.models.datatable', $brand_id);
        $delete_all_route = route('backend.brands.models.delete-selected');
        $brand   = Brand::findOrFail($brand_id);
        $model = $brand->models();

        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['model'] = 'model';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns,$delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.brands.models.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        if (permission_can('create model', 'admin')) {
            $create_button = $this->create_button(route('backend.brands.models.create', $brand_id), trans('backend.brand.create_model'));
        }

        return view('backend.brand.model.index', compact('datatable_script', 'switch_script', 'create_button', 'brand'));
    }

    function datatable(Request $request, $brand_id)
    {
//        dd($brand_id);
        if (!permission_can('show models', 'admin')) {
            return abort(403);
        }
        $brand = Brand::findOrFail($brand_id);
        $models = $brand->models();
        if ($request->has('status') && $request->status !=-1) {
            $models = $models->where('status', $request->status);
        }
        return datatables()->make($models)
            ->addColumn('placeholder', function ($q) {
                return "";
            })
            ->editColumn('model', function($q){
                return $q->model;
            })
            ->editColumn('status', function ($q) {
                return $this->status_switch($q->id, $q->status, 'status');
            })
            ->addColumn('actions', function ($q) use ($brand_id) {
                $actions = '';
                if (permission_can('delete model', 'admin')) {
                    $actions .= $this->delete_button(route('backend.brands.models.destroy', ['model' => $q->id]), $q->name);
                }
                if (permission_can('edit model', 'admin')) {
                    $actions .= $this->edit_button(route('backend.brands.models.edit', ['model' => $q->id]), $q->name );
                }
                if (permission_can('show years', 'admin')) {
                    $actions .= $this->btn(route('backend.brands.models.years.index', ['model_id' => $q->id]), trans('backend.brand.years') ,'fa fa-cong' , 'btn-info'  ,['attribute' => $q->id]);
                }


                return $actions;
            })
            ->rawColumns(['actions', 'status'])->toJson();
    }
    #endregion

    #region create

    public function create( $brand_id)
    {
        $brand = Brand::findOrFail($brand_id);
        return view('backend.brand.model.create', compact('brand'));
    }

    public function store(CreateRequest $request, $brand_id){

        $model = [];
        foreach (get_languages() as $language){
            $model[$language->code] = $request->get('model_'.$language->code);
        }
        $slug = convertToKebabCase($request->get('model_en'));
        $slug= check_slug(Brand::query(), $slug);
        $model = BrandModel::create([
            'model'=> $model,
            'brand_id' => $brand_id,
            'slug' => $slug,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('backend.brands.models.create', $brand_id)->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    public function edit($id)
    {
        $model = BrandModel::findOrFail($id);

        return view('backend.brand.model.edit', compact('model'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $model = BrandModel::findOrFail($id);

        $models = [];
        foreach (get_languages() as $language){
            $models[$language->code] = $request->get('model_'.$language->code);
        }
        $model->update([
            'model' => $models,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('backend.brands.models.index', $model->brand_id)->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    function destroy($id)
    {
        if(BrandModel::destroy($id))
        {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);

        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }

    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete model', 'admin')){
            abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            BrandModel::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion


    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status model', 'admin')) {
            return abort(403);
        }
        $id = $request->id;

        $model = BrandModel::findOrFail($id);
        if ($model->status == 1) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }
        if ($model->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

}
