<?php

namespace App\Http\Requests\Seller\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrder extends FormRequest
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
        $data = array();
        $data['address'] = ['required', 'exists:addresses,id'];
        $data['orders_item'] = ['required', 'array'];
        $data['shipping_method'] = ['required', Rule::in(Order::$DHL, Order::$Aramex, Order::$FedEx, Order::$UPS)];
        $data['type'] = ['required', Rule::in(Order::$proforma, Order::$order)];
        $data['user'] = ['required', 'exists:users,uuid'];
        $data['status']='required';
        $data['shipment_value']='required';
        $data['shipment_description']='required';
        $data['files.*'] = 'mimes:jpg,jpeg,png,bmp,pdf|max:20000';

        $data['currency_symbol']='required';
        $data['currency_rate']='required';
        if (request()->has('orders_item')){
            foreach (request('orders_item') as $item){
                $data['product_price_'.$item]= 'required';
                $data['quantity_'.$item]= 'required';
            }
        }
        return $data;
    }
}
