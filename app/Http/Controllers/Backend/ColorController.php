<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Http\Requests\Backend\Color\CreateRequest;
use App\Http\Requests\Backend\Color\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index()
    {
        if (!permission_can('show colors', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';

        $datatable_route = route('backend.colors.datatable');
        $delete_all_route = route('backend.colors.delete-selected');

        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['code'] = 'code';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.colors.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = $this->create_button(route('backend.colors.create'), trans('backend.color.create_new_color'));
        return view('backend.color.index', compact('datatable_script', 'switch_script', 'create_button'));
    }

    public function datatable(Request $request)
    {

        if (!permission_can('show colors', 'admin')) {
            return abort(403);
        }
        $model = Color::query();

        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return "";
            })
            ->addColumn('actions', function ($q) {
                $actions = '';

                if (permission_can('edit color', 'admin')) {
                    $actions .= $this->edit_button(route('backend.colors.edit', ['color' => $q->id]));
                }
                if (permission_can('delete color', 'admin')) {
                    $actions .= $this->delete_button(route('backend.colors.destroy', ['color' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->editColumn('name', function ($q) {
                return $q->name;
            })
            ->editColumn('code', function ($q) {
                $html = '<span  style="background:' . $q->code . '" class="badge badge-white  "> <span class="text-light">' . $q->code . '</span> / <span>' . $q->code . '</span></span>';
                return $html;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status color', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'code', 'roles', 'placeholder'])
            ->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        if (!permission_can('create color', 'admin')) {
            return abort(403);
        }
        return view('backend.color.create');
    }

    public function store(CreateRequest $request)
    {
        $color = new Color();
        $name = [];
        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
        }
        $color->name = $name;
        $slug = convertToKebabCase($request->get('name_en'));
        $color->slug = check_slug(Color::query(), $slug);
        $color->code = $request->code;
        $color->status = $request->has('status') ? 1 : 0;;

        $color->save();
        return redirect()->route('backend.colors.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region edit
    public function edit($id)
    {

        if (!permission_can('edit color', 'admin')) {
            return abort(403);
        }
        $color = Color::find($id);

        return view('backend.color.edit', compact('color'));

    }

    public function update(UpdateRequest $request, $id)
    {

        $color = Color::findOrFail($id);
        $name = [];
        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
        }
        $color->name = $name;
        $color->code = $request->code;
        $color->status = $request->has('status') ? 1 : 0;;
        $color->save();

        return redirect()->route('backend.colors.index')->with('success', trans('backend.global.success_message.updated_successfully'));

    }

    #endregion

    #region delete
    public function destroy($id)
    {
        if (!permission_can('delete color', 'admin')) {
            return abort(403);
        }

        if (Color::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete color', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Color::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status color', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $color = Color::find($id);
        if ($color->status == 1) {
            $color->status = 0;
        } else {
            $color->status = 1;
        }
        if ($color->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion
}
