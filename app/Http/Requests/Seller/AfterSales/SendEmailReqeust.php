<?php

namespace App\Http\Requests\Seller\AfterSales;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailReqeust extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('seller')->check() && (permission_can('send after sales','seller') || permission_can('resend after sales' , 'seller'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
