<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\MeRequest;
use App\Models\Seller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    function me(MeRequest $request)
    {
        $user = auth('api')->user();
        $seller =Seller::query()->select(
            'name' ,
            'email',
            'facebook',
            'phone',
            'skype',
            'whatsapp_number',
            'avatar',

            )->where('id' , $user->seller_id)->first();
        if (!empty($seller)){
            $seller->avatar = asset($seller->avatar);
        }
         $data=array(
            "id" => $user->id,
            "uuid" => $user->uuid,
            "country_id" => $user->country_id,
            "city" => $user->city,
            "name" => $user->name,
            "provider_type" => $user->provider_type,
            "email" => $user->email,
            "avatar" => ( !empty($user->avatar) && !filter_var($user->avatar, FILTER_VALIDATE_URL)) ? asset($user->avatar) : $user->avatar,
            "address" => $user->address,
            "state" => $user->state,
            "street" => $user->street,
            "company_name" => $user->company_name,
            "website_url" => $user->website_url,
            "type_of_business" => $user->type_of_business,
            "postal_code" => $user->postal_code,
            "balance" => $user->balance,
            "referral_code" => $user->referral_code,
            "status" => $user->status,

        );
        if (!empty($user->seller)) {
            $data ['seller'] = [
                'name' => $user->seller ?->name,
                'email' => $user->seller ?->email,
                'whatsapp_number' => $user->seller ?->whatsapp_number,
                'phone' => $user->seller ?->phone,
                'skype' => $user->seller ?->skype,
                'facebook' => $user->seller ?->facebook,
                'avatar' =>  asset($seller->avatar),
            ];

        }

        return response()->api_data(['data'=>$data]);
    }
}
