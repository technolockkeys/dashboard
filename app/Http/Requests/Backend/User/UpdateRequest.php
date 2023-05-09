<?php

namespace App\Http\Requests\Backend\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('edit user', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = User::findOrFail(request()->segment(3));
        return [
//            'avatar' => Rule::requiredIf($user->avatar == null || request()->avatar_remove != null ),
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.request()->segment(3),
            'phone' => 'required|unique:users,phone,'.request()->segment(3),
//            'state' => '',
            'street' => '',
            'seller' => 'nullable|exists:sellers,id',
            'password'=>'nullable|confirmed|min:8',
        ];
    }
}
