<?php

namespace App\Http\Requests\Backend\CMS\Status;

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
        return permission_can('create status', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
//        dd(request()->segments());
        $rules = [];
        $rules['type'] = 'required';
        foreach (get_languages() as $item){
            if($item->is_default){
                $rules['image_' . $item->code] = 'required';
                $rules['link_' . $item->code] = Rule::requiredIf(request()->get('type') == 'link');
                $rules['media_data_' . $item->code] = Rule::requiredIf(request()->get('type') == 'image');
                $rules['video_'.$item->code] = Rule::requiredIf(request()->get('type') == 'video');
            }
        }
        $rules['order'] = 'required|numeric|min:1|unique:statuses,order,'.request()->segment(4);
        return $rules;
    }
}
