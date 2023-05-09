<?php

namespace App\Http\Requests\Api\User\Auth;

use App\Http\Requests\Api\User\BaseRequest;

class RegisterRequest extends BaseRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'avatar' => 'nullable',
            'address' => 'nullable',
            'country' => 'nullable|exists:countries,id',
            'state' => 'nullable',
            'street' => 'nullable',
            'company_name' => 'nullable',
            'website_url' => 'nullable',
            'postal_code' => 'nullable',
        ];
    }

}
