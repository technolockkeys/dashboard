<?php

namespace App\Http\Requests\Backend\Attribute\SubAttrubte;

use Illuminate\Foundation\Http\FormRequest;
use function get_languages;
use function permission_can;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('create sub attribute', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['image'] = '';
        foreach (get_languages() as $item){
            if($item->is_default)
                $rules['value_'.$item->code] = 'required';
        }
        return $rules;
    }
}
