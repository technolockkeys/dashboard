<?php

namespace App\Http\Controllers\Api\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\Order\PinCodePaypalRequest;
use App\Http\Requests\Api\Frontend\Order\PinCodeRequest;
use App\Http\Requests\Api\User\Order\ResponsePaypalRequset;
use App\Mail\OrderUserMail;
use App\Mail\PincodeMail;
use App\Models\Brand;
use App\Models\Card;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\UserWallet;
use App\Traits\OrderTrait;
use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PinCodeController extends Controller
{
    use PaymentTrait;
    use OrderTrait;

    public function create(PinCodePaypalRequest $request, $type)
    {
//        $user = auth('api')->user();
        $payment_order = OrderPayment::query()
            ->where('order_id', $request->order_id)
//            ->where('user_id', auth('api')->id())
            ->where('payment_method', 'paypal')
            ->where('status', 'pending')
            ->where('id', $request->order_payment_id)
            ->first();
        if (empty($payment_order)) {
            return response()->error(trans('api.order.order_not_found'));
        }
        if ($type == 'success') {
            $payment_order->status = 'captured';
            $payment_order->save();
            $order = Order::query()->where('id', $request->order_id)->first();
            $order->status = Order::$processing;
            $order->payment_status = 'paid';
            $order->save();

            $bcc_emails = json_decode(get_setting('order_notifications_receivers'));
            $details['order'] = $order;
            $details['title'] = 'New order Created';
            $details['content'] = ' Created a new order';
            $details['button'] = 'Show order';


        } else {
            $payment_order->status = 'voided';
            $payment_order->save();
            $order = Order::query()->where('id', $request->order_id)->first();
            $order->status = 'pending_payment';
            $order->payment_status = 'unpaid';
            $order->save();
        }

        return response()->api_data(['message' => trans('api.cart.product_added_successfully')]);
    }

    public function get_prices()
    {
        $currency = request()->header('currency');
        $currency = Currency::where(function ($q) use ($currency) {
            $q->where('symbol', $currency)->orWhere('code', $currency);
        })->where('status', 1)->first();
        if (empty($currency)) {
            $currency = Currency::where('is_default', 1)->first();
        }
        $all_brands = Brand::where('pin_code_price', '>', 0)->where('status', 1)->get();

        $brands = [];
        foreach ($all_brands as $brand) {
            $brands[] = [
                'id' => $brand->id,
                'brand' => $brand->make,
                'slug' => $brand->slug,
                'icon' => get_multisized_image($brand->image, ['s']),
                'pin_code_price' =>
                    api_currency($brand->pin_code_price, $currency),
                'dolar_price' => [
                    'value' => number_format($brand->pin_code_price, 2, '.', ''),
                    'currency' => '$'
                ]
            ];
        }

        return response()->api_data(['brands' => $brands]);
    }

    public function get_brand_price(PinCodeRequest $request)
    {

        $currency = Currency::where('symbol', request()->header('currency'))->orWhere('is_default', 1)->first();

        $brand = Brand::find($request->brand);
        if ($brand != null) {

            $response = [
                'id' => $brand->id,
                'brand' => $brand->make,
                'pin_code_price' => api_currency($brand->pin_code_price, $currency)
            ];
            $order = new Order();

            $order->uuid = $this->generate_order_code();

            $order->type = 'pin_code';
            $order->total = $brand->pin_code_price;

            if ($order->total == 0) {
                $order->payment_method = 'paypal';
                $order->payment_status = 'unpaid';
                $order->status = 'waiting';
            }

            $order_info = [
                'brand' => $request->brand,
                'serial_number' => $request->serial_number,
                'contact_channel' => $request->contact_channel,
                'contact_value' => $request->contact_value,
            ];
            // create order payment

            // create user wallet records

            $order->note = json_encode($order_info, true);

            $order->phone = $request->phone;
            $order->user_id = auth('api')->check() ? auth('api')->id() : null;

            $order->save();
            $order_payment = new OrderPayment();
            $order_payment->order_id = $order->id;
            $order_payment->user_id = auth('api')->check() ? auth('api')->id() : null;
            $order_payment->amount = $order->total;
            $order_payment->payment_method = Order::$paypal;
            $order_payment->card_id = null;
            $order_payment->status = OrderPayment::$pending;
            $order_payment->save();


        } else {
            return response()->api_error(['message' => trans('api.brand.brand_not_found')]);

        }
        $order = $order->refresh();
        return response()->api_data(['brand' => $response, 'order' => $order->uuid, 'order_payment_id' => $order_payment->id]);
    }


}
