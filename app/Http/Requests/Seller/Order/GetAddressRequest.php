<?php

namespace App\Http\Requests\Seller\Order;

use App\Rules\Seller\Order\CheckUserHasTheSameSeller;
use Illuminate\Foundation\Http\FormRequest;

class GetAddressRequest extends FormRequest
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
            'uuid' => ['required' ,'exists:users,uuid' ,new CheckUserHasTheSameSeller( ) ]
        ];
    }
}
