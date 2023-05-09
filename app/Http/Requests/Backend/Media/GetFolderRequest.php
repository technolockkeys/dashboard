<?php

namespace App\Http\Requests\Backend\Media;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class GetFolderRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'path'=>'required',
            'file_name'=>'required'
        ];
    }
}
