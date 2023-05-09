<?php

namespace App\Http\Requests\Api\User\Cart;

use App\Http\Requests\Api\User\BaseRequest;

class ChangeQuantityRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required'
        ];
    }
}
