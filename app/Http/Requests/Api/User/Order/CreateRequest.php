<?php

namespace App\Http\Requests\Api\User\Order;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends BaseRequest
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
        $validation['address'] = 'required';
        $validation['files.*'] = 'mimes:jpg,jpeg,png,bmp,pdf|max:20000';

        $validation['payment_method'] =['required' , Rule::in('stripe' ,'paypal','transfer')];
        $validation['shipping_method'] =['required' , Rule::in('dhl' ,'aramex', 'fedex', 'ups')];
//        $validation['currency'] = ['required', 'exists:currencies,symbol'];
        if (request()->payment_method == 'stripe') {
            $validation['card_id'] = 'required';
            if (request()->card_id == -1 ){
                $validation['card_name']= 'required';
                $validation['card_number']= 'required';
                $validation['card_exp_month']='required|numeric|max:12';
                $validation['card_exp_year']='required|numeric|max:9999|min:'.date('Y');
                $validation['card_cvc']='required|numeric|max:9999|min:1';
            }
        }

        return $validation;
    }
}
