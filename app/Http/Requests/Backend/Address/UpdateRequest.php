<?php

namespace App\Http\Requests\Backend\Address;

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
        return permission_can('edit user address', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'country' => ['required', 'exists:countries,id',],
            'state' => ['required',],
            'city' => ['required',],
            'street' => ['required',],
            'address' => ['required',    'min:5'],
            'phone' => '',
            'postal_code' => '',
        ];
    }
}
