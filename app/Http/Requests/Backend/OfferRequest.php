<?php

namespace App\Http\Requests\Backend;

use App\Models\Offer;
use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('create offer', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rule = [];
//        $rule['from'] = ['required', function($attribute, $value, $fail){
//            $values = Offer::query()->whereNot('id', request()->segment(3))
//                ->where('from', '<=', $value)->where('to', '>=' , $value)->first();
//            if($values != null)
//                $fail('The values can not be intersected');
//
//        }];
        $rule['from'] =['required'];
//        $rule['to'] = ['required', function($attribute, $value, $fail){
//            $values = Offer::query()->whereNot('id', request()->segment(3))
//                ->where('from', '<=', $value)->where('to', '>=' , $value)->first();
//            if($values != null)
//                $fail('The values can not be intersected');
//
//        }];
        $rule['to'] =['required'];
        $rule['days'] = 'required';
        $rule['discount_type'] = 'required';
        $rule['type'] = 'required';

        if (request()->discount_type == 'Percentage') {
            $rule['discount'] = 'required|numeric|min:1|max:100';
        } else {
            $rule['discount'] = 'required|numeric|min:1';
        }
        if (request()->type == 'Product') {
            $rule['products_ids'] = ['required'];

        }
        return $rule;
    }
}
