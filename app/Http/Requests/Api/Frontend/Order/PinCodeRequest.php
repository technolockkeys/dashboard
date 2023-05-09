<?php

namespace App\Http\Requests\Api\Frontend\Order;

use App\Http\Requests\Api\User\BaseRequest;

class PinCodeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  true;
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
            'brand' => 'required|exists:brands,id',
            'serial_number' => 'required|max:17|min:17',
        ];
    }
}
