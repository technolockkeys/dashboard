<?php

namespace App\Http\Requests\Api\User\Card;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends BaseRequest
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
            'card_name' => 'required',
            'card_number' => 'required',
            'expiry_month' => 'required|numeric|digits:2',
            'expiry_year' => 'required|numeric|digits:4',
            'card_cvc'=>'required|numeric|digits:3,4'
        ];
    }
}
