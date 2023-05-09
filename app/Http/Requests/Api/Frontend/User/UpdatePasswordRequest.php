<?php

namespace App\Http\Requests\Api\Frontend\User;

use App\Http\Requests\Api\User\BaseRequest;

class UpdatePasswordRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
//            'email' => 'required|email|exists:users,email',
            'token' => 'required|exists:password_resets,token',
            'password' => 'required|confirmed'
        ];
    }
}
