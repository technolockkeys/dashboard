<?php

namespace App\Http\Controllers\Backend\ManagerAccess;

use App\Http\Controllers\Controller;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Http\Requests\Backend\Admins\CreateRequest;
use App\Http\Requests\Backend\Admins\EditRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index page

    public function index()
    {
        if (!permission_can('show admins', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $datatable_route = route('backend.admins.datatable');
        $delete_all_route = permission_can('delete admin', 'admin') ? route('backend.admins.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['roles'] = 'roles';
        $datatable_columns['email'] = 'email';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.admins.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        $roles = Role::all();
//        dd($roles);
        if (permission_can('create admin', 'admin')) {
            $create_button = $this->create_button(route('backend.admins.create'), trans('backend.admin.create_new_admin'));
        }
        return view('backend.management.admin.index', compact('datatable_script', 'switch_script', 'create_button'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show admins', 'admin')) {
            return abort(403);
        }
        $model = Admin::query();
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit admin', 'admin')) {
                    $actions .= $this->edit_button(route('backend.admins.edit', ['admin' => $q->id]));
                }
                if (permission_can('delete admin', 'admin') && $q->id != 1) {
                    $actions .= $this->delete_button(route('backend.admins.destroy', ['admin' => $q->id]), $q->name);
                }
                return $actions;

            })
            ->addColumn('roles', function ($q) {
                $roles = $q->getRoleNames();
                $html = '';
                foreach ($roles as $role) {
                    $html .= '<span class="ms-2 badge badge-light-primary fw-bold  ">' . $role . '</span>';
                }
                return $html;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status admin', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'roles'])
            ->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        if (!permission_can('create admin', 'admin')) {
            return abort(403);
        }
        $roles = Role::all();
        $permissions = Permission::query()->where('guard_name', 'admin')->get();

        return view('backend.management.admin.create', compact('roles'));
    }


    public function store(CreateRequest $request)
    {

        $admin = new Admin();

        #region create admin object
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->status = (!empty($request->status) && $request->status == 1);
        if (!empty($request->file('avatar'))) {

            $avatar = $request->file('avatar');

            $image_data = $admin->StoreAvatarImage('avatar', $admin->id, 'admin');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $admin->avatar = $avatar_link;
        }

        #endregion

        $admin->save();

        $role = Role::find($request->role);

        $admin->syncRoles($role);

        return redirect()->route('backend.admins.create')->with('success', trans('backend.global.success_message.created_successfully'));

    }

    #endregion

    #region edit
    public function edit($id)
    {
        if (!permission_can('edit admin', 'admin')) {
            return abort(403);
        }
        $roles = Role::all();
        $admin = Admin::findOrFail($id);
        return view('backend.management.admin.edit', compact('roles', 'admin'));
    }

    public function update(EditRequest $request, $id)
    {

        $admin = Admin::findOrFail($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->status = (!empty($request->status) && $request->status == 1);
        if (!empty($request->password)) {
            $admin->password = Hash::make($request->password);
        }
        if ($request->avatar_remove == true) {
            $admin->avatar = null;
        }
        if (!empty($request->file('avatar'))) {

            $avatar = $request->file('avatar');

            $image_data = $admin->StoreAvatarImage('avatar', $id, 'admin');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $admin->avatar = $avatar_link;
        }


        $admin->save();

        $role = Role::find($request->role);
        $admin->syncRoles($role);
        return redirect()->route('backend.admins.edit', $admin)->with('success', trans('backend.global.success_message.updated_successfully'));


    }
    #endregion

    #region destroy
    public function destroy($id)
    {
        if (!permission_can('delete admin', 'admin') || $id == 1) {
            return abort(403);
        }
        if (Admin::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete admin', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            if ($id != 1) {
                Admin::destroy($id);
            }
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status admin', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $admin = Admin::find($id);
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
