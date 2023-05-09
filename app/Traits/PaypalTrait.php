<?php

namespace App\Traits;

use App\Models\Card;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\Settings;
use App\Models\User;
use PayPal\Api\Capture;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment as SandboxEnvironment;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

use PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsVoidRequest;
use PayPalHttp\HttpException;

trait PaypalTrait
{
    private $client;

    function configuration_paypal()
    {
        $paypal_client_test = get_setting('paypal_client_id_test');
        $paypal_secret_test = get_setting('paypal_client_secret_test');
        $paypal_client = get_setting('paypal_client_id');
        $paypal_secret = get_setting('paypal_client_secret');
        $mode = get_setting('paypal_sandbox_mode') == 1 ? 'sandbox' : 'live';
        config()->set('paypal.mode', $mode);
        config()->set('paypal.sandbox.client_id', $paypal_client_test);
        config()->set('paypal.sandbox.client_secret', $paypal_secret_test);
        config()->set('paypal.live.client_id', $paypal_client);
        config()->set('paypal.live.client_secret', $paypal_secret);

        if (get_setting('paypal_sandbox_mode') == 0) {
            $environment = new ProductionEnvironment($paypal_client, $paypal_secret);
        } else {
            $environment = new SandboxEnvironment($paypal_client_test, $paypal_secret_test);
        }
        $this->client = new PayPalHttpClient($environment);
        return $this->client;
    }

    function refund_paypal($order_id)
    {
        return ['success' => true];
        $order = Order::query()->where('id', $order_id)->first();
        $old_order_payemnt = OrderPayment::query()
            ->where('status', OrderPayment::$captured)
            ->where('payment_method', 'paypal')
            ->where('order_id', $order_id)->where('user_id', $order->user_id)->first();
        $old_payment = json_decode($old_order_payemnt->payment_details);
        $captureID = $old_payment->purchase_units[0]->payments->captures[0]->id;
        $capture_amount = $old_payment->purchase_units[0]->payments->captures[0]->amount->value;
        $this->configuration_paypal();
        $order_payment = new OrderPayment();
        $order_payment->order_id = $order_id;
        $order_payment->user_id = $order->user_id;
            $order_payment->payment_method = Order::$paypal;
        $order_payment->amount = $capture_amount;
        try {
            $request = new CapturesRefundRequest($captureID);
            $response = $this->client->execute($request);
                if ($response->result->status == 'status') {
                    $order_payment->status = OrderPayment::$refund;
                }
                $order_payment->payment_details = $response;
                $order_payment->save();
            return ['success' => true];
        } catch (HttpException $exception) {
            $order_payment->payment_details = $exception->getMessage();
            $order_payment->status = OrderPayment::$voided;
            $order_payment->save();
            return ['success' => false];
        }
        return ['success' => false];


    }
}
