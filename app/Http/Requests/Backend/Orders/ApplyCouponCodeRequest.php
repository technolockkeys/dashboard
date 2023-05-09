<?php

namespace App\Http\Requests\Backend\Orders;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponCodeRequest extends FormRequest
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
            'coupon_code' => 'required|exists:coupons,code',
            'user_id' => 'required|exists:users,uuid'
        ];
    }
}
