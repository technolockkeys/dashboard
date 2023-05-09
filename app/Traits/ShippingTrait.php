<?php


namespace App\Traits;


use App\Models\Country;
use App\Models\Order;
use App\Models\ZonePrice;
use Illuminate\Http\Request;

trait ShippingTrait
{

    public function shipping_cost($country_id, $weight, $with_currency = true, $shipping_method = null)
    {
        $country = Country::find($country_id);
        if (empty($country)) {
            return ['error' => trans('backend.global.error_message.this_country_does_not_have_a_region'), 'shipping' => 0];
        }
        if ($weight == 0) {
            return ['shipping' => $with_currency ? currency(0) : 0];
        }
        $shipping_price = ZonePrice::query()->where('zone_id', $country->zone_id)
            ->where('weight', '>=', $weight)->first();

        if ($shipping_price == null) {
            return ['error' => trans('backend.global.error_message.this_country_does_not_have_a_region'), 'shipping' => 0];
        }
        $price_setting_dhl = get_setting('dhl');
        $price_setting_aramex = get_setting('aramex');
        $price_setting_fedex = get_setting('fedex');
        $price_setting_ups = get_setting('ups');

        $shipping_price_dhl = $shipping_price->price;
        $shipping_price_aramex = $shipping_price->price + ($price_setting_aramex * $shipping_price->price / 100);
        $shipping_price_fedex = $shipping_price->price + ($price_setting_fedex * $shipping_price->price / 100);
        $shipping_price_ups = $shipping_price->price + ($price_setting_ups * $shipping_price->price / 100);
        if (!empty($shipping_method)) {
            switch ($shipping_method) {
                case Order::$DHL:
                    return ['shipping' => $with_currency ? currency($shipping_price_dhl) : $shipping_price_dhl];
                    break;
                case Order::$FedEx:
                    return ['shipping' => $with_currency ? currency($shipping_price_fedex) : $shipping_price_fedex];
                    break;
                case Order::$Aramex:
                    return ['shipping' => $with_currency ? currency($shipping_price_aramex) : $shipping_price_aramex];
                    break;
                case Order::$UPS:
                    return ['shipping' => $with_currency ? currency($shipping_price_ups) : $shipping_price_ups];
                    break;

            }
        }
        return ['shipping' =>
            [
                Order::$DHL . '' => $with_currency ? currency($shipping_price_dhl) : $shipping_price_dhl,
                Order::$Aramex . '' => $with_currency ? currency($shipping_price_aramex) : $shipping_price_aramex,
                Order::$FedEx . '' => $with_currency ? currency($shipping_price_fedex) : $shipping_price_fedex,
                Order::$UPS . '' => $with_currency ? currency($shipping_price_ups) : $shipping_price_ups
            ]
        ];
    }

    protected function calculate_shipping_cost(Request $request)
    {
        $validated = $request->validate([
            'shipping_country' => 'required|exists:countries,id',
            'shipping_weight' => 'required|numeric',
        ]);
        $response = $this->shipping_cost($request->shipping_country, $request->shipping_weight);
        if (!empty($response['error'])) {
            return response()->error($response['error']);
        }
        return response()->data($response);
    }
}
