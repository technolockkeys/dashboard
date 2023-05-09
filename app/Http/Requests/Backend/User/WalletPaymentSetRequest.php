<?php

namespace App\Http\Requests\Backend\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WalletPaymentSetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth('admin')->check() && permission_can('change user balance', 'admin')) || (auth('seller')->check() || permission_can('edit payment recodes seller user', 'seller'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $data = [];
        $data['user_id'] = 'required|exists:users,id';
        $data['amount'] = 'required|numeric';
        $data['type'] = ['required', Rule::in(['withdraw', 'order', 'refund'])];
        $data['order_id'] = 'required|exists:orders,id';
        $data['files.*'] = 'mimes:jpg,jpeg,png,bmp,pdf|max:20000';
        $data['note'] = 'nullable';
        return $data;
    }
}
