<?php

namespace App\Http\Requests\Backend\Setting;

use Illuminate\Foundation\Http\FormRequest;
use function permission_can;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('paypal update', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'paypal_client_id'=> 'required',
//            'paypal_client_secret'=> 'required',
            'paypal_sandbox_mode'=> '',
            'paypal_status'=> '',
            'paypal_client_id_test'=> 'required',
//            'paypal_client_secret_test'=> 'required',
            'strip_key'=> 'required',
//            'strip_secret'=> 'required',
            'strip_key_test'=> 'required',
//            'strip_secret_test'=> 'required',
            'strip_sandbox_mode'=> '',
            'strip_status'=> '',
        ];
    }
}
