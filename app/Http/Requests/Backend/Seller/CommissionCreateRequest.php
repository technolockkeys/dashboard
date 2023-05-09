<?php

namespace App\Http\Requests\Backend\Seller;

use App\Rules\Backend\Seller\CheckCommissionRule;
use App\Rules\Backend\Seller\CheckFromRule;
use App\Rules\Backend\Seller\CheckToRule;
use Illuminate\Foundation\Http\FormRequest;

class CommissionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('admin')->check() && permission_can('add commission seller' ,'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'seller_id' => 'required|exists:sellers,id',
            'from' => ['required','numeric', new CheckFromRule(request('seller_id')) ],
            'to' => ['required','numeric', new CheckToRule(request('seller_id')) ],
            'commission' => ['required', new CheckCommissionRule(request('seller_id'))]
        ];
    }
}
