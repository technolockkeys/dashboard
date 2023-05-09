<?php

namespace App\Http\Controllers\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Cms\Status\CreateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Status;
use App\Models\User;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index()
    {
        if (!permission_can('show statuses', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';

        $datatable_route = route('backend.cms.statuses.datatable');
        $delete_all_route = route('backend.cms.statuses.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = 'placeholder';
        $datatable_columns['id'] = 'id';
        $datatable_columns['type'] = 'type';
        $datatable_columns['image'] = 'image';
        $datatable_columns['order'] = 'order';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $switch_route = route('backend.cms.statuses.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $create_button = $this->create_button(route('backend.cms.statuses.create'), trans('backend.status.create_new_status'));

        return view('backend.cms.status.index', compact('datatable_script', 'switch_script', 'create_button'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show statuses', 'admin')) {
            return abort(403);
        }
        $model = Status::query();

        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        $default_images = media_file(get_setting('default_images'));

        $has_edit_permission = permission_can('edit status', 'admin');
        $has_delete_permission = permission_can('delete status', 'admin');
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) use($has_edit_permission,$has_delete_permission){
                $actions = '';
                if ($has_edit_permission) {
                    $actions .= $this->edit_button(route('backend.cms.statuses.edit', ['status' => $q->id]), $q->name);
                }
                if ($has_delete_permission) {
                    $actions .= $this->delete_button(route('backend.cms.statuses.destroy', ['status' => $q->id]), $q->name);
                }

                return $actions;
            })
            ->editColumn('image', function ($q) use ($default_images) {
                return '<img width="75px" onerror="this.src=' . "'" . $default_images . "'" . '" src="' . media_file($q->getTranslation('image', app()->getLocale())) .'">';


            })
            ->editColumn('type', function ($q) {

                if ($q->type == 'link') {
                    $badge_type = 'badge-light-primary';
                } elseif ($q->type == 'image') {
                    $badge_type = 'badge-light-warning';
                } else {
                    $badge_type = 'badge-light-success';
                }
                return '<span class="badge badge-lg ' . $badge_type . ' fw-bold "  >' . $q->getTranslation('type', app()->getLocale()) . '</span>';

            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status status', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'image', 'type'])
            ->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        if (!permission_can('create status', 'admin')) {
            abort(403);
        }

        $order = Status::withTrashed()->max('order') + 1;
        $types = ['link', 'video', 'image'];
        return view('backend.cms.status.create', compact('types', 'order'));
    }

    public function store(CreateRequest $request)
    {

        $status = new Status();
        $image = [];
        $value = [];
        $type = $request->type;
        foreach (get_languages() as $item) {
            $image[$item->code] = $request->get('image_' . $item->code);
            if ($type == 'link') {
                $value[$item->code] = $request->get('link_' . $item->code);
            } elseif ($type == 'image') {
                $value[$item->code] = $request->get('media_data_' . $item->code);
            } else {
                $value[$item->code] = $request->get('video_' . $item->code);
            }
        }
        $status->type = $type;
        $status->image = $image;
        $status->value = $value;
        $status->order = $request->order;
        $status->status = $request->has('status') ? 1 : 0;

        $status->save();

        return redirect()->route('backend.cms.statuses.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }

    #endregion

    #region edit
    public function edit($id)
    {
        if (!permission_can('edit status', 'admin')) {
            abort(403);
        }
        $status = Status::findOrFail($id);
        $types = ['link', 'video', 'image'];

        return view('backend.cms.status.edit', compact('status', 'types'));
    }

    public function update(CreateRequest $request, $id)
    {
        $status = Status::findOrFail($id);
        $image = [];
        $value = [];
        $type = $request->type;
        foreach (get_languages() as $item) {
            //TODO: update or store depends on the type not on the request...
            $image[$item->code] = $request->get('image_' . $item->code);
            if ($type == 'link') {
                $value[$item->code] = $request->get('link_' . $item->code);
            } elseif ($type == 'image') {
                $value[$item->code] = $request->get('media_data_' . $item->code);
            } else {
                $value[$item->code] = $request->get('video_' . $item->code);
            }
        }
        $status->type = $request->type;
        $status->image = $image;
        $status->value = $value;
        $status->order = $request->order;
        $status->status = $request->has('status') ? 1 : 0;

        $status->save();

        return redirect()->route('backend.cms.statuses.index')->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status status', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $status = Status::findOrFail($id);
        if ($status->status == 1) {
            $status->status = 0;
        } else {
            $status->status = 1;
        }
        if ($status->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #endregion

    public function destroy($id)
    {
        if (Status::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));
    }

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete status', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Status::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
}
