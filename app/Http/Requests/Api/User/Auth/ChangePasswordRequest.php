<?php

namespace App\Http\Requests\Api\User\Auth;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Validation\Rule;

class ChangePasswordRequest extends BaseRequest
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
            'old_password' => Rule::requiredIf(auth('api')->user()->password !=null),
            'password' => 'confirmed|required|min:8'
        ];
    }
}
