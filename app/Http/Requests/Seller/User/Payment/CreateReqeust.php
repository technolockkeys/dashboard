<?php

namespace App\Http\Requests\Seller\User\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CreateReqeust extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('seller')->check()&& permission_can('edit payment recodes seller user','seller');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'order' => 'nullable',
            'amount' => 'required|numeric',
            'note' => 'nullable'
        ];
    }
}
