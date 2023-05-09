<?php

namespace App\Http\Requests\Backend\Setting;

use Illuminate\Foundation\Http\FormRequest;

class EditSetting extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('setting website', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        foreach (get_languages() as $lang) {
            $rules['system_name_' . $lang->code] = 'required|min:3';
        }
        $rules['app_url'] = 'required';
        $rules['system_logo_white'] = 'required';
        $rules['system_logo_black'] = 'required';
        $rules['admin_background'] = 'required';
        $rules['system_logo_icon'] = 'required';
        $rules['merchant_app_name'] = 'required';
        $rules['merchant_id'] = 'required';
        $rules['client_credentials_path'] = 'required';
        return $rules;
    }
}
