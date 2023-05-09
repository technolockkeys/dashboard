<?php

namespace App\Http\Requests\Backend\Category;

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
        if (!permission_can('create category', 'admin')) {
            return abort(403);
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['slug'] = 'required|unique:categories,slug|max:254';
        $rules['parent'] = 'required';
        $rules['banner'] = '';
        $rules['icon'] = '';
        $rules['type'] =Rule::requiredIf(empty(request('parent'))&& empty(request('parent')));
        foreach (get_languages() as $lang){
            if ($lang->is_default){
                $rules['name_'.$lang->code] = 'required';
                $rules['description_'.$lang->code] = 'required';
                $rules['meta_description_'.$lang->code] = 'required';
                $rules['meta_title_'.$lang->code] = 'required';
            }
        }
        return $rules;
    }
}
