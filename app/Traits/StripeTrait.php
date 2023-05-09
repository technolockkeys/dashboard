<?php

namespace App\Traits;

use App\Models\Card;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\Settings;
use App\Models\User;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\PermissionException;
use Stripe\Stripe;
use Stripe\Token;

trait StripeTrait
{

    function create_card($user_id, $name, $number, $exp_month, $exp_year, $cvc)
    {
        $user = User::find($user_id);

        $sk = "";
        if (get_setting('stripe_sandbox_mode') == 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        try {

            $stripe = Stripe::setApiKey($sk);
            try {
                $card_token = Token::create(['card' => [
                    "name" => $name,
                    "number" => $number,
                    "exp_month" => $exp_month,
                    "exp_year" => $exp_year,
                    "cvc" => $cvc
                ]]);

                $stripe_cust_id = $user->stripe_cust_id;

                $token = $card_token['id'];

                if (!is_null($stripe_cust_id) || $stripe_cust_id != "") {
                    try {
                        $customer = Customer::retrieve($stripe_cust_id);
                        $card = Customer::createSource($stripe_cust_id, ['source' => $token]);
                    } catch (CardException $exception) {
                        return ['success' => false, 'message' => $exception->getMessage()];
                    }
                } else {
                    $customer = Customer::create(["email" => $user->email, 'source' => $token]);
                    $user->stripe_cust_id = $customer->id;
                    $user->save();
                }
                $card = new Card();
                $card->user_id = $user_id;
                $card->stripe_cust_id = $customer->id;
                $card->card_fingerprint = $card_token['card']['fingerprint'];
                $card->last_four = $card_token['card']['last4'];
                $card->card_id = $card_token['card']['id'];
                $card->brand = $card_token['card']['brand'];
                $card->is_default = 1;
                $card->save();
                $this->setCardDefault($card);
                return ['success' => true, 'message' => trans('api.card.card_added_successfully'), 'card' => $card];

            } catch (CardException $exception) {
                return ['success' => false, "message" => $exception->getMessage()];
            }
        } catch (PermissionException $exception) {
            return ['success' => false, "message" => $exception->getMessage()];

        }


    }

    function delete_card($user_id, $card_id)
    {

        $sk = "";
        if (get_setting('stripe_sandbox_mode') == 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        try {

            $stripe = Stripe::setApiKey($sk);
            $card = Card::query()->where('card_id', $card_id)->where('user_id', $user_id)->first();

            if ($card) {
                $stripe_cust_id = User::find($user_id)->stripe_cust_id;
                try {
                    try {
                        Customer::deleteSource($stripe_cust_id, $card->card_id);
                        Card::query()->where('card_id', $card_id)->where('user_id', $user_id)->delete();
                    } catch (InvalidRequestException $exception) {
                        return ['success' => false, 'message' => trans('api.card.not_found_card')];
//                        return ['success' => false, 'message' => $exception->getMessage()];

                    }
                    return ['success' => true, 'message' => trans('api.card.deleted_successfully')];
                } catch (CardException $exception) {
                    return ['success' => false, 'message' => $exception->getMessage()];
                }
            }
            return ['success' => false, 'message' => trans('api.card.not_found_card')];
        } catch (CardException $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    function all_card($user_id)
    {
        $sk = "";
        if (get_setting('stripe_sandbox_mode') == 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        $stripe_cust_id = User::find($user_id)->stripe_cust_id;

        try {
            $stripe = Stripe::setApiKey($sk);
            $cards = Customer::allSources($stripe_cust_id);
            return ['success' => true, 'cards' => $cards];
        } catch (CardException $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }

    }

    function create_product($id, $title, $description = null, $image = null)
    {
        $sk = "";
        if (get_setting('stripe_sandbox_mode') == 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        $stripe = new \Stripe\StripeClient($sk);
        $product_data['name'] = $title;
        if (!empty($description)) {
            $product_data['description'] = $description;
        }
//        if (!empty($image)) {
//            $product_data['images'] = [$image];
//        }
        $product = $stripe->products->create($product_data);
        Product::query()->where('id', $id)->update(['stripe_id' => $product->id]);
        return $product;
    }

    function create_price($product_stripe_id, $unit_price)
    {
        $sk = "";
        if (get_setting('stripe_sandbox_mode') == 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        $stripe = new \Stripe\StripeClient($sk);
        $price = $stripe->prices->create([
            'unit_amount' => intval(round($unit_price, 2) * 100),
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product' => $product_stripe_id,
        ]);
        return $price;
    }

    function create_payment_link($list, $orderid)
    {
//        list items array in array(price id  , quantity)


        $sk = "";
        if (get_setting('stripe_sandbox_mode') == 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        $stripe = new \Stripe\StripeClient($sk);
        $order = Order::find($orderid);
        $product_data['name'] = $order->uuid;
        $product = $stripe->products->create($product_data);
        $list_product = [];

        $price = $stripe->prices->create([
            'unit_amount' => intval(round($order->total, 2) * 100),
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product' => $product->id,
        ]);
        $list_product[] = ['price' => $price->id, 'quantity' => 1];
        $stripe_data = [];
        $stripe_data ['line_items'] = $list_product;
//        if ($order->coupon_value != 0) {
//            $coupon = $stripe->coupons->create(["currency" => "usd",
//                'amount_off' => intval($order->coupon_value * 100), 'duration' => 'once']);
//            $stripe_data ['discounts'] = ['coupon' => $coupon->id];
//        }

//        if ($order->shipping != 0) {
//            $stripe_data['shipping_cost'] = $order->shipping;
//        }

        $data = $stripe->paymentLinks->create($stripe_data);
        return ['link' => $data->url, 'data' => $data];

    }

    function stripe_refund($order_id)
    {
        return ['success' => true];
        if (get_setting('stripe_sandbox_mode') == 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        $stripe = new \Stripe\StripeClient($sk);
        $order_payment_old = OrderPayment::query()->where('order_id', $order_id)->where('payment_method', 'stripe')->where('status', 'captured')->first();
        if (empty($order_payment_old)) {
            return ['success' => false];
        }
        $stripe_old_payment = json_decode($order_payment_old->payment_details, true);
        $id_stripe_captured = $stripe_old_payment['charges']['data'][0]['id'];

        $amount_stripe_captured = $stripe_old_payment['amount'] / 100;
        $response = $stripe->refunds->create(['charge' => $id_stripe_captured]);
        $order = Order::query()->find($order_id);
        $order_payment = new OrderPayment();
        $order_payment->order_id = $order_id;
        $order_payment->user_id = $order->user_id;
        $order_payment->payment_method = Order::$stripe;
        $order_payment->amount = $amount_stripe_captured;
        if ($response->status == 'succeeded') {
            $order_payment->status = OrderPayment::$refund;
        } else {
            $order_payment->status = OrderPayment::$voided;
        }
        $order_payment->payment_details = $response;

        $order_payment->save();
        if ($response->status == 'succeeded') {
            return ['success' => true];
        }
        return ['success' => false];
    }

    function setCardDefault(Card $card)
    {
        Card::query()->where('user_id', $card->user_id)->update(['is_default' =>0]);
        Card::query()->where('id', $card->id)->update(['is_default' => 1]);

    }
}
