<?php

namespace App\Http\Requests\Seller\AfterSales;

use Illuminate\Foundation\Http\FormRequest;

class SaveBlackListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('seller')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'users' => 'nullable|exists:users,id'
        ];
    }
}
