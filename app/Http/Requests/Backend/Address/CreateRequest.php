<?php

namespace App\Http\Requests\Backend\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('create user address', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'country' => ['required','exists:countries,id',],
            'city' => ['required'],
            'address' => ['required',    'min:5'],
            'phone' => '',
            'postal_code' => '',
        ];

    }
}
