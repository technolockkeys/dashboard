<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Attribute\CreateRequest;
use App\Http\Requests\Backend\Attribute\EditRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Attribute;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    function index()
    {
        if (!permission_can('show attributes', 'admin')) {
            return abort(403);
        }
        $filters = [];
        $filters[] = 'status';
        $datatable_route = route('backend.attributes.datatable');
        $delete_all_route = route('backend.attributes.delete-selected');

        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['image'] = 'image';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters, );
        $switch_script = null;
        $switch_route = route('backend.attributes.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        if (permission_can('create attribute', 'admin')) {
            $create_button = $this->create_button(route('backend.attributes.create'), trans('backend.attribute.create_new_attribute'));
        }
        return view('backend.attribute.index', compact('datatable_script', 'switch_script', 'create_button'));

    }

    function datatable(Request $request)
    {
        if (!permission_can('show attributes', 'admin')) {
            return abort(403);
        }

        $model = Attribute::query();

        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        if ($request->order[0]['column'] == 0) {
            $model->orderByDesc('id');
        }

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return "";
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status attribute', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->editColumn('name', function ($q) {
                return $q->name;
            })
            ->editColumn('image', function ($q) {
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . media_file($q->getTranslation('image', app()->getLocale())) . "'> </div>";
                return $html;

            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit attribute', 'admin')) {
                    $actions .= $this->edit_button(route('backend.attributes.edit', ['attribute' => $q->id]));
                }
                if (permission_can('delete attribute', 'admin')) {
                    $actions .= $this->delete_button(route('backend.attributes.destroy', ['attribute' => $q->id]), $q->name);
                }
                if (permission_can('show sub attributes', 'admin')) {
                    $actions .= $this->btn(route('backend.attributes.sub-attributes.index', ['attribute_id' => $q->id]), trans('backend.attribute.sub_attributes_page'), 'fa fa-cong', 'btn-info', ['attribute' => $q->id]);
                }
                return $actions;
            })
            ->rawColumns(['actions', 'status', 'name', 'image'])->toJson();
    }
    #endregion

    #region create
    function create()
    {
        if (!permission_can('create attribute', 'admin')) {
            return abort(403);
        }
        return view('backend.attribute.create');
    }

    function store(CreateRequest $request)
    {
        $attribute = new Attribute();
        $name = [];
        $image = [];
        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
            $image[$item->code] = $request->get('image_' . $item->code);
        }
        $attribute->name = $name;
        $slug = convertToKebabCase($request->get('name_en'));
        $attribute->slug = check_slug(Attribute::query(), $slug);
        $attribute->image = $image;
        $attribute->status = $request->has('status') ? 1 : 0;
        $attribute->save();
        return redirect()->route('backend.attributes.index')->with('success', trans('backend.global.success_message.created_successfully'));

    }

    #endregion

    #region edit
    function edit($id)
    {
        if (!permission_can('edit attribute', 'admin')) {
            return abort(403);
        }
        $attribute = Attribute::findOrFail($id);
        return view('backend.attribute.edit', compact('attribute'));
    }

    function update(EditRequest $request, $id)
    {

        $attribute = Attribute::findOrFail($id);
        $name = [];
        $image = [];
        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
            $image[$item->code] = $request->get('image_' . $item->code);
        }
        $attribute->name = $name;
        $attribute->image = $image;
        $attribute->status = $request->has('status') ? 1 : 0;
        $attribute->save();
        return redirect()->route('backend.attributes.edit', $attribute)->with('success', trans('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region delete
    public function destroy($id)
    {
        if (!permission_can('delete attribute', 'admin')) {
            return abort(403);
        }
        if (Attribute::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete attribute', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Attribute::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status attribute', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $admin = Attribute::find($id);
        if ($admin->status == 1) {
            $admin->status = 0;
        } else {
            $admin->status = 1;
        }
        if ($admin->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion
}
