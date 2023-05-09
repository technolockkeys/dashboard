<?php

namespace App\Http\Requests\Seller\Order;

use Illuminate\Foundation\Http\FormRequest;

class CouponApply extends FormRequest
{
    public function authorize()
    {
        return auth('seller')->check();
    }

    public function rules()
    {
        return [
            'coupon_code'=>'required|exists:coupons,code',
            'user_id'=>'required|exists:users,uuid'
        ];
    }
}
