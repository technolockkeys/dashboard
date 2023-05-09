<?php

namespace App\Http\Requests\Backend\Language;

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
        return permission_can('create language' ,'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'language' => 'required|min:3',
            'code' => 'required',
            'display_type' => ['required', Rule::in(['RTL','LTR'])],
            'is_default' => '',
            'status' => '',
            'flag' => 'required'
        ];
    }
}
