<?php

namespace App\Http\Requests\Api\User\Auth;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends BaseRequest

{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }


}
