<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Attribute\SubAttrubte\CreateRequest;
use App\Http\Requests\Backend\Attribute\SubAttrubte\EditRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\SubAttribute;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class SubAttributeController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    public function index($attribute_id){

        if (!permission_can('show sub attributes', 'admin')) {
            return abort(403);
        }
        $datatable_route = route('backend.attributes.sub-attribute.datatable', $attribute_id);
        $delete_all_route = route('backend.attributes.sub-attributes.delete-selected');

        $attribute   = Attribute::find($attribute_id);
        $model = $attribute->sub_attributes();

        $filters[] = 'status';

        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['value'] = 'value';
        $datatable_columns['image'] = 'image';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns,$delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.attributes.sub-attributes.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        if (permission_can('create sub attribute', 'admin')) {
            $create_button = $this->create_button(route('backend.attributes.sub-attributes.create', $attribute_id), trans('backend.attribute.add_new_sub_attribute'));
        }
        $attribute   = Attribute::find($attribute_id);
        $model = $attribute->sub_attributes();

        return view('backend.attribute.sub-attribute.index', compact('datatable_script', 'switch_script', 'create_button', 'attribute'));

    }

    function datatable(Request $request, $attribute_id)
    {
        if (!permission_can('show sub attributes', 'admin')) {
            return abort(403);
        }
        $attribute   = Attribute::find($attribute_id);
        $model = $attribute->sub_attributes();
        if ($request->has('status') && $request->status !=-1) {
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
                $bool = !permission_can('change status sub attribute', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->editColumn('value', function ($q) {
                return $q->value;
            })
            ->editColumn('image', function ($q) {
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='". media_file($q->image) ."'> </div>";
                return $html;

            })
            ->addColumn('actions', function ($q) use ($attribute_id) {
                $actions = '';
                if (permission_can('edit sub attribute', 'admin')) {
                    $actions .= $this->edit_button(route('backend.attributes.sub-attributes.edit', ['attribute_id' => $attribute_id,
                        'id' => $q->id]));
                }
                if (permission_can('delete sub attribute', 'admin')) {
                    $actions .= $this->delete_button(route('backend.attributes.sub-attributes.destroy', ['sub_attribute' => $q->id]), $q->name);
                }

                return $actions;
            })
            ->rawColumns(['actions', 'status', 'value','image'])->toJson();
    }
    #endregion

    public function create($attribute_id)
    {
        $attribute = Attribute::find($attribute_id);
        return view('backend.attribute.sub-attribute.create', compact('attribute'));

    }

    public function store(CreateRequest $request, $attribute_id)
    {
        $attribute = Attribute::find($attribute_id);
        $sub_attribute = new SubAttribute();
        $value = [];
        foreach (get_languages() as $item) {
            $value[$item->code] = $request->get('value_' . $item->code);
            $description[$item->code] = $request->get('description_' . $item->code);
        }
        $sub_attribute->value = $value;
//        $sub_attribute->slug =  convertToKebabCase($sub_attribute->getTranslation('value', 'en'));
        $slug = convertToKebabCase($sub_attribute->getTranslation('value', 'en'));
        $sub_attribute->slug = check_slug(SubAttribute::query(), $slug);
        $sub_attribute->image = $request->image;
        $sub_attribute->status = $request->has('status') ? 1 : 0;
        $attribute->sub_attributes()->save($sub_attribute);

        return redirect()->route('backend.attributes.sub-attributes.index', $attribute_id)->with('success', trans('backend.global.success_message.created_successfully'));
    }

    public function edit($attribute_id,$id)
    {
        $sub_attribute = SubAttribute::findOrFail($id);
        $attribute = Attribute::findOrFail($attribute_id);
        return view('backend.attribute.sub-attribute.edit', compact( 'attribute','sub_attribute'));

    }

    public function update(EditRequest $request, $id)
    {
        $sub_attribute = SubAttribute::findOrFail($id);
        $value = [];
        foreach (get_languages() as $item) {
            $value[$item->code] = $request->get('value_' . $item->code);
            $description[$item->code] = $request->get('description_' . $item->code);
        }
        $sub_attribute->value = $value;
        $sub_attribute->image = $request->image;
        $sub_attribute->status = $request->has('status') ? 1 : 0;
        $sub_attribute->save();

        return redirect()->route('backend.attributes.sub-attributes.edit' ,['attribute_id' => $sub_attribute->attribute->id,
            'id' => $sub_attribute->id])->with('success', trans('backend.global.success_message.updated_successfully'));
    }


    #region delete
    public function destroy($id)
    {
        if(!permission_can('delete sub attribute', 'admin')){
            return abort(403);
        }
        if (SubAttribute::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete sub attribute', 'admin')){
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            SubAttribute::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status sub attribute', 'admin')) {
            return abort(403);
        }
        $id = $request->id;

        $sub_attributes = SubAttribute::find($id);
        if ($sub_attributes->status == 1) {
            $sub_attributes->status = 0;
        } else {
            $sub_attributes->status = 1;
        }
        if ($sub_attributes->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

}
