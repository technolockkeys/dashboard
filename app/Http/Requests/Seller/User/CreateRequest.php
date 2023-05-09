<?php

namespace App\Http\Requests\Seller\User;

use App\Http\Requests\Api\User\BaseRequest;
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
        return auth('seller')->check() && permission_can('create seller user','seller');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'full_name' => 'required',
            'email_address' => 'required|unique:users,email',
            'phone_number' => 'required|unique:users,phone',
            'country' => 'required',
            'city' => 'required',
            'street' => 'required',
            'full_address' => 'required',
            'website_url' => 'nullable|url',
            'company_name' => 'nullable',
            'type_of_business' => 'nullable',

        ];
    }
}
