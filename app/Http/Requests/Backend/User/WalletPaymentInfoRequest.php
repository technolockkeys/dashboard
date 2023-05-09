<?php

namespace App\Http\Requests\Backend\User;

use App\Http\Requests\Api\User\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class WalletPaymentInfoRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth('admin')->check() && permission_can('show wallets' , 'admin')) || (auth('seller')->check() && permission_can('edit payment recodes seller user' , 'seller'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
           'id'=>'required|exists:user_wallet,id'
        ];
    }
}
