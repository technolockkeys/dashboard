<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Language\CreateRequest;
use App\Http\Requests\Backend\Language\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Color;
use App\Models\Country;
use App\Models\Language;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\MediaUploadingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LanguageController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use MediaUploadingTrait;

    #region index
    public function index()
    {
        if (!permission_can('show languages', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';

        $datatable_route = route('backend.languages.datatable');
        $delete_all_route = permission_can('delete language', 'admin') ? route('backend.languages.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['language'] = 'language';
        $datatable_columns['code'] = 'code';
        $datatable_columns['display_type'] = 'display_type';
        $datatable_columns['is_default'] = 'is_default';
        $datatable_columns['flag'] = 'flag';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.languages.change.status');
        $switch_class = 'status';
        $switch_is_default = $this->switch_is_default_script(route('backend.languages.change.default'), 'default');
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = $this->create_button(route('backend.languages.create'), trans('backend.language.create_new_language'));
        return view('backend.language.index', compact('datatable_script', 'switch_script', 'create_button', 'switch_is_default'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show languages', 'admin')) {
            return abort(403);
        }
        $model = Language::query();
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        $default_images = media_file(get_setting('default_images'));

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';

                if (permission_can('edit language', 'admin')) {
                    $actions .= $this->edit_button(route('backend.languages.edit', ['language' => $q->id]));
                }
                if (permission_can('delete language', 'admin') && !($q->is_default)) {
                    $actions .= $this->delete_button(route('backend.languages.destroy', ['language' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->editColumn('flag', function ($q) use($default_images
            ){
                return '<img width="75px" onerror="this.src=' . "'" . $default_images . "'" . '" src="' . media_file($q->flag) . '">';
            })
            ->editColumn('is_default', function ($q) {
                $bool = (!$q->status) || ($q->is_default);
                return $this->status_switch($q->id, $q->is_default, 'default', $bool);

            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status language', 'admin') || $q->is_default == 1;
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'is_default', 'flag'])
            ->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        if (!permission_can('create language', 'admin')) {
            return abort(403);
        }
        $display_types = ['RTL', 'LTR'];
        $codes = Country::query()->groupBy('iso2')->pluck('iso2');
        return view('backend.language.create', compact('display_types', 'codes'));
    }

    public function store(CreateRequest $request)
    {
        $language = new Language();
        $language->language = $request->language;
        $language->code = $request->code;
        $language->display_type = $request->display_type;
        $language->flag = $request->flag;

        $language->save();
        Cache::forget('language');
        return redirect()->route('backend.languages.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region edit
    public function edit($id)
    {

        if (!permission_can('edit language', 'admin')) {
            return abort(403);
        }
        $codes = Country::query()->groupBy('iso2')->pluck('iso2');

        $language = Language::find($id);
        $display_types = ['RTL', 'LTR'];
        Cache::forget('language');
        return view('backend.language.edit', compact('language', 'display_types', 'codes'));

    }

    public function update(UpdateRequest $request, $id)
    {

        $language = Language::findOrFail($id);
        $language->language = $request->language;
        $language->code = $request->code;
        $language->flag = $request->flag;
        $language->display_type = $request->display_type;

        $language->save();
        Cache::forget('language');
        return redirect()->route('backend.languages.index')->with('success', trans('backend.global.success_message.updated_successfully'));

    }

    #endregion

    #region delete
    public function destroy($id)
    {
        if (!permission_can('delete language', 'admin')) {
            return abort(403);
        }
        Cache::forget('language');
        if (Language::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete language', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Language::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status language', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $language = Language::find($id);
        if ($language->status == 1) {
            $language->status = 0;
        } else {
            $language->status = 1;
        }
        Cache::forget('language');
        if ($language->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion

    #region change default language
    function change_default(ChangeStatusRequest $request)
    {
        $id = $request->id;
        $language = Language::find($id);
        if ($language->is_default == 0) {
            Language::all()->each(function ($query) {
                $query->is_default = 0;
                $query->save();
            });
            $language->is_default = 1;
            Cache::forget('language');
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
