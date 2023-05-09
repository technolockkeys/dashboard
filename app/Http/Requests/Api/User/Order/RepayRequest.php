<?php

namespace App\Http\Requests\Api\User\Order;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RepayRequest extends BaseRequest
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
        $validation = [];
        $validation['order_id'] = 'required|exists:orders,id';
        $validation['payment_method'] = ['required', Rule::in(['stripe', 'paypal'])];
        if (request()->has('payment_method') && request()->payment_method == 'stripe') {
            $validation['card_id'] ='required';
            if (request()->card_id == -1  ){
                $validation['card_name'] = 'required';
                $validation['card_number'] = 'required';
                $validation['card_exp_month']='required|numeric|max:12';
                $validation['card_exp_year']='required|numeric|max:9999|min:'.date('Y');
                $validation['card_cvc']='required|numeric|max:9999|min:1';
            }
        }
        return $validation;
    }
}
