<?php

namespace App\Http\Requests\Backend\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SocialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('setting social', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'social_email' => '',
            'social_facebook' => '',
            'social_twitter' => '',
            'social_telegram' => '',
            'social_whatsapp' => '',
            'social_phone' => '',
            'social_tiktok' => '',
        ];
    }
}
