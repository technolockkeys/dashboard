<?php

namespace App\Http\Requests\Api\User\Order;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResponsePaypalRequset extends BaseRequest
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
            'success' => ['required' ],
            'response' => 'required',
            'order_id' => ['required', 'exists:orders,uuid'],
            'order_payment_id' => 'required'
        ];
    }
}
