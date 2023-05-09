<?php

namespace App\Http\Requests\Backend\CMS\Status;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            if ($item->is_default) {
                $rules['image_' . $item->code] = 'required';
                $rules['type_' . $item->code] = 'required';
                $rules['link_' . $item->code] = Rule::requiredIf(request()->get('type_' . $item->code) == 'link');
                $rules['media_data_' . $item->code] = Rule::requiredIf(request()->get('type_' . $item->code) == 'image');
                $rules['video_' . $item->code] = Rule::requiredIf(request()->get('type_' . $item->code) == 'video');
            }
        }
        $rules['order'] = 'required';
        return $rules;

    }
}
