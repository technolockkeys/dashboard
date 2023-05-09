<?php

namespace App\Http\Requests\Backend\Page;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('edit page', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {


        $rules = [];
//        $rules['slug'] = 'required|max:254|unique:pages,slug,'.request()->segment(3);
        $rules['slug'] = ['required','max:254',Rule::unique('pages','slug')->ignore(request()->segment(3))];
        $rules['meta_image'] = 'required';

        foreach (get_languages() as $item){
            if($item->is_defualt)
                $rules['title_'.$item->code] = 'required';
        }
        return $rules;
    }
}
