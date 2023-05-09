<?php

namespace App\Http\Requests\Api\User\Cart;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product'=>'exists:products,sku',
//            'address'=>'required|exists:addresses,id',
            'quantity'=>'',
            'note'=> 'nullable',
            'products'=>'string'
        ];
    }
}
