<?php

namespace App\Http\Requests\Api\User\User;

use App\Http\Requests\Api\User\BaseRequest;

class UserUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'avatar' => 'nullable',
            'email' => 'required|email|unique:users,email,'.auth('api')->id(),
            'phone' => 'required|numeric|unique:users,phone,'.auth('api')->id() ,
            'type_of_business' => 'nullable',
            'company_name' => 'nullable',
            'website_url' => 'nullable',

        ];
    }
}
