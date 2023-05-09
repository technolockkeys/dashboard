<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Manufacturer\CreateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Download;
use App\Models\Manufacturer;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{

    #region index
    use DatatableTrait;
    use ButtonTrait;

    #region index
    function index()
    {
        if (!permission_can('show manufacturers', 'admin')) {
            return abort(403);
        }

        $datatable_route = route('backend.manufacturers.datatable');
        $delete_all_route = permission_can('delete manufacturer', 'admin') ? route('backend.manufacturers.delete-selected') : null;
        $filters = [];
        $filters[] = 'status';
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['title'] = 'title';
        $datatable_columns['image'] = 'image';
        $datatable_columns['type'] = 'type';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $switch_script = null;
        $switch_route = route('backend.manufacturers.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $create_button = '';
        if (permission_can('create manufacturer', 'admin')) {
            $create_button = $this->create_button(route('backend.manufacturers.create'), trans('backend.manufacturer.create_new_manufacturer'));
        }
        return view('backend.manufacturer.index', compact('datatable_script', 'switch_script', 'create_button'));

    }

    function datatable(Request $request)
    {
        if (!permission_can('show manufacturers', 'admin')) {
            return abort(403);
        }
        $model = Manufacturer::query();
        if ($request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        $default_images = media_file(get_setting('default_images'));

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->editColumn('title', function ($q) {
                return $q->title;
            })
            ->editColumn('image', function ($q) use ($default_images) {
                return '<img width="75px" onerror="this.src=' . "'" . $default_images . "'" . '" src="' . media_file($q->image) . '">';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit manufacturer', 'admin')) {
                    $actions .= $this->edit_button(route('backend.manufacturers.edit', ['manufacturer' => $q->id]));
                }
                if (permission_can('delete manufacturer', 'admin')) {
                    $actions .= $this->delete_button(route('backend.manufacturers.destroy', ['manufacturer' => $q->id]), $q->name);
                }

                return $actions;
            })
            ->addColumn('type', function ($q) {
                $badges = '';
                if ($q->software == 1) {
                    $badges .= '<span class="ms-2 badge badge-light-primary fw-bold  ">' . trans('backend.manufacturer.software') . '</span>';
                }
                if ($q->token == 1) {
                    $badges .= '<span class="ms-2 badge badge-light-warning fw-bold  ">' . trans('backend.manufacturer.token') . '</span>';
                }
                if ($q->software == 0 && $q->token == 0) {
                    $badges .= '<span class="ms-2 badge badge-light-danger fw-bold  ">' . trans('backend.global.not_found') . '</span>';
                }
                return $badges;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status manufacturer', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'name', 'image', 'type'])->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        return !permission_can('create manufacturer', 'admin') ?
            abort(403) :
            view('backend.manufacturer.create');

    }

    public function store(CreateRequest $request)
    {
        $title = [];
        $description = [];
        $meta_title = [];
        $meta_description = [];
        foreach (get_languages() as $language) {
            $title[$language->code] = $request->get('title_' . $language->code);
            $description[$language->code] = $request->get('description_' . $language->code);
            $meta_title[$language->code] = $request->get('meta_title_' . $language->code);
            $meta_description[$language->code] = $request->get('meta_description_' . $language->code);
        }
        $slug = convertToKebabCase($request->get('title_en'));
        $slug= check_slug(Manufacturer::query(), $slug);
        $manufacturer = Manufacturer::create([
            'title' => $title,
            'slug' => $slug,
            'meta_title' => $meta_title,
            'description' => $description,
            'meta_description' => $meta_description,
            'image' => $request->image,
            'status' => $request->has('status') ? $request->status : 0,
            'software' => $request->has('software') ? $request->software : 0,
            'token' => $request->has('token') ? $request->token : 0,

        ]);
        return redirect()->route('backend.manufacturers.index')->with('success', __('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region edit
    public function edit($id)
    {
        $manufacturer = Manufacturer::findOrFail($id);
        return view('backend.manufacturer.edit', compact('manufacturer'));
    }

    public function update(CreateRequest $request, $id)
    {
        $manufacturer = Manufacturer::findOrFail($id);
        $title = [];
        $description = [];
        $meta_title = [];
        $meta_description = [];
        foreach (get_languages() as $language) {
            $title[$language->code] = $request->get('title_' . $language->code);
            $description[$language->code] = $request->get('description_' . $language->code);
            $meta_title[$language->code] = $request->get('meta_title_' . $language->code);
            $meta_description[$language->code] = $request->get('meta_description_' . $language->code);
        }

        $manufacturer->update([
            'title' => $title,
            'meta_title' => $meta_title,
            'description' => $description,
            'meta_description' => $meta_description,
            'image' => $request->image,
            'status' => $request->has('status') ? $request->status : 0,
            'software' => $request->has('software') ? $request->software : 0,
            'token' => $request->has('token') ? $request->token : 0,
        ]);

        return redirect()->route('backend.manufacturers.edit', [$id])->with('success', __('backend.global.success_message.updated_successfully'));
    }


    #endregion

    #region destroy
    public function destroy($id)
    {
        if (Manufacturer::destroy($id)) {
            return response()->data(['message' => __('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete manufacturer', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Manufacturer::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status manufacturer', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $download = Manufacturer::find($id);
        if ($download->status == 1) {
            $download->status = 0;
        } else {
            $download->status = 1;
        }
        if ($download->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion
}
