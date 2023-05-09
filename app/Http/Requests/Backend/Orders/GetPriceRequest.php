<?php

namespace App\Http\Requests\Backend\Orders;

use Illuminate\Foundation\Http\FormRequest;

class GetPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sku' => ['required', 'exists:products,sku'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'address' => ['required', 'exists:addresses,id'],
        ];
    }
}
