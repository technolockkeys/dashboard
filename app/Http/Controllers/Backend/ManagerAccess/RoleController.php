<?php

namespace App\Http\Controllers\Backend\ManagerAccess;

use App\Http\Controllers\Controller;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Http\Requests\Backend\Role\CreateRequest;
use App\Http\Requests\Backend\Role\EditRequest;
use App\Http\Requests\Backend\Role\GetPermissionRequest;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index page

    public function index()
    {
        if (!permission_can('show admins', 'admin')) {
            return abort(403);
        }
        $datatable_route = route('backend.roles.datatable');
        $delete_all_route = permission_can('delete role', 'admin') ? route('backend.roles.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['guard_name'] = 'guard_name';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route);
        $create_button = '';
        if (permission_can('create role', 'admin')) {
            $create_button = $this->create_button(route('backend.roles.create'), trans('backend.role.create_new_role'));
        }
        return view('backend.management.role.index', compact('datatable_script', 'create_button'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show admins', 'admin')) {
            return abort(403);
        }
        $roles = Role::query();
        return datatables($roles)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit role', 'admin')) {
                    $actions .= $this->edit_button(route('backend.roles.edit', ['role' => $q->id]));
                }
                if (permission_can('delete role', 'admin') && $q->id != 1) {
                    $actions .= $this->delete_button(route('backend.roles.destroy', ['role' => $q->id]), $q->name);

                }
                return $actions;

            })
            ->editColumn('created_at', function ($q) {
                return date('Y-m-d H:i:s', strtotime($q->created_at));
            })
            ->editColumn('updated_at', function ($q) {
                return $q->updated_at;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    #endregion

    #region create
    public function create()
    {
        if (!permission_can('create role', 'admin')) {
            return abort(403);
        }
        $permissions = Permission::all();
        $guards = Role::query()->groupBy('guard_name')->pluck('guard_name');
        return view('backend.management.role.create', compact('permissions', 'guards'));
    }


    public function store(CreateRequest $request)
    {

        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();
        if ($request->has('permission') && !empty($request->permission)) {
            foreach ($request->permission as $item) {
                $permission = Permission::find($item);
                $role->givePermissionTo($permission->name);
            }
        }

        return redirect()->route('backend.roles.create')->with('success', trans('backend.global.success_message.created_successfully'));
    }

    function getPermission(GetPermissionRequest $request)
    {

        $PermissionsGroup = PermissionGroup::query()->where('guard_name', $request->guard)->get();
        $role = null;
        if ($request->has('role') && !empty($request->role)) {
            $role = Role::find($request->role);
        }
        $view = view('backend.management.role.permission', compact('PermissionsGroup', 'role'))->render();
        return response()->data(['view' => $view]);
    }

    #endregion

    #region update
    public function edit($id)
    {

        if (!permission_can('edit role', 'admin')) {
            return abort(403);
        }
        $role = Role::findOrFail($id);

        $PermissionsGroup = PermissionGroup::query()->where('guard_name', $role->guard_name)->get();
        $guards = Role::query()->groupBy('guard_name')->pluck('guard_name');

        foreach ($PermissionsGroup as $group) {
            $permissions = Permission::query()
                ->where('guard_name', $role->guard_name)
                ->where('group_id', $group->id)
                ->get();
            $group->permission = [];
            foreach ($permissions as $item) {
                $item->active = $role->hasPermissionTo($item->name);

            }
            $group->permission = $permissions;
        }


        return view('backend.management.role.edit', compact('PermissionsGroup', 'role', 'guards'));
    }


    public function update(EditRequest $request, $id)
    {

        $role = Role::findOrFail($id);
        $role->name = $request->name;
//        $role->guard_name = $request->guard_name;
        $role->save();
        if (!empty($request->permission)) {
            $role->syncPermissions($request->permission);
        }
        return redirect()->route('backend.roles.edit', $role)->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    #endregion

    public function destroy($id)
    {

        if (!permission_can('delete role', 'admin') || $id == 1) {
            return abort(403);
        }
        if (Role::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete role', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            if ($id != 1) {

                Role::destroy($id);
            }
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

}
