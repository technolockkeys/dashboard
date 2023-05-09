<?php

namespace App\Http\Requests\Api\User\Product;

use App\Http\Requests\Api\User\BaseRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        return [
            'page'=>'nullable|numeric',
            'length'=>'nullable|numeric',
            'categories' => 'nullable',
            'brands' => 'nullable',
            'models' => 'nullable',
            'years' => 'nullable',
            'manufacturers' => 'nullable',
            'free_shipping' => 'nullable|boolean',
            'super_sale' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'bundled' => 'nullable|boolean',
            'has_discount' => 'nullable|boolean',
            'colors.*' => 'nullable|exists:colors,slug',
            'price' => 'nullable|numeric|min:0',
            'lowest_price' => 'nullable|numeric|min:0',
            'highest_price' => 'nullable|numeric|min:0',
        ];
    }
}
