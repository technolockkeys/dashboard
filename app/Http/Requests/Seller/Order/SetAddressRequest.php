<?php

namespace App\Http\Requests\Seller\Order;

use App\Rules\Seller\Order\CheckUserHasTheSameSeller;
use Illuminate\Foundation\Http\FormRequest;

class SetAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('seller')->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id'=>['required','exists:users,uuid',new CheckUserHasTheSameSeller()],
            'country'=>'required|exists:countries,id',
            'city'=>'required',
            'postal_code'=>'required',
            'address'=>'required',
            'number'=>'required',

        ];
    }
}
