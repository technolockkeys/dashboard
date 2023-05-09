<?php

namespace App\Http\Requests\Api\Frontend;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Validation\Rule;

class CreateTicketRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['required', Rule::in(['order', 'product','support', 'shipping', 'other'])],
            'subject' => 'required|min:2',
            'details' => 'required|min:2',
            'files' => '',
            'files.*' => 'file',
        ];
    }
}
