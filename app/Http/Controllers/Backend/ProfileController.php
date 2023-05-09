<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Profile\UpdateRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function view;

class ProfileController extends Controller
{
    public function index()
    {
        $admin_id = Auth::guard('admin')->id();
        $admin = Admin::findOrFail($admin_id);
        return view('backend.profile.index', compact('admin'));
    }

    public function update(UpdateRequest $request)
    {
        $admin_id = Auth::guard('admin')->id();
        $admin = Admin::findOrFail($admin_id);

        if($request->avatar_remove == true)
        {
            $admin->avatar = null;
        }

        if (!empty($request->file('avatar'))){

            $avatar = $request->file('avatar');

            $image_data = $admin->StoreAvatarImage('avatar', $admin_id, 'admin');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/'.$encoded_data->data->path . $encoded_data->data->title;
            $admin->avatar = $avatar_link;
        }

        if (!empty($request->password)){
            $admin->password = Hash::make($request->password);
        }


        $admin->name = $request->name;
        $admin->email = $request->email;
        if (!empty($request->password)){
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return redirect()->route('backend.profile')->with('success', trans('backend.global.success_message.updated_successfully'));
    }
}
