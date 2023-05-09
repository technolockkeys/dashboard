<?php

namespace App\Http\Requests\Backend\Reply;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('edit reply', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'edit_reply' =>  'required',//Rule::requiredIf(empty(request('status'))&& empty(request('files'))),
            'old_files' =>  '',//Rule::requiredIf(empty(request('reply'))&& empty(request('status'))),
            'files' =>  '',//Rule::requiredIf(empty(request('reply'))&& empty(request('status'))),
        ];
    }
}
