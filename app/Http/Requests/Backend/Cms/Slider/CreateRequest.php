<?php

namespace App\Http\Requests\Backend\Cms\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('create slider', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        foreach (get_languages() as $language){
            if ($language->is_default){
                $rules['image_'.$language->code] = 'required';
                $rules['link_'.$language->code] = 'required';
            }
            $rules['status'] ='';
            $rules['type'] =['required', Rule::in(['main', 'banner'])];
        }
        return $rules;
    }
}
