<?php

namespace App\Http\Requests\Backend\Brand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('edit brand', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        foreach (get_languages() as $lang){
            if ($lang->is_default){
                $rules['make_'.$lang->code] = 'required';
                $rules['description_'.$lang->code] = '';
            }
        }
        $rules['image'] = '';
        $rules['pin_code_price'] = 'required';
        return $rules;
    }
}
