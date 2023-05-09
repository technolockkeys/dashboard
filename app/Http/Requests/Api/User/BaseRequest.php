<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return auth('api')->check();
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->api_error('validation error',403, $validator->errors()));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->api_error('unauthorized', 401));
    }
}
