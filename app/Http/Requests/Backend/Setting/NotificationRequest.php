<?php

namespace App\Http\Requests\Backend\Setting;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('setting notifications', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'apiKey' => 'nullable',
            'authDomain' => 'nullable',
            'projectId' => 'nullable',
            'storageBucket' => 'nullable',
            'messagingSenderId' => 'nullable',
            'appId' => 'nullable',
            'measurementId' => 'nullable',
        ];
    }
}
