<?php

namespace App\Http\Requests\Backend\Media;

use Illuminate\Foundation\Http\FormRequest;

class DetaileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!permission_can('edit media', 'admin')) {
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
        return [
            'id' => 'required'
        ];
    }
}
