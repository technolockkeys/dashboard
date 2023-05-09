<?php

namespace App\Http\Requests\Backend\Products;

use App\Models\BrandModelYear;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('create product', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $rules = [];
        foreach (get_languages() as $lang) {
            if ($lang->is_default) {
                $rules['title_' . $lang->code] = 'required';
                $rules['description_' . $lang->code] = 'required';
                $rules['meta_description_' . $lang->code] = 'required';
                $rules['meta_title_' . $lang->code] = 'required';
            }

        }
        $rules['discount_type'] = ['required', Rule::in('fixed', 'none', 'percent')];
        if (request('discount_type') != "none") {

            if (request('discount_type') != "fixed") {
                $rules['discount_value'] = 'required|numeric|max:100';

            } else {
                $rules['discount_value'] = 'required|numeric|max:' . (request()->has('sale_price') ? request('sale_price') : request('price'));
            }

        }
        $rules['slug'] = 'required|unique:products,slug';
        $rules['priority'] = 'required';
        $rules['category'] = 'required';
        $rules['image'] = 'required';
        $rules['manufacturer'] = '';
        $rules['price'] = 'required';
        $years_to = $this->get('years_to');
        $years_from = $this->get('years_from');

        $rules['years_to.*'] = [
            function ($attribute, $value, $fail) use ($years_from, $years_to) {
                $index = array_search($value, $years_to);
                if ($years_from[$index] != null && $value == null)
                    $fail(trans('validation.year_required', ['attribute' => $attribute]));
            },
            function ($attribute, $value, $fail) use ($years_from, $years_to) {
                $index = array_search($value, $years_to);

                $from = BrandModelYear::find($years_from[$index])?->year;
                $to = BrandModelYear::find($value)?->year;
                if ($from > $to) {
                    $fail(trans('backend.validation.year_is_small', ['attribute' => $attribute, 'year' => $from]));
                }
            }
        ];

        if (request()->has('weight')) {
            $rules['weight'] = 'required';
        }
        $rules['sku'] = 'required|unique:products,sku';
//        $rules['quantity']= 'required';

        return $rules;
    }
}
