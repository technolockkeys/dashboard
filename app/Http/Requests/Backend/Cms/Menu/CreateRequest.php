<?php

namespace App\Http\Requests\Backend\Cms\Menu;

use App\Models\Menu;
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
        return permission_can('create menu', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        foreach (get_languages() as $item) {
            if ($item->is_default == 1) {
                $rules['title_' . $item->code] = 'required|max:256';
            }
        }
        $rules['type'] = 'required|' . Rule::in(Menu::types());
        $rules['link'] = 'required';
        $rules['icon'] = Rule::requiredIf(request()->get('type') == 'header');
        return $rules;
    }
}
