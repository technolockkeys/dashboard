<?php

namespace App\Http\Requests\Frontend\Api\Order;

use App\Http\Requests\Api\User\BaseRequest;

class CheckoutRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'coupon_code' => 'nullable|exists:coupons,code,deleted_at,NULL',
            'address' => 'nullable|exists:addresses,id,deleted_at,NULL',
            'shipping_method' => 'required|in:dhl,aramex,ups,fedex',
        ];
    }
}
