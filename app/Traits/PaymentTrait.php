<?php

namespace App\Traits;

use App\Mail\OrderUserMail;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Card;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\ZonePrice;
use Google\Service\Dfareporting\Ad;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\StripeClient;

trait PaymentTrait
{
    use EraningsTrait;

//    function shipping_cost(Address $address)
//    {
//        $country = $address->country()->first();
//
//        if (empty($country)) {
//            return ['success' => false];
//        }
//        $zone_id = $country->zone_id;
//        $cart_items = Cart::query()->where('user_id', auth('api')->id())->get();
//        $cost = 0;
//
//        foreach ($cart_items as $item) {
//            $product = $item->product;
//
//            if (!empty($product)) {
//                $weight = $product->weight;
//                $zone = ZonePrice::query()->where('weight', '<=', $weight)->where('weight', '>=', $weight)->where('zone_id', $zone_id)->first();
//
//                if (!empty($zone)) {
//                    $cost += ($zone->price * $item->quantity);
//                }
//            }
//        }
//
//        return ['success' => true, 'zone_id' => $zone_id, 'price' => $cost];
//
//    }

    function shipping_cost_for_product(Address $address, Product $product, $quantity = 1)
    {

        $country = $address->country()->first();

        if (empty($country)) {
            return ['success' => false];
        }
        $zone_id = $country->zone_id;
        $cost = 0;

        if (!empty($product)) {
            $weight = $product->weight;
            $zone = ZonePrice::query()->where('weight', '<=', $weight)->where('weight', '>=', $weight)->where('zone_id', $zone_id)->first();

            if (!empty($zone)) {
                $cost += ($zone->price * $quantity);
            }
        }

        return ['success' => true, 'zone_id' => $zone_id, 'price' => $cost];

    }

    function payment_stripe($amount, $cardId, $orderId = null, $order_payment_id = null)
    {
        $card = Card::query()->where('card_id', $cardId)->first();

        if (get_setting('stripe_sandbox_mode') != 1) {
            $sk = get_setting('strip_secret');
        } else {
            $sk = get_setting('strip_secret_test');
        }
        Stripe::setApiKey($sk);
        $payment = new StripeClient($sk);
        $amount = intval($amount * 100);
        $order = Order::find($orderId);
        try {
            $result = $payment->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $card->stripe_cust_id,
                'confirm' => true,
                'payment_method_types' => ['card'],
                'payment_method' => $cardId
            ]);

            if (isset($orderId)) {


                $order->payment_status = $result->status == 'succeeded' ? 'paid' : "failed";
                $order->status = $result->status == 'succeeded' ? Order::$processing : Order::$pending_payment;
                $order->payment_method = "stripe";
                $order_payment = new OrderPayment();
                if (!empty($order_payment_id)) {
                    $order_payment = OrderPayment::find($order_payment_id);
                }
                $order_payment->order_id = $orderId;
                $order_payment->card_id = $card->id;
                $order_payment->user_id = auth('api')->id();
                $order_payment->amount = $amount / 100;
                $order_payment->payment_method = 'stripe';
                $order_payment->payment_details = json_encode($result);
                $order_payment->status = $result->status == 'succeeded' ? 'captured' : "voided";

                $order_payment->save();
                if ($result->status == 'succeeded') {
                    $order->status = Order::$processing;
                    $order->payment_status = 'paid';
                    $order->save();
                    $order_products = OrdersProducts::query()->where('order_id', $orderId)->get();
                    foreach ($order_products as $order_product) {
                        $product = Product::query()->where('id', $order_product->product_id)->first();
                        if (!empty($product)) {
                            $product->quantity = $product->quantity - $order_product->quantity;
                            $product->save();
                        }
                    }

                } else {
                    $order->status =  Order::$failed;
                    $order->payment_status =  Order::$payment_status_failed;
                    $order->save();
                }
            }
            $order->save();
        } catch (\Exception $exception) {
            $order->status = Order::$failed;
            $order->payment_status = Order::$payment_status_failed;
            $order->save();
            return  ['success'=>false , 'message'=>$exception->getMessage()];
        }


           return  ['success'=>true ];
    }

    function payment_paypal($amount, $orderId)
    {
        $order = Order::query()->where('id', $orderId)->first();
        $order->payment_method = 'paypal';
        $order->status = "pending_payment";
        $order->save();
        $order_payment = OrderPayment::query()
            ->where('user_id', auth('api')->id())
            ->where('status', 'pending')
            ->where('order_id', $orderId)->first();
        if (empty($order_payment)) {
            $order_payment = new OrderPayment();
        }
        $order_payment->order_id = $orderId;
        $order_payment->user_id = auth('api')->id();
        $order_payment->card_id = null;
        $order_payment->amount = $amount;
        $order_payment->status = "pending";
        $order_payment->payment_method = "paypal";
        $order_payment->save();
        return $order_payment;
    }

    function payment_wallet($user_id, $amount, $type, $status = 'pending', $created_by, $created_by_id, $order_id = null, $order_payment_id = null, $note = null, $input_files = null)
    {
        // status = enum('approve', 'pending', 'cancelled')
        // type enum('refund', 'order', 'withdraw', 'amount')
        $user_wallet = new UserWallet();
        $user_wallet->user_id = $user_id;
        $user_wallet->amount = $amount;
        $user_wallet->type = $type;
        $user_wallet->status = $status;

        $order_payment = null;
        $user_wallet->order_id = $order_id;
        if (!empty($order_payment_id)) {
            $order_payment = OrderPayment::find($order_payment_id);

        } else {
            $order_payment = new OrderPayment();

        }


        $order_payment->user_id = $user_id;
        $order_payment->order_id = $order_id;
        $order_payment->amount = $amount;
        if ($type == UserWallet::$withdraw) {
            if ($order_payment->amount > 0) {
                $order_payment->amount = $order_payment->amount * -1;
            }
        }
        $user_wallet->files = $order_payment->files;
        if (empty($order_payment_id))
            $order_payment->payment_method = OrderPayment::$wallet;
        if ($status == UserWallet::$approve) {
            $order_payment->status = OrderPayment::$captured;
        } else {
            $order_payment->status = OrderPayment::$pending;
        }

        if (isset($order_payment_id)) {
            $user_wallet->order_payment_id = $order_payment_id;
        }

        if (isset($note)) {
            $user_wallet->note = $note;
        }
        $user_wallet->create_by_type = $created_by;
        $user_wallet->create_by_id = $created_by_id;
//        if ($created_by == Admin::class && $status=='pending') {
//            $user_wallet->status = UserWallet::$approve;
//        }
        if ($type == UserWallet::$withdraw) {
            if ($user_wallet->amount > 0) {
                $user_wallet->amount = $user_wallet->amount * -1;
            }
        }


        $user_wallet->save();
        $user_wallet_id = $user_wallet->id;
        $files = [];
        if (!empty($input_files) && request()->hasFile($input_files)) {
            try {

                foreach (request()->file($input_files) as $file) {
                    $files[] = Storage::disk('public')->putFile('payment/wallet/' . $user_wallet->id . '/', $file);

                }
            } catch (\Exception $e) {
            }
            $user_wallet->files = json_encode($files);
            $user_wallet->save();
        }
        if (!empty($order_payment)) {
            $order_payment->files = json_encode($files);
            $order_payment->save();
            $user_wallet->order_payment_id = $order_payment->id;
            $user_wallet->save();
        }
        if (!empty($order_id) && $type != UserWallet::$refund) {
            $order = Order::query()->where('id', $order_id)->first();
            $last_status = ['status' => $order->status, 'payment_status' => $order->payment_status];

            $user_wallet = UserWallet::query()->where('status', 'approve')->where('order_id', $order_id)->sum('amount');
            $user_wallet_count = UserWallet::query()->where('status', 'approve')->where('order_id', $order_id)->count();
            if ($user_wallet >= 0 && $user_wallet_count != 0 && !empty($order)) {
                $order->payment_status = Order::$payment_status_paid;
                if (in_array($order->status, [Order::$on_hold, Order::$pending_payment])) {
                    $order->status = Order::$processing;
                }
            } else {
                $order->payment_status = Order::$payment_status_unpaid;
            }
            $order->save();
            if (!empty($order->seller_id) && $order->payment_status == Order::$payment_status_paid) {
                $this->calculate_eranings($order->created_at->format('Y'), $order->created_at->format('m'), $order->seller_id);
            }
            if ($last_status['payment_status'] != $order->payment_status && !empty($order->seller_commission)) {
                $receivers['admins'] = Admin::query()->where('status', 1)->get();
                $data = [
                    'title' => trans('backend.notifications.order_is_paid'),
                    'body' => trans('backend.notifications.the_order_is_paid', ['number' => $order->uuid])
                ];
                $this->sendNotification($receivers, 'order_is_paid', null, $data, Order::class, $order->id);
                if (!empty($order->seller_id)) {
                    $receivers = null;
                    $receivers['seller'] = Seller::query()->where('id', 1)->get();
                    $data = [
                        'title' => trans('backend.notifications.order_is_paid'),
                        'body' => trans('backend.notifications.seller_order_is_paid', ['number' => $order->uuid, 'commission' => ($order->seller_commission)])
                    ];
                    $this->sendNotification($receivers, 'order_is_paid', $data, null, Order::class, $order->id);
                }
            }
        }
        $order->refresh();
        $bcc_emails = [];
        if (!empty(get_setting('order_notifications_receivers'))) {
            $bcc_emails = json_decode(get_setting('order_notifications_receivers'));
        }

        if (!empty($order->seller_id)) {
            $seller = Seller::find($order->seller_id);
            if (!empty($seller) && !empty($seller->email)) {
                $bcc_emails[] = $seller->email;
            }
        }


        if (!empty($order_id) && strtotime($order->created_at) < time() - 100) {
            Mail::to($order->user->email)
                ->bcc($bcc_emails)
                ->later(0, new OrderUserMail("payment", ['order' => $order], 'statement', ['payment_recode' => $user_wallet_id]));

        }

        return $user_wallet;

    }

}
