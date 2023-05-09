<?php

namespace App\Http\Requests\Api\User\Order\Review;

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
            'product_slug'=>'required|exists:products,slug',
            'rating'=>'required|numeric|min:1|max:5',
            'comment'=>'required',
        ];
    }
}
