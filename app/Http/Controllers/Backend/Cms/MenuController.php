<?php

namespace App\Http\Controllers\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Cms\Menu\CreateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Menu;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index()
    {
        if (!permission_can('show menus', 'admin')) {
            return abort(403);
        }
        $filters[] = 'type';
        $filters[] = 'status';

        $datatable_route = route('backend.cms.menus.datatable');
        $delete_all_route = route('backend.cms.menus.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['type'] = 'type';
        $datatable_columns['title'] = 'title';
        $datatable_columns['link'] = 'link';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $switch_route = route('backend.cms.menus.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route,$filters);
        $create_button = $this->create_button(route('backend.cms.menus.create'), trans('backend.menu.create_new_menu'));
        $types = Menu::types();
        return view('backend.cms.menu.index', compact('datatable_script', 'switch_script', 'create_button', 'types'));
    }

    public function datatable(Request $request){
        if (!permission_can('show menus', 'admin')) {
            return abort(403);
        }
        $model = Menu::query();

        if ($request->has('status') && $request->status !=-1) {
            $model = $model->where('status', $request->status);
        }

        if ($request->has('type') && $request->type != null) {
            $model = $model->where('type', $request->type);
        }

            $has_delete_permission =permission_can('delete menu', 'admin');
        $has_edit_permission = permission_can('edit menu', 'admin');
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q){
                return '';
            })
            ->addColumn('actions', function ($q) use ($has_delete_permission,$has_edit_permission){
                $actions = '';
                if ($has_delete_permission) {
                    $actions .= $this->delete_button(route('backend.cms.menus.destroy', ['menu' => $q->id]), $q->name);
                }
                if ($has_edit_permission) {
                    $actions .= $this->edit_button(route('backend.cms.menus.edit', ['menu' => $q->id]), $q->name);
                }

                return $actions;
            }) ->editColumn('status', function ($q) {
                $bool = !permission_can('change status menu', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->editColumn('title', function ($q){
                return $q->getTranslation('title', app()->getLocale());
            })
            ->editColumn('type', function ($q){
                return '<span class="badge badge-light-primary badge-lg fw-bold  ">' . trans('backend.menu.'.$q->type ). '</span>';
            })
            ->rawColumns(['actions', 'status','type'])
            ->toJson();
    }

    #region create
    public function create()
    {
        if(!permission_can('create menu', 'admin')){
            return abort(403);
        }
        $types = Menu::types();
        return view('backend.cms.menu.create', compact('types'));
    }

    public function store(CreateRequest $request){

        $title = [];
        foreach (get_languages() as $language){
            $title[$language->code] = $request->get('title_'.$language->code);
        }
        Menu::Create([
            'type' => $request->type,
            'link' => $request->link,
            'title' => $title,
            'icon' => $request->icon,
            'status' => $request->has('status')?1:0
            ]);
        Cache::forget('language');

        return redirect()->route('backend.cms.menus.index')->with('success', trans('backend.global.success_message.created_successfully') );
    }

    #endregion

    #region edit
    public function edit($id)
    {
        if(!permission_can('edit menu', 'admin')){
            return abort(403);
        }
        $menu = Menu::findOrFail($id);
        $types = Menu::types();

        return view('backend.cms.menu.edit', compact('menu','types'));
    }

    public function update(CreateRequest $request, $id){
        $title = [];
        foreach (get_languages() as $language){
            $title[$language->code] = $request->get('title_'.$language->code);
        }
        Menu::findOrFail($id)->Update([
            'type' => $request->type,
            'link' => $request->link,
            'title' => $title,
            'icon' => $request->icon,
            'status' => $request->has('status')?1:0
        ]);
        Cache::forget('language');

        return redirect()->route('backend.cms.menus.index')->with('success', trans('backend.global.success_message.updated_successfully') );
    }
    #endregion

    #region delete

    public function destroy($id)
    {
        if(!permission_can('delete menu','admin')){
            abort(403);
        }
        if(Menu::destroy($id)){
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));
    }
    #endregion
    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete menu', 'admin')){
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Menu::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status menu', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $menu = Menu::findOrFail($id);
        if ($menu->status == 1) {
            $menu->status = 0;
        } else {
            $menu->status = 1;
        }
        Cache::forget('language');

        if ($menu->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #endregion


}
