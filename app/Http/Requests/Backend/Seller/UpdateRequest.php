<?php

namespace App\Http\Requests\Backend\Seller;

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
        return permission_can('edit seller', 'admin');
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
            'email' => 'required|unique:sellers,email,'.$this->segment(3),
            'password' => 'nullable|confirmed',
            'seller_manager' => 'nullable',
            'seller_product_rate' => 'required|numeric|min:0|max:100'

        ];
    }
}
