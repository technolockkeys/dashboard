<?php

namespace App\Http\Requests\Backend\Setting;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('setting contact', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'contact_email' => 'required',
            'contact_email_secondary' => '',
            'contact_telegram' => 'required',
            'contact_whatsapp' => 'required',
            'contact_phone' => 'required',
            'contact_phone_secondary' => '',
            'contact_address' => '',
        ];
    }
}
