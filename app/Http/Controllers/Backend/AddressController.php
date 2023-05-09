<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Address\CreateRequest;
use App\Http\Requests\Backend\Address\UpdateRequest;
use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    #region show
    public function index()
    {

    }
    #endregion

    #region show
    public function show()
    {

    }
    #endregion
    #region create
    public function create(Request $request)
    {
        $countries = Country::where('status', 1)->get();
        $user_id = $request->user_id;
        $view = view('backend.user.data.create_address', compact('countries', 'user_id'))->render();
        return response()->data(['view' => $view]);

    }

    public function store(CreateRequest $request)
    {
        $address = new Address();
        $user = User::where('id', $request->user_id)->orWhere('uuid',$request->user_id)->first();
        $address->city = $request->city;
        $address->country_id = $request->country;
        $address->address = $request->address;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->state = $request->state;
        $address->street = $request->street;

        if (!empty($request->is_default)) {
            $user->addresses()->each(function ($q) {
                $q->is_default = 0;
                $q->save();
            });
            $address->is_default = $request->has('is_default') ? 1 : 0;
            $user->country_id = $request->country;
            $user->country_id= $request->country;
            $user->state=  $request->state;
            $user->city=  $request->city;
            $user->address=$request->address;
            $user->street=$request->street;
            $user->postal_code=  $request->postal_code;

            $user->save();
        }

        $user->addresses()->save($address);
//        $address->save();
        return response()->data(['message' => trans('backend.global.success_message.created_successfully')]);

    }
    #endregion

    #region edit
    public function edit($id)
    {
        $address = Address::findOrFail($id);
        $countries = Country::where('status', 1)->get();
        $view = view('backend.user.data.edit_address', compact('address', 'countries'))->render();
        return response()->data(['view' => $view]);
    }

    public function update(UpdateRequest $request, $id)
    {
        $address = Address::findOrFail($id);
        $user = User::findOrFail($address->user_id);
        $address->country_id = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->street = $request->street;
        $address->address = $request->address;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        if (!empty($request->is_default)) {
            Address::query()->where('user_id', $address->user_id)->whereNot('id',$id)->update(['is_default' => 0]);

            $address->is_default = 1;
            $user->country_id = $request->country;
            $user->country_id= $request->country;
            $user->state=  $request->state;
            $user->city=  $request->city;
            $user->address=$request->address;
            $user->street=$request->street;
            $user->postal_code=  $request->postal_code;

            $user->save();

        } else {
            $address->is_default = 0;
        }

        $address->save();

        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);

    }
    #endregion

    #region set default
    public function set_default($id)
    {
        $address = Address::find($id);
        $user = User::find($address->user_id);

        Address::query()->where('user_id', $address->user_id)->update(['is_default' => 0]);
        $user->country_id = $address->country_id;
        $user->save();
        $address->is_default = 1;
        $user->state=  $address->state;
        $user->city=  $address->city;
        $user->address=$address->address;
        $user->street=$address->street;
        $user->postal_code=  $address->postal_code;
        $user->save();
        $address->save();
        return response()->data(['message' => trans('backend.address.set_as_default_successfully')]);
    }

    #endregion
    #region delete
    public function destroy($id)
    {
        if (!permission_can('delete coupon', 'admin')) {
            return abort(403);
        }

        if (Address::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion
}
