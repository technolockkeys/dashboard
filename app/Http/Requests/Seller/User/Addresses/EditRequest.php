<?php

namespace App\Http\Requests\Seller\User\Addresses;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('seller')->check()&& permission_can('edit address seller user','seller');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'uuid'=>'required|exists:users,uuid',
            'id'=>'required|exists:addresses,id',
            'country'=>'required|exists:countries,id',
            'city'=>'required',
            'street'=>'required',
            'full_address'=>'required',
            'state'=>'nullable',
            'postal_code'=>'nullable',
            'phone'=>'nullable',
        ];
    }
}
