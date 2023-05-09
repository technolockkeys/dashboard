<?php

namespace App\Http\Requests\Backend\Page;

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
        return permission_can('create page', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['slug'] = 'required|unique:pages,slug|max:254';
        $rules['meta_image'] = 'required';

        foreach (get_languages() as $item){
            if($item->is_default)
                $rules['title_'.$item->code] = 'required';
            $rules['meta_title_'.$item->code] = '';
        }
        return $rules;
    }
}
