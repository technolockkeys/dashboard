<?php

namespace App\Http\Controllers\Api\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Http\Requests\Api\User\Address\CreateAddressRequest;
use App\Http\Requests\Api\User\User\ProfileRequest;
use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Google\Service\Dfareporting\Ad;
use Illuminate\Http\Request;

class AddressController extends Controller
{


    public function addresses(ProfileRequest $request)
    {
        $user = User::find(auth('api')->id());
        $length = 12;
        $page = 1;

        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $total = $user->addresses()->count();

        $addresses = [];
        $user_addresses = $user->addresses()->skip(($page - 1) * $length)->limit($length)->get();
        foreach ($user_addresses as $address) {
            $addresses[] = [
                'id' => $address->id,
                'country' => [
                    'id' => $address->country_id,
                    'country_name' => $address->get_country()
                ],
                'city' => $address->city,
                'address' => $address->address,
                'phone' => $address->phone,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'street' => $address->street,
                'default' => $address->is_default,
            ];
        }
        return response()->api_data(['addresses' => $addresses, 'total' => $total, 'page' => $page, 'total_pages' => ceil($total / $length), 'length' => sizeof($user_addresses)]);
    }

    public function create(CreateAddressRequest $request)
    {
        $address = Address::query()->make($request->except('country_id'));
        $user = auth('api')->user();
        if ($request->is_default == 1) {

            auth('api')->user()->addresses()->update([
                'is_default' => 0
            ]);
            $user->country_id = $request->country_id;
            $user->save();
        }
        $country = Country::find($request->country_id);

        $address->country()->associate($country);
        $address->user()->associate(auth('api')->user());
        $address->save();

        return response()->api_data(['message' => trans('api.review.added_successfully'),
            'data' => [
                'id' => $address->id,
                'country' => [
                    'id' => $address->country_id,
                    'country_name' => $address->get_country()
                ],
                'city' => $address->city,
                'address' => $address->address,
                'phone' => $address->phone,
                'postal_code' => $address->postal_code,
                'street' => $address->street,
                'default' => $address->is_default,
            ]]);
    }

    public function update(UpdateAddressRequest $request, $address_id)
    {
        $address = auth('api')->user()->addresses()
            ->where('id', $address_id)->first();
        if (!$address) {
            return response()->api_error(trans('frontend.address.not_found'), 404);
        }

        $user = auth('api')->user();
        if ($request->default) {
            $user->addresses()->update([
                'is_default' => 0
            ]);
        }
        $request->merge(['is_default' => $request->default]);
        $address->update($request->except('country_id'));
        $country = Country::find($request->country_id);
        $address->country()->associate($country);
//        $address->update(['is_default' => 1]);
        $address->save();

        return response()->api_data(['message' => trans('backend.global.success_message.updated_successfully'),
            'data' => [
                'id' => $address->id,
                'country' => [
                    'id' => $address->country_id,
                    'country_name' => $address->get_country()
                ],
                'city' => $address->city,
                'address' => $address->address,
                'phone' => $address->phone,
                'postal_code' => $address->postal_code,
                'street' => $address->street,
                'default' => $address->is_default,
            ]]);

    }

    public function destroy($address)
    {
        $address = Address::find($address);

        if ($address->user_id == auth('api')->id()) {
            Address::destroy($address->id);
            return response()->api_data(['message' => trans('api.review.deleted_successfully')]);
        }

        return response()->api_error(['message' => trans('backend.global.unauthorized')]);

    }

    public function set_default_address($address)
    {
        $address = auth('api')->user()->addresses()
            ->where('id', $address)->first();
        if (!$address) return response()->api_error([trans('backend.global.not_found')], 404);
        $user = auth('api')->user();
        $user->addresses()->update([
            'is_default' => 0
        ]);

        $address->update(['is_default' => 1]);
        $user->country_id = $address->country_id;
        $user->save();
        return response()->api_data(['message' => trans('backend.global.success_message.updated_successfully')]);

    }
}
