<?php

namespace App\Http\Requests\Backend\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('edit coupon', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rule = [];
        $rule['type'] = ['required', Rule::in(['Order', 'Product'])];
        $rule['code'] = ['required'];
        $rule['start_date'] = ['required'];
        $rule['end_date'] = ['required'];
        $rule['max_use'] = 'required|numeric|min:1';
        $rule['per_user'] = 'required|numeric|min:1';
        $rule['discount_type'] = ['required', Rule::in(['Percentage', 'Amount'])];
        if (request()->type == 'Product') {
            $rule['products_ids'] = ['required'];

        } else {
            $rule['minimum_shopping'] = ['required', 'regex:/^\d{1,13}(\.\d{1,4})?$/'];
        }
        if (request()->discount_type == 'Percentage') {
            $rule['discount'] = 'required|numeric|min:1|max:100';
        } else {
            $rule['discount'] = 'required|numeric|min:1';
        }
          return $rule;

    }
}
