<?php

namespace App\Http\Requests\Api\User\Address;

use App\Http\Requests\Api\User\BaseRequest;

class CreateAddressRequest extends BaseRequest
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
            'country_id' => 'required|exists:countries,id',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'street' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
            'is_default' => 'nullable'
        ];
    }
}
