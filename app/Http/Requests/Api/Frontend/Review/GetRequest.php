<?php

namespace App\Http\Requests\Api\Frontend\Review;

use \App\Http\Requests\Api\User\BaseRequest;

class GetRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
//        dd('asdasd');
        return [
            'product_slug' => 'required|exists:products,slug',
            'page' => 'nullable|numeric',
            'length' => 'nullable|numeric',
        ];
    }
}
