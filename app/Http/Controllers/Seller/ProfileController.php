<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Profile\UpdateRequest;
use App\Models\Admin;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function view;

class ProfileController extends Controller
{
    public function index()
    {
        $seller_id = Auth::guard('seller')->id();
        $seller = Seller::findOrFail($seller_id);
        return view('seller.profile.index', compact('seller'));
    }

    public function update(UpdateRequest $request)
    {
        $seller_id = Auth::guard('seller')->id();
        $seller = Seller::findOrFail($seller_id);

        if($request->avatar_remove == true)
        {
            $seller->avatar = null;
        }

        if (!empty($request->file('avatar'))){
            $image_data = $seller->StoreAvatarImage('avatar', $seller_id, 'seller');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/'.$encoded_data->data->path . $encoded_data->data->title;
            $seller->avatar = $avatar_link;
        }

        if (!empty($request->password)){
            $seller->password = Hash::make($request->password);
        }

        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->whatsapp_number = $request->whatsapp_number;
        $seller->phone = $request->phone;
        $seller->skype = $request->skype;
        $seller->facebook = $request->facebook;
        if (!empty($request->password)){
            $seller->password = Hash::make($request->password);
        }
        $seller->save();

        return redirect()->route('seller.profile')->with('success', trans('backend.global.success_message.updated_successfully'));
    }
}
