<?php

namespace App\Http\Requests\Api\Frontend\Order;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PinCodePaypalRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true ;
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
            'response' => 'required',
            'order_id'=> 'required|exists:orders,id',
            'order_payment_id' => 'required|exists:orders_payments,id',
        ];
    }
}
