<?php

namespace App\Http\Requests\Backend\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'email'=>'email|unique:admins,email,'.auth('admin')->id(),
            'name'=>'required',
            'password'=>'nullable|confirmed|min:8',
            'avatar' => '',
            'avatar_remove' => ''
        ];
    }
}
