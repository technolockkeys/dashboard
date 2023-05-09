<?php

namespace App\Http\Requests\Backend\Setting;

use Illuminate\Foundation\Http\FormRequest;

class GolbalSeoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('setting global_seo', 'admin');
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
            $rules['meta_title_' . $lang->code] = 'required|min:3';
            $rules['meta_description_' . $lang->code] = 'required|min:3';

        }
        return $rules;
    }
}
