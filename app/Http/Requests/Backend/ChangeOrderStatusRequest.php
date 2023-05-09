<?php

namespace App\Http\Requests\Backend;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return permission_can('edit order', 'admin') ||  permission_can('edit order', 'seller');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'status' => ['required', Rule::in(['canceled',
                'completed',
                'failed',
                'on_hold',
                'pending_payment',
                'processing',
                'refunded'])],
            'tracking_number' => 'nullable|string'
        ];
    }
}
