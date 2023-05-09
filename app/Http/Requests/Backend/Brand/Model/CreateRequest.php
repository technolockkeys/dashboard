<?php

namespace App\Http\Requests\Backend\Brand\Model;

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
        return permission_can('create model', 'admin');
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
                $rules['model_'.$lang->code] = 'required';
            }
        }
        return $rules;

    }
}
