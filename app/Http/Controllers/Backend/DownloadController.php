<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Download\CreateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Download;
use App\Models\DownloadAttribute;
use App\Models\Review;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    #region index
    use DatatableTrait;
    use ButtonTrait;

    #region index
    function index()
    {
        if (!permission_can('show downloads', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $datatable_route = route('backend.downloads.datatable');
        $delete_all_route = permission_can('delete download', 'admin') ? route('backend.downloads.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['title'] = 'title';
        $datatable_columns['image'] = 'image';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $switch_script = null;
        $switch_route = route('backend.downloads.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $create_button = '';
        if (permission_can('create download', 'admin')) {
            $create_button = $this->create_button(route('backend.downloads.create'), trans('backend.download.create_new_download'));
        }
        return view('backend.download.index', compact('datatable_script', 'switch_script', 'create_button'));

    }

    function datatable(Request $request)
    {
        if (!permission_can('show downloads', 'admin')) {
            return abort(403);
        }
        $model = Download::query();
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        $default_images = media_file(get_setting('default_images'));

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->editColumn('id', function ($q) {
                return $q->id;
            })
            ->editColumn('title', function ($q) {
                return $q->title;
            })
            ->editColumn('image', function ($q) use($default_images) {
                return '<img width="75px" onerror="this.src='."'".$default_images."'".'" src="' . media_file($q->image) . '">';

            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit download', 'admin')) {
                    $actions .= $this->edit_button(route('backend.downloads.edit', ['download' => $q->id]));
                }
                if (permission_can('delete download', 'admin')) {
                    $actions .= $this->delete_button(route('backend.downloads.destroy', ['download' => $q->id]), $q->name);
                }

                return $actions;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status download', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'name', 'image'])->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        $types = DownloadAttribute::types();
        return view('backend.download.create', compact('types'));
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

        #region videos
        $videos = [];
        if ($request->has('video_url') && !empty($request->video_url)) {
            foreach ($request->video_url as $key => $item) {
                $videos[] = ['provider' => "youtube", 'link' => $request->video_url[$key]];
            }
        }
        #endregion

        $download = Download::create([
            'slug' => $request->slug,
            'title' => $title,
            'description' => $description,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'image' => $request->image,
            'internal_image' => $request->internal_image,
            'screen_shot' => $request->screen_shot,
            'gallery' => $request->gallery,
            'video' => json_encode($videos),
        ]);

        if ($request->types && !empty($request->types)) {
            foreach ($request->types as $key => $type) {
                DownloadAttribute::create([
                    'type' => $type,
                    'name' => $request->name[$key],
                    'link' => $request->link[$key],
                    'download_id' => $download->id,
                ]);
            }
        }
        return redirect()->route('backend.downloads.create')->with('success', __('backend.global.success_message.created_successfully'));

    }

    #endregion

    #region check on slug
    function check_slug(Request $request)
    {
        $slug = $request->slug;
        $check_ajax = Download::query()->where('slug', $slug);
//        dd($request->id);
        if ($request->has('id')) {
            $check_ajax = $check_ajax->where('id', $request->id);
            return response()->data(['message' => trans('backend.product.check_slug.you_can_use_this_slug')]);

        }
        if ($check_ajax->count() == 0) {
            return response()->data(['message' => trans('backend.product.check_slug.you_can_use_this_slug')]);
        }
        return response()->error(trans('backend.product.check_slug.you_can_not_use_this_slug'), []);


    }
    #endregion

    #region edit
    public function edit($id)
    {
        $download = Download::findOrFail($id);
        $types = DownloadAttribute::types();
        $attributes = $download->attributes;
//        dd($attributes);
        return view('backend.download.edit', compact('download', 'types', 'attributes'));
    }

    public function update(CreateRequest $request, $id)
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

        #region videos
        $videos = [];
        if ($request->has('video_url') && !empty($request->video_url)) {
            foreach ($request->video_url as $key => $item) {
                $videos[] = ['provider' => "youtube", 'link' => $request->video_url[$key]];
            }
        }
//            dd(json_encode($videos));
        #endregion
        $download = Download::findOrFail($id);
        $download->update([
            'slug' => $request->slug,
            'title' => $title,
            'description' => $description,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'image' => $request->image,
            'internal_image' => $request->internal_image,
            'screen_shot' => $request->screen_shot,
            'gallery' => $request->gallery,
            'video' => json_encode($videos),
        ]);

        $download->attributes()->delete();
        if ($request->types && !empty($request->types)) {

            foreach ($request->types as $key => $type) {
                DownloadAttribute::firstOrCreate([
                    'type' => $type,
                    'name' => $request->name[$key],
                    'link' => $request->link[$key],
                    'download_id' => $download->id,
                ]);
            }
        }
        return redirect()->route('backend.downloads.index')->with('success', __('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region destroy
    public function destroy($id)
    {
        if (Download::destroy($id)) {
            return response()->data(['message' => __('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete download', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Download::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status download', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $download = Download::find($id);
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
