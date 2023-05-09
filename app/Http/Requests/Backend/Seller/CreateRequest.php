<?php

namespace App\Http\Requests\Backend\Seller;

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
        return permission_can('create seller', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|unique:sellers,email',
            'password' => 'nullable|confirmed',
            'seller_manager' => 'nullable',
            'seller_product_rate' => 'required|numeric|min:0|max:100',
        ];
    }
}
