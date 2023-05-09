<?php

namespace App\Http\Requests\Backend\Download;

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
        return permission_can('create download', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['slug'] = 'required|unique:downloads,slug,' . $this->segment(3);
        $rules['image'] = 'required';
        $rules['screen_shot'] = '';
        $rules['gallery'] = '';
        $rules['internal_image'] = '';
        $rules['name.*'] = 'required';
        $rules['link.*'] = 'required';
        $rules['types.*'] = 'required';
        $rules['videos_provider.*'] = 'required';
        $rules['video_url.*'] = 'required';
        foreach (get_languages() as $language) {
            if ($language->is_default) {
                $rules['title_' . $language->code] = 'required';
                $rules['description_' . $language->code] = 'required';
                $rules['meta_title_' . $language->code] = '';
                $rules['meta_description_' . $language->code] = '';
            }
        }
        return $rules;
    }
}
