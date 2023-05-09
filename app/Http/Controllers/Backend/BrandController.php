<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Brand\CreateRequest;
use App\Http\Requests\Backend\Brand\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Brand;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    function index()
    {
        if (!permission_can('show brands', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $datatable_route = route('backend.brands.datatable');
        $delete_all_route = route('backend.brands.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['make'] = 'make';
        $datatable_columns['image'] = 'image';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.brands.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
            if (permission_can('create brand', 'admin')) {
            $create_button = $this->create_button(route('backend.brands.create'), trans('backend.brand.create_new_brand'));
        }
        return view('backend.brand.index', compact('datatable_script', 'switch_script', 'create_button'));

    }

    function datatable(Request $request)
    {
        if (!permission_can('show brands', 'admin')) {
            return abort(403);
        }
        $model = Brand::query();
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return "";
            })
            ->editColumn('status', function ($q) {
                return $this->status_switch($q->id, $q->status, 'status');
            })
            ->editColumn('make', function ($q) {
                return $q->make;
            })
            ->editColumn('image', function ($q) {
                $html = "<div class='w-40px h-40px text-center overflow-hidden'> <img class='w-100 h-100' style='object-fit: cover' src='" . media_file($q->image) . "'> </div>";
                return $html;
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete brand', 'admin')) {
                    $actions .= $this->delete_button(route('backend.brands.destroy', ['brand' => $q->id]), $q->name);
                }
                if (permission_can('edit brand', 'admin')) {
                    $actions .= $this->edit_button(route('backend.brands.edit', ['brand' => $q->id]));
                }
                if (permission_can('show models', 'admin')) {
                    $actions .= $this->btn(route('backend.brands.models.index', ['brand_id' => $q->id]), trans('backend.brand.models'), 'fa fa-cong', 'btn-info', ['attribute' => $q->id]);
                }

                return $actions;
            })
            ->rawColumns(['actions', 'status', 'image', 'placeholder'])->toJson();
    }
    #endregion

    #region create
    function create()
    {
        if (!permission_can('create brand', 'admin')) {
            return abort(403);
        }
        return view('backend.brand.create');
    }

    function store(CreateRequest $request)
    {
        $brand = new Brand();
        $description = [];
        $make = [];
        foreach (get_languages() as $item) {
            $description[$item->code] = $request->get('description_' . $item->code);
            $make[$item->code] = $request->get('make_' . $item->code);
        }
        $slug = convertToKebabCase($request->get('make_en'));
        $brand->slug = check_slug(Brand::query(), $slug);
        $brand->make = $make;
        $brand->description = $description;
        $brand->image = $request->image;
        $brand->pin_code_price = $request->pin_code_price;
        $brand->status = $request->has('status') ? 1 : 0;

        $brand->save();

        return redirect()->route('backend.brands.create')->with('success', trans('backend.global.success_message.created_successfully'));

    }

    #endregion

    #region edit
    public function edit($id)
    {
        if (!permission_can('edit brand', 'admin')) {
            return abort(403);
        }

        $brand = Brand::findOrFail($id);
        return view('backend.brand.edit', compact('brand'));

    }

    public function update(UpdateRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $description = [];
        $make = [];
        foreach (get_languages() as $item) {
            $description[$item->code] = $request->get('description_' . $item->code);
            $make[$item->code] = $request->get('make_' . $item->code);
        }
        $brand->update([
            'description' => $description,
            'make' => $make,
            'image' => $request->image,
            'pin_code_price' => $request->pin_code_price,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('backend.brands.index')->with('success', trans('backend.global.success_message.updated_successfully'));

    }
    #endregion

    #region delete
    public function destroy($id)
    {
        if (Brand::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete brand', 'admin')) {
            abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Brand::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status brand', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $brand = Brand::findOrFail($id);
        if ($brand->status == 1) {
            $brand->status = 0;
        } else {
            $brand->status = 1;
        }
        if ($brand->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

    #region load models
    public function load_models(Request $request)
    {
        if ($request->type == "year") {
            $models = Brand::where('make', request()->get('make'))
                ->where('model', request()->get('model'))
                ->groupBy('year')
                ->pluck('year');

        }
        if ($request->type == 'model') {
            $models = Brand::where('make', request()->get('make'))
                ->groupBy('model')
                ->pluck('model');
        }

        return response()->data(['models' => $models]);
    }

    #endregion
}
