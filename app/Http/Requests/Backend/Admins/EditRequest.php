<?php

namespace App\Http\Requests\Backend\Admins;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public $id ;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if (!permission_can('edit admin', 'admin')) {
            return abort(403);
        }

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
            'email'=>'email|unique:admins,email,'.request()->segment(3),
            'name'=>'required',
            'password'=>'confirmed',
            'role'=>'required',
            'avatar' => '',
            'avatar_remove' => ''
        ];
    }
}
