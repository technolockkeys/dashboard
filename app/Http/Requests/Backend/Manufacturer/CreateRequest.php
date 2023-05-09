<?php

namespace App\Http\Requests\Backend\Manufacturer;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('create manufacturer', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['image'] = 'required';
        $rules['status'] ='';
        foreach (get_languages() as $language) {
            if ($language->is_default) {
                $rules['title_' . $language->code] = 'required';
                $rules['description_' . $language->code] = 'required';
                $rules['meta_title_' . $language->code] = '';
                $rules['meta_description_' . $language->code] = '';
            }
        }
        return $rules;
    }
}
