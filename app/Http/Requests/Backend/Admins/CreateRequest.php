<?php

namespace App\Http\Requests\Backend\Admins;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!permission_can('create admin', 'admin')) {
            return abort(403);
        }
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'=>'email|unique:admins,email',
            'name'=>'required',
            'password'=>'required|confirmed|min:8',
            'role'=>'required',
            'avatar' => '',
            'avatar_remove' => ''
        ];
    }
}
