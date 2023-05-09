<?php

namespace App\Traits;


use App\Mail\OrderUserMail;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Card;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsages;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\ProductsPackages;
use App\Models\ProductsSerialNumber;
use App\Models\ProductStockStatus;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

trait OrderTrait
{
//    use SellerTrait;
    use StripeTrait;
    use NotificationTrait;
    use ShippingTrait;
    use PaymentTrait;
    use PaypalTrait;
    use EraningsTrait;

    function generate_order_code()
    {
        $uuid = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
        if (Order::where('uuid', $uuid)->first()) {
            return $this->generate_order_code();
        }
        return $uuid;
    }

    function generateQrCode($order_id)
    {
        return \QrCode::size(50)->generate($order_id);
    }

    /*
     * parameters :
     * 1-user id
     * 2-address id
     * 3-array from product when send array [ [sku , quantity] ] is empty create product at carts
     * 4-payment method a-stripe b-paypal c-transfer(wallet) d-stripe link
     * 5-type has 2 types Order or Proforma
     * 6-shipping method a-dhl b-ups c-aramex d-fedex
     * 7-seller id can be null
     * 8-seller id
     * 9-files =>for payment method when type is transfer
     * 10-note
     * 11-coupon code
     * 12-card_id is requirement when payment method is stripe
     * */
    function update_order($order_uuid, $userId, $address_id, $products, $payment_method, $type = 'order', $shipping_method = 'dhl', $seller_id = null, $files = [], $note = null, $coupon_code = null, $shipment_description = null, $shipment_value = null, $status = null)
    {
        $address = Address::query()->where('id', $address_id)->first();
        #region update order
        $order = Order::query()->where('uuid', $order_uuid)->first();
        $last_status = ['status' => $order->status, 'payment_status' => $order->payment_status];

        $old_price = $order->total;
        $order->user_id = $userId;
        $order->address_id = $address_id;
        $order->shipping_method = $shipping_method;
        $order->payment_method = $payment_method;
        $order->payment_status = 'unpaid';
        $order->type = $type;
//        $order->shipping = $order->shipping_method;
        $old_status = $order->status;
        if ($type == Order::$proforma) {
            $order->status = Order::$proforma;
        } else {
            $can_change_status = false;
            if ($order->status == 'on_hold' && in_array($status, ['on_hold', 'pending_payment', 'processing', 'completed', 'canceled', 'proforma'])) {
                $can_change_status = true;
            } else if ($order->status == 'pending_payment' && in_array($status, ['pending_payment', 'processing', 'completed', 'canceled', 'proforma'])) {
                $can_change_status = true;
            } else if ($order->status == 'processing' && in_array($status, ['processing', 'completed', 'refunded', 'proforma'])) {
                $can_change_status = true;
            } else if ($order->status == 'completed' && in_array($status, ['completed', 'refunded', 'proforma'])) {
                $can_change_status = true;
            }
            if ($can_change_status) {
                $order->status = empty($status) ? $order->status : $status;
            }
        }
        $order->has_coupon = 0;
        $order->coupon_value = 0;
        $order->country_id = $address->country_id;
        $order->city = $address->city;
        $order->address = $address->address;
        $order->postal_code = $address->postal_code;
        $order->phone = $address->phone;
        $order->seller_id = $seller_id;
        $order->shipment_description = $shipment_description;
        $order->shipment_value = $shipment_value;
        $order->save();
        #endregion

        $total = 0;
        $total_without_shipping = 0;
        $total_shipping_cost = 0;
        $total_quantity = 0;
        $list_stripe_items = [];
        $can_apply_coupon = true;
        $coupon_value = 0;
        $coupon = null;
        $discount_value_order = 0;
        $products_ids = [];
        $free_shipping = false;
        $total_price = 0;
        foreach ($products as $item) {
            if (empty($item['price'])) {
                $product = Product::query()->where('sku', $item['id'])->first();
                $price = $this->product_price($product, $item['quantity']);
            } else {
                $price = $item['price'];
            }
            $total_price += $price * $item['quantity'];
        }
        $shipping_cost_old = $order->shipping;
        if (get_setting('free_shipping_cost') <= $total_price) {
            $free_shipping = true;
            $order->shipping = 0;
            $order->save();

        }

        $tweight = 0;
        $tweightShipping = 0;
        $payment_products_data = [];
        foreach ($products as $item) {
            $array = [];
            $shipping_cost = 0;
            $product = Product::query()->where('sku', $item['id'])->first();
            $array['id'] = $item['id'];
            if (empty($product->stripe_id) && $payment_method == Order::$stripe_link) {
                $product_stripe = $this->create_product($product->id, $product->title, $product->summary_name, media_file($product->image));
                $product->stripe_id = $product_stripe->id;
            }

            if (!empty($product)) {
                $new_item = false;
                if (OrdersProducts::query()->whereNUll('parent_id')->where('order_id', $order->id)->where('product_id', $product->id)->count() == 0) {
                    $new_item = true;
                }
                $product_order = OrdersProducts::query()->whereNUll('parent_id')->where('order_id', $order->id)->where('product_id', $product->id)->firstOrCreate();
                if ($new_item == false) {
                    $array['old_quantity'] = $product_order->quantity;
                    $array['old_price'] = $product_order->price / $product_order->quantity;
                }
                $array['new_item'] = $new_item;
                $array['product'] = $product;
                $array['product_order_id'] = $product_order->id;

                $product_order->order_id = $order->id;
                $product_order->product_id = $product->id;
                $products_ids [] = $product->id;
                $quantity_availability = $item['quantity'];
                if (empty($item['price'])) {
                    $price = $this->product_price($product, $item['quantity']);
                } else {
                    $price = $item['price'];
                }
                $array['new_quantity'] = $item['quantity'];
                $array['new_price'] = $price;

                #region has packages
                $packege = ProductsPackages::query()
                    ->where('from', '<=', $quantity_availability)
                    ->where('to', '>=', $quantity_availability)
                    ->where('product_id', $product->id)
                    ->first();
                if (!empty($packege)) {
                    $product_order->has_package = 1;
                    $product_order->package_price = $packege->price;
                } else {
                    $product_order->has_package = 0;
                }
                #endregion

                #region original price
                $product_order->original_price = $this->price_product($product, $item['quantity']);
                #endregion

                #region change quantity && change weight
                $quantity_availability = $item['quantity'];
                $product_order->quantity = $quantity_availability;
                $weight = $product->weight * $product_order->quantity;
                $product_order->weight = $weight;
                $product_order->attributes = null;
                $product_order->coupon_discount = null;
                if ($product->is_free_shipping != 1) {
                    $tweightShipping += $weight;
                }
                $tweight += $weight;
                #endregion

                #region price
                $product_order->price = $quantity_availability * $price;
                $total_without_shipping += $quantity_availability * $price;
                $total += ($quantity_availability * $price);
                $total_quantity += $product_order->quantity;
                #endregion

                #region create new stripe price
                if ($product_order->quantity != 0 && $payment_method == Order::$stripe_link) {
                    $product_order->stripe_price_id = $this->create_price($product->stripe_id, ((($quantity_availability * $price) + $shipping_cost) / $product_order->quantity))->id;
                    $list_stripe_items[] = ['price' => $product_order->stripe_price_id, 'quantity' => $quantity_availability];
                }
                #endregion

                $product_order->save();

                #region bundle product
                if ($product->is_bundle == 1 && $new_item) {
                    $bundle_products = Product::query()->whereIn('id', json_decode($product->bundled))->get();
                    foreach ($bundle_products as $bundle_product) {
                        $product_order_bundle = new OrdersProducts();
                        $product_order_bundle->order_id = $order->id;
                        $product_order_bundle->product_id = $bundle_product->id;
                        $product_order_bundle->quantity = $product_order->quantity;
                        $product_order_bundle->price = 0;
                        $product_order_bundle->shipping_cost = 0;
                        $product_order_bundle->color_id = null;
                        $product_order_bundle->attributes = null;
                        $product_order_bundle->has_package = false;
                        $product_order_bundle->package_price = 0;
                        $product_order_bundle->original_price = 0;
                        $product_order_bundle->coupon_discount = 0;
                        $product_order_bundle->parent_id = $product_order->id;
                        $product_order_bundle->save();
                    }
                }
                #endregion
                $payment_products_data[] = $array;
            }


        }
        if ($free_shipping) {
            $shipping_cost = 0;
        } else {
            $shipping_cost = $this->shipping_cost($address->country_id, $tweightShipping, false, $shipping_method)['shipping'];
        }


        if (!empty($status)) {
            $order->status = $status;
        }

        $order->shipping = $shipping_cost;
        $order->total = $total + $shipping_cost;
        if (isset($note)) {
            $order->note = $note;
        }

        $order->save();

        $files_data = [];
        $details_new_changes = [];
        $deleted_product_ids = OrdersProducts::query()->whereNotIn('product_id', $products_ids)->whereNUll('parent_id')->where('order_id', $order->id)->pluck('orders_products.product_id')->toArray();


        if (!empty($deleted_product_ids)) {
            $deleted_products = Product::query()->whereIn('id', $deleted_product_ids)->get();
            foreach ($deleted_products as $dp) {
                $details_new_changes[] = 'delete item : ' . $dp->title;

            }
        }
        if ($order->type == Order::$order && $order->status == Order::$processing) {
            $new_price = $shipping_cost;
            foreach ($payment_products_data as $ppd) {
                $new_price += $ppd['new_price'] * $ppd['new_quantity'];
                if ($ppd['new_item']) {
                    $details_new_changes[] = "add new item : " . $ppd['product']->title;
                    $op = OrdersProducts::query()->find($ppd['product_order_id']);
                    if ($old_status == $order->status) {
                        $this->set_serial_numbers($op, $ppd['new_quantity']);
                    }
                } else {
                    if ($ppd['new_price'] != $ppd['old_price']) {
                        $details_new_changes[] = "change product price : " . $ppd['product']->title;
                    }
                    if ($ppd['new_quantity'] != $ppd['old_quantity']) {
                        $details_new_changes[] = "change order quantity : " . $ppd['product']->title;
                    }
                    if (intval($ppd['new_quantity']) < intval($ppd['old_quantity'])) {
                        $this->refund_product_to_stock($order->id, $ppd['product_order_id'], $ppd['old_quantity'] - $ppd['new_quantity']);
                    } elseif (intval($ppd['new_quantity']) > intval($ppd['old_quantity'])) {
                        $op = OrdersProducts::query()->find($ppd['product_order_id']);
                        if ($old_status != $order->status) {
                            $this->set_serial_numbers($op, $ppd['new_quantity']);
                        } else {
                            $this->set_serial_numbers($op, $ppd['new_quantity'] - $ppd['old_quantity']);
                        }
                    } else {
                        $op = OrdersProducts::query()->find($ppd['product_order_id']);
                        if ($old_status != $order->status) {
                            $this->set_serial_numbers($op, $ppd['new_quantity']);
                        }
                    }

                }


            }#region  refund amount
            ;
            if ($old_price > $new_price) {
                /*
                 * 1.create new order payment status is refund .
                 * 2.create new user wallet .
                */
                #region 1.creat new order payment status is refund
                $order_payment_refund = new OrderPayment();
                $order_payment_refund->amount = $old_price - $new_price;
                $order_payment_refund->user_id = $order->user_id;
                $order_payment_refund->order_id = $order->id;
                $order_payment_refund->card_id = null;
                $order_payment_refund->status = OrderPayment::$captured;
                $order_payment_refund->stripe_url = null;
                $order_payment_refund->payment_method = $order->payment_method;
                $order_payment_refund->payment_details = json_encode($details_new_changes);
                $order_payment_refund->save();
                #endregion

                #region 2.create new user wallet .
                $user_wallet = new UserWallet();
                $user_wallet->user_id = $userId;
                $user_wallet->order_id = $order->id;
                $user_wallet->order_payment_id = $order_payment_refund->id;
                $user_wallet->amount = $old_price - $new_price;
                $user_wallet->type = UserWallet::$refund;
                $user_wallet->status = UserWallet::$approve;
                $user_wallet->files = json_encode([]);
                $user_wallet->create_by_type = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
                $user_wallet->create_by_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
                $user_wallet->save();
                #endregion
            }
            #endregion
            #region add credit to user
            else if ($old_price < $new_price) {
                $order_payment = OrderPayment::query()
                    ->where('order_id', $order->id)
                    ->where('status', OrderPayment::$captured)
                    ->where('amount', '>', 0)->first();
                $user_wallet = new UserWallet();
                $user_wallet->user_id = $userId;
                $user_wallet->order_id = $order->id;
                $user_wallet->order_payment_id = empty($order_payment) ? null : $order_payment->id;
                $user_wallet->amount = $old_price - $new_price;
                $user_wallet->type = UserWallet::$order;
                $user_wallet->status = UserWallet::$approve;
                $user_wallet->files = json_encode([]);
                $user_wallet->create_by_type = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
                $user_wallet->create_by_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
                $user_wallet->save();
            }
            #endregion

        }


        $seller = Seller::find($seller_id);
        $order->seller()->associate($seller);
        $order->save();
        $order->refresh();
        $order_products = OrdersProducts::query()->whereNotIn('product_id', $products_ids)->where('order_id', $order->id)->get();
        foreach ($order_products as $order_product) {
            $this->refund_product_to_stock($order->id, $order_product->id);
        }
        $orders_product_ids = OrdersProducts::query()->whereNotIn('product_id', $products_ids)->whereNUll('parent_id')->where('order_id', $order->id)->pluck('orders_products.id')->toArray();
        OrdersProducts::query()->whereNotIn('product_id', $products_ids)->whereNUll('parent_id')->where('order_id', $order->id)->delete();
        OrdersProducts::query()->whereIn('parent_id', $orders_product_ids)->where('order_id', $order->id)->delete();
        $orderPriceAtWallet = UserWallet::query()->where('status', UserWallet::$approve)->where('order_id', $order->id)->where('amount', '<', '0')->sum('amount');
        if (($orderPriceAtWallet * -1) != $order->total) {
            $created_by = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : null);
            $created_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : null);
            UserWallet::query()->where('status', UserWallet::$approve)->where('order_id', $order->id)->where('amount', '<', '0')->update(['status' => UserWallet::$cancelled]);
            $this->payment_wallet($order->user_id, $order->total * -1, UserWallet::$order, UserWallet::$approve, $created_by, $created_id, $order->id);
        }
        if (!empty($coupon_code)) {
            $order = $this->coupon_apply($coupon_code, $order);
        }
        if (!empty($order->seller_id)) {
            $this->calculate_eranings($order->updated_at->year, $order->updated_at->month, $order->seller_id);
        }
        if ($last_status['payment_status'] != $order->payment_status && !empty($order->seller_commission)) {
            $receivers['admins'] = Admin::query()->where('status', 1)->get();
            $data = [
                'title' => trans('backend.notifications.order_is_paid'),
                'body' => trans('backend.notifications.the_order_is_paid', ['number' => $order->uuid])
            ];
            $this->sendNotification($receivers, 'order_is_paid', $data, null, Order::class, $order->id);
            if (!empty($order->seller_id)) {
                $receivers = null;
                $receivers['seller'] = Seller::query()->where('id', 1)->get();
                $data = [
                    'title' => trans('backend.notifications.order_is_paid'),
                    'body' => trans('backend.notifications.seller_order_is_paid', ['number' => $order->uuid, 'commission' => $order->seller_commission])
                ];
                $this->sendNotification($receivers, 'order_is_paid', $data, null, Order::class, $order->id);
            }
        }
        return ['order' => $order];

    }

    function create_orders($userId, $address_id, $products = [], $payment_method, $type = 'order', $shipping_method = 'dhl', $seller_id = null, $files = [], $note = null, $coupon_code = null, $card_id = null, $status = null, $shipment_value = null, $shipment_description = null, $currency_symbol = null, $exchange_rate = null)
    {

        if (isset($currency_symbol)) {
            $currency = Currency::query()->where('symbol', $currency_symbol)->first();
        } else {
            $currency = Currency::query()->where('code', 'USD')->first();
        }
        if (empty($exchange_rate)) {
            $exchange_rate = $currency->value;
        }
        $address = Address::query()->where('id', $address_id)->first();


        #region order products
        $total = 0;
        $stripe_url = '';
        $total_without_shipping = 0;
        $total_shipping_cost = 0;
        $total_quantity = 0;
        $list_stripe_items = [];
        $can_apply_coupon = false;
        $coupon_value = 0;
        $total_weight = 0;
        $tweight = 0;
        //region get products id or get carts
        $data_products = [];
        $total_price = 0;
        $country_id = $address->country_id;
        if (empty($products) || $products == []) {
            $products = Cart::query()->where('carts.user_id', $userId)->select('product_id', 'quantity', 'note')->get();
            if ($products->count() == 0) {
                return false;
            }
            foreach ($products as $item) {
                $product = Product::query()->where('id', $item->product_id)->first();
                $countries_blocked = empty($product->blocked_countries) ? [] : $product->blocked_countries;
                if (!in_array($country_id, $countries_blocked)) {
                    if (!empty($product) && ($product->quantity >= $item['quantity'] || $type == Order::$proforma)) {
                        if ($product->is_free_shipping == false) {
                            $total_weight += ($product->weight * $item['quantity ']);
//                            $pre_shipping_cost_obj = $this->shipping_cost($address->country_id, ($product->weight * $item['quantity']), false, $shipping_method);
                            //decrease quantity
                            if (isset($pre_shipping_cost_obj['shipping'])) {
//                                $pre_shipping_cost = $pre_shipping_cost_obj['shipping'];
                            } else {
//                                $pre_shipping_cost = 0;
                            }
                        } else {
                            $pre_shipping_cost = 0;
                        }
                        $pro_price = $this->price_product($product, $item['quantity']);
                        $total_price += $pro_price['price'] * $item['quantity'];
                        $weight = ($product->weight * $item['quantity']);
                        $total_weight += $weight;
                        $productOrder = ['product' => $product, 'price' => $pro_price, 'weight' => $weight, 'quantity' => $item['quantity'], 'shipping_cost' => 0];
                        $productOrder['serial_number'] = "";
                        try {
                            $serial_number = $item->note;

                            if (!empty($serial_number))
                                $serial_number = json_decode($serial_number, true);
                            if (!empty($serial_number['serial_number'])) {
                                $serial_number = $serial_number['serial_number'];
                            }

                            $productOrder['serial_number'] = json_encode($serial_number);
                        } catch (\Exception $exception) {

                        }
                        $data_products[] = $productOrder;
                    }
                }
            }
            //empty cart ...
            Cart::query()->where('carts.user_id', $userId)->delete();
        } else {
            foreach ($products as $item) {
                $product = Product::query()->where('sku', $item['id'])->first();
                $countries_blocked = empty($product->blocked_countries) ? [] : $product->blocked_countries;
                if (!in_array($country_id, $countries_blocked)) {

                    if (!empty($product) && $product->quantity >= $item['quantity']) {
                        //decrease quantity
                        if ($product->is_free_shipping == false) {
                            $total_weight += ($product->weight * $item['quantity']);
                        }
                        $total_price += $item['price'] * $item['quantity'];
                        $obj = [
                            'price' => $item['price'],
                            'has_packages' => false,
                            'before_packages' => 0,
                            'package_price' => 0,
                            'has_discount' => false,
                            'before_discount' => 0,
                            'discount_value' => 0,

                        ];
                        $data_products[] = ['product' => $product, 'price' => $obj, 'weight' => ($product->weight * $item['quantity']), 'quantity' => $item['quantity'], 'shipping_cost' => 0];

                    }
                } else {
                    throw new \Exception('address is not available');
                }
            }

        }

        $products = $data_products;

        if (count($products) == 0) {
            return false;
        }

        #region create order
        $order = new Order();
        $uuid = $this->generate_order_code();
        while (true) {
            if (Order::query()->where('uuid', $uuid)->count() != 0) {
                $uuid = $this->generate_order_code();
            } else {
                break;
            }
        }
        $order->uuid = $uuid;
        $order->user_id = $userId;
        $order->address_id = $address_id;
        $order->shipping_method = $shipping_method;
        $order->payment_method = $payment_method;
        $order->payment_status = 'unpaid';
        $order->type = $type;
        $order->total = 0;
        $order->shipping = 0;
        $order->status = !empty($status) ? $status : Order::$on_hold;
        if ($type == Order::$proforma) {
            $order->status = Order::$proforma;
            $order->payment_status = 'unpaid';
            $order->payment_method = Order::$transfer;
        }

        $order->has_coupon = 0;
        $order->coupon_value = 0;
        $order->country_id = $address->country_id;
        $order->city = $address->city;
        $order->currency_id = $currency->id;
        $order->exchange_rate = number_format($exchange_rate, 2);
        $order->address = $address->address;
        $order->postal_code = $address->postal_code;
        $order->phone = $address->phone;
        $order->shipment_value = $shipment_value;
        $order->shipment_description = $shipment_description;
        $order->seller_id = $seller_id;
        if (isset($note)) {
            $order->note = $note;
        }
        $order->save();
        #endregion

        //endregion
        $free_shipping = false;

        if (get_setting('free_shipping_cost') <= $total_price) {
            $free_shipping = true;
        }

        //region products
        foreach ($products as $item) {

            $shipping_cost = $free_shipping ? 0 : $item['shipping_cost'];

            $product = $item['product'];

            #region create product at stripe product ..
            if (empty($product->stripe_id) && $payment_method == Order::$stripe_link) {
                $product_stripe = $this->create_product($product->id, $product->title, $product->summary_name, media_file($product->image));
                $product->stripe_id = $product_stripe->id;
            }
            #endregion


            $product_order = new OrdersProducts();
            $product_order->order_id = $order->id;
//            $id = $product->id;

            $product_order->product_id = $product->id;

            $quantity_availability = $item['quantity'];

            $total_weight += ($product->weight * $item['quantity']);
            /*
             * price
             * has_packages
             * before_packages
             * package_price
             * has_discount
             * before_discount
             * */
            $obj_price = $item['price'];
            $price = $obj_price['price'];
            $product_order->price = $obj_price['price'] * $quantity_availability;
            $product_order->quantity = $quantity_availability;
            $product_order->has_package = $obj_price['has_packages'];
            $product_order->package_price = $obj_price['package_price'];
            $product_order->original_price = $obj_price['before_packages'];
            $total_without_shipping += $quantity_availability * $price;
            $total += ($quantity_availability * $price);
            $total_quantity += $product_order->quantity;
            if ($product_order->quantity != 0 && $payment_method == Order::$stripe_link) {
                $product_order->stripe_price_id = $this->create_price($product->stripe_id, ((($quantity_availability * $price) + $shipping_cost) / $product_order->quantity))->id;
                $list_stripe_items[] = ['price' => $product_order->stripe_price_id, 'quantity' => $quantity_availability];
            }
            $product_order->bundles_products_id = $product->bundled;
            $product_order->serial_number = isset($item['serial_number']) ? $item['serial_number'] : "";
            $product_order->weight = $item['weight'];
            $tweight += $product_order->weight;
            $product_order->save();
            #region bundle product
            if ($product->is_bundle == 1) {
                $bundle_products = Product::query()->whereIn('id', json_decode($product->bundled))->get();
                foreach ($bundle_products as $bundle_product) {
                    $product_order_bundle = new OrdersProducts();
                    $product_order_bundle->order_id = $order->id;
                    $product_order_bundle->product_id = $bundle_product->id;
                    $product_order_bundle->quantity = $product_order->quantity;
                    $product_order_bundle->price = 0;
                    $product_order_bundle->shipping_cost = 0;
                    $product_order_bundle->color_id = null;
                    $product_order_bundle->attributes = null;
                    $product_order_bundle->has_package = false;
                    $product_order_bundle->package_price = 0;
                    $product_order_bundle->original_price = 0;
                    $product_order_bundle->coupon_discount = 0;
                    $product_order_bundle->parent_id = $product_order->id;
                    $product_order_bundle->save();
                    if ($type == Order::$order && $status == Order::$processing) {
                        $this->set_serial_numbers($product_order_bundle);
                    }

                }
            }
            if ($type == Order::$order) {
                $this->set_serial_numbers($product_order->refresh());
            }
            #endregion
        }
        //endregion

        $order->total = $total;
        $total_shipping_cost = 0;

        if (!$free_shipping) {
            $total_shipping_cost = $this->shipping_cost($address->country_id, $tweight, null, $shipping_method)['shipping'];
//            dd($total_shipping_cost);
        }
        $order->shipping = $free_shipping ? 0 : $total_shipping_cost;
        $order->weight = $tweight;
        $order->total += $order->shipping;
        $order->save();
        #region apply coupon
        if (!empty($coupon_code) && isset($coupon_code)) {
            $order = $this->coupon_apply($coupon_code, $order);
            $order->refresh();
        }
        #endregion
        if ($order->type == Order::$order) {
            $order_payment = new OrderPayment();
            if (!empty($list_stripe_items) && $payment_method == Order::$stripe_link) {
                $stripe_url = $this->create_payment_link($list_stripe_items, $order->id);

                $order_payment->order_id = $order->id;
                $order_payment->user_id = $order->user_id;
                $order_payment->amount = $order->total;
                $order_payment->payment_method = Order::$stripe_link;
                $order_payment->card_id = null;
                $order_payment->status = OrderPayment::$pending;
                $order_payment->stripe_url = $stripe_url['link'];
                $order_payment->payment_details = json_encode($stripe_url);
                $stripe_url = $stripe_url['link'];
                $order_payment->save();
                $created_by = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : null);
                $created_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : null);
                $this->payment_wallet($order->user_id, $order->total * -1, 'order', 'approve', $created_by, $created_id, $order->id, $order_payment->id);
                $this->payment_wallet($order->user_id, $order->total, 'order', 'pending', $created_by, $created_id, $order->id, $order_payment->id);;
            } else {
                if ($payment_method == Order::$paypal) {
                    //region order payment
                    $order_payment = new OrderPayment();
                    $order_payment->order_id = $order->id;
                    $order_payment->user_id = $order->user_id;
                    $order_payment->amount = $order->total;
                    $order_payment->payment_method = Order::$paypal;
                    $order_payment->card_id = null;
                    $order_payment->status = OrderPayment::$pending;
                    $order_payment->stripe_url = null;
                    $order_payment->payment_details = "";
                    $order->status = Order::$pending_payment;
                    $created_by = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
                    $created_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
                    $this->payment_wallet($order->user_id, $order->total * -1, 'order', 'approve', $created_by, $created_id, $order->id, $order_payment->id);

                    //endregion
                } elseif ($payment_method == Order::$stripe) {
                    $card = Card::query()->where('card_id', $card_id)->first();
                    $order_payment->order_id = $order->id;
                    $order_payment->user_id = $order->user_id;
                    $order_payment->amount = $order->total;
                    $order_payment->payment_method = Order::$transfer;
                    $order_payment->card_id = $card?->id;
                    $order_payment->status = OrderPayment::$pending;
                    $order_payment->stripe_url = null;
                    $order_payment->payment_details = "";
                    $order_payment->save();
                    $order->status = Order::$processing;

                    $res = $this->payment_stripe($order->total, $card_id, $order->id, $order_payment->id);
                    Log::info($res);
                    if ($res['success']) {
                        $created_by = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
                        $created_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
                        $this->payment_wallet($order->user_id, $order->total * -1, 'order', 'approve', $created_by, $created_id, $order->id, $order_payment->id);
                        $this->payment_wallet($order->user_id, $order->total, 'order', 'approve', $created_by, $created_id, $order->id, $order_payment->id);
                    } else {
                        $order_payment->payment_details = $res['message'];
                        $order_payment->status = OrderPayment::$captured;;
                        $order_payment->save();

                        $order->status = Order::$failed;
                        $order->payment_status = Order::$payment_status_failed;
                        $order->save();

                        return ['order' => $order, 'success' => false, 'message' => $res['message']];

                    }

                } elseif ($payment_method == Order::$transfer) {
                    $status_waller = UserWallet::$pending;
                    $order_payment->order_id = $order->id;
                    $order_payment->user_id = $order->user_id;
                    $order_payment->amount = $order->total;
                    $order_payment->payment_method = Order::$transfer;
                    $order_payment->card_id = null;
                    $order_payment->status = OrderPayment::$pending;
                    $order_payment->stripe_url = null;
                    $order_payment->payment_details = "";
                    $files_data = [];
                    if (!empty($files)) {

                        foreach ($files as $file) {

                            $path = Storage::disk('public')->putFile('payment/orders/' . $order->id . '/', $file);
                            $files_data[] = $path;
                        }
                        $status_waller = UserWallet::$pending;

                    } else {
                        $order_payment->status = OrderPayment::$captured;
                        $status_waller = UserWallet::$approve;
                    }
                    $order_payment->files = json_encode($files_data);
                    $created_by = auth('api')->check() ? User::class : (auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : null));
                    $created_id = auth('api')->check() ? auth('api')->id() : (auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : null));
                    $order_payment->save();

                    $this->payment_wallet($order->user_id, -1 * $order->total, 'order', UserWallet::$approve, $created_by, $created_id, $order->id, $order_payment->id);
                    if (!empty($files)) {
                        $this->payment_wallet($order->user_id, $order->total, 'order', $status_waller, $created_by, $created_id, $order->id, $order_payment->id, '', 'payment_files');

                    }
                }
                $order_payment->save();
            }
        }
        #endregion

        $seller = Seller::find($seller_id);
        if (empty($seller)) {
            $user = User::find($userId);
            if (!empty($user) && !empty($user->seller_id)) {
                $seller = Seller::query()->where('id', $user->seller_id)->first();
            }

        }
        $order->seller()->associate($seller);
        $order->save();
        if ($order->type == Order::$order) {
            if (!empty($order_payment->stripe_url)) {
                $order->stripe_url = $stripe_url;
            }
        } else {
            $order_payment = null;
        }
        $order->refresh();
        $order->total_in_sale_currency = $order->total * $order->exchange_rate;
        $order->save();
        $order->refresh();
        if (!empty($order->seller_id)) {
            $this->calculate_eranings($order->created_at->year, $order->created_at->month, $order->seller_id);
        }
        try {
            if (!empty($order->user_id)) {
                if (!auth('admin')->check() || true) {
                    $order->uuid = Order::generateUUID(10);
                    $receivers['admins'] = Admin::query()->where('status', 1)->get();
                    $receivers['seller'] = Seller::query()->where('status', 1)->where('id', $order->seller_id)->get();

                    if (auth('api')->check())
                        $receivers['users'] = User::where('id', auth('api')->id())->get();

                    $name = auth('admin')->check() ? auth('admin')->user()->name : (auth('api')->check() ? auth('api')->user()->name : auth('seller')->user()->name);
                    $data = [
                        'title' => 'New order Created',
                        'body' => $name . ' Created a new order'
                    ];

                    //notifications
                    if (get_setting('order_notifications')) {
                        $this->sendNotification($receivers, 'new_order', $data, '/', Order::class, $order->id,);
                    }
                    //email
                    $details['order'] = $order;
                    $details['title'] = 'New order Created';
                    $details['content'] = $name . ' Created a new order';
                    $details['button'] = 'Show order';

                    $bcc_emails = json_decode(get_setting('order_notifications_receivers'));


//                    Mail::to()
//                        ->queue(new OrderMail('New order Created', $details));
//                    $details['title'] = 'Your order received';
                    if (!empty($order->seller_id)) {
                        $seller = Seller::find($order->seller_id);
                        if (!empty($seller) && !empty($seller->email)) {
                            $bcc_emails[] = $seller->email;
//                            Mail::to($seller)
//                                ->queue(new OrderSellerMail($details['title'], $details));
                        }
                    }
                    try {
                        Mail::to($order->user->email)
                            ->bcc($bcc_emails)->later(0, new OrderUserMail($details['title'], $details, 'create'));

                    } catch (\Exception $exception) {
                        \Log::error("Create Order Mail Exception : " . $exception->getMessage());
                    }

                }
            }
            /*
            if (!empty($order->user_id)) {
                if (!auth('admin')->check()) {
                    $order->uuid = Order::generateUUID(10);
                    $receivers['admins'] = Admin::query()->where('status', 1)->get();
                    if (auth('api')->check())
                        $receivers['users'] = User::where('id', auth('api')->id())->get();

                    $name = auth('api')->check() ? auth('api')->user()->name : auth('seller')->user()->name;
                    $data = [
                        'title' => 'New order Created',
                        'body' => $name . ' Created a new order'
                    ];

                    //notifications
                    if (get_setting('order_notifications')) {
                        $this->sendNotification($receivers, 'new_order', $data, '/', Order::class, $order->id,);
                    }
                    //email
                    $details['order'] = $order;
                    $details['title'] = 'New order Created';
                    $details['content'] = $name . ' Created a new order';
                    $details['button'] = 'Show order';
                    $this->setMailConfigurations();
                    $bcc_emails = json_decode(get_setting('order_notifications_receivers'));


//                    Mail::to()
//                        ->queue(new OrderMail('New order Created', $details));
//                    $details['title'] = 'Your order received';
                    if (!empty($order->seller_id)) {
                        $seller = Seller::find($order->seller_id);
                        if (!empty($seller) && !empty($seller->email)) {
                            $bcc_emails[] = $seller->email;
//                            Mail::to($seller)
//                                ->queue(new OrderSellerMail($details['title'], $details));
                        }
                    }
                    try {
                        Mail::to($order->user->email)
                            ->bcc($bcc_emails)->later(0, new OrderUserMail($details['title'], $details));

                    } catch (\Exception $exception) {
                        \Log::error("Create Order Mail Exception : " . $exception->getMessage());
                    }

                }
            }
            */
        } catch (\Exception $exception) {
            Log::error("order : " . $exception->getMessage());
        }

        $order = $order->refresh();
        return ['order' => $order, 'success' => true, 'order_payment' => $order_payment];
    }

    function price_product(Product $product, $quantity = 1)
    {
        $obj = [
            'price' => 0,
            'has_packages' => false,
            'before_packages' => 0,
            'package_price' => 0,
            'has_discount' => false,
            'before_discount' => 0,
            'discount_value' => 0,

        ];
        $price = $product->price;

        if (!empty($product->sale_price)) {
            $price = $product->sale_price;
        }
        $obj['price'] = $price;
        $packages = ProductsPackages::query()->where('product_id', $product->id)
            ->where('from', '<=', $quantity)
            ->where('to', '>=', $quantity)
            ->first();
        if (!empty($packages)) {
            $obj['before_packages'] = $price;
            $obj['packages_price'] = $packages->price;
            $obj['has_packages'] = true;
            $price = $packages->price;
            $obj['price'] = $price;
        }

        if ($product->discount_type != Product::$none && (strtotime($product->end_date_discount) >= time() || empty($product->end_date_discount)) && strtotime($product->start_date_discount) <= time()) {
            $discount_val = $product->discount_value;
            $obj['has_discount'] = true;
            $obj['before_discount'] = $price;


            if ($product->discount_type == Product::$percent) {
                $price = $price - ($price * $discount_val / 100);
                $obj['discount_value'] = ($price * $discount_val / 100);
            } else {
                $price = $price - $discount_val;
                $obj['discount_value'] = $discount_val;
            }

        }
        $obj['price'] = $price;
        return $obj;
    }

    function coupon_apply($coupon_code, Order $order)
    {
        $coupon = Coupon::query()
            ->where('code', $coupon_code)
            ->where('status', 1)
            ->where('starts_at', '<=', date('Y-m-d H:i:s'))
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', date('Y-m-d h:i:s'));
            })
            ->where(function ($q) use ($order) {
                $q->where(function ($q2) use ($order) {
                    $q2->where('type', Coupon::$Order)
                        ->where('minimum_shopping', "<=", $order->total);
                })->orWhere('type', Coupon::$Product);
            })
            ->first();
        if (!empty($coupon)) {
            $coupon_used = CouponUsages::query()->where('coupon_id', $coupon->id)->count();
            $coupon_used_for_user = CouponUsages::query()->where('coupon_id', $coupon->id)->where('user_id', $order->user_id)->count();
            if (!empty($coupon) && $coupon->max_use > $coupon_used && $coupon->per_user > $coupon_used_for_user) {
                if ($coupon->type == Coupon::$Order) {
                    $total = $order->total - $order->shipping;
                    $order->coupon_id = $coupon->id;
                    $order->has_coupon = true;

                    if ($coupon->discount_type == Coupon::$Percentage) {
                        $order->coupon_value = $total * $coupon->discount / 100;
                    } else {
                        $order->coupon_value = $coupon->discount;
                    }
                    $order->total = $order->total - $order->coupon_value;
                    $order->save();
                } else {
                    $products = OrdersProducts::query()->where('order_id', $order->id)->get();
                    $total_coupon = 0;
                    foreach ($products as $item) {
                        if (in_array($item->product_id, $coupon->products_ids)) {
                            $price = $item->price;
                            if (Coupon::$Percentage == $coupon->discount_type) {
                                $item->coupon_discount = ($price) * $coupon->discount / 100;
                            } else {
                                $item->coupon_discount = $coupon->discount * $item->quantity;
                            }


                            $total_coupon += $item->coupon_discount;

                            $item->save();


                        }
                    }
                    $order->coupon_id = $coupon->id;
                    $order->has_coupon = true;
                    $order->coupon_value = $total_coupon;
                    $order->total = $order->total - $total_coupon;
                    $order->save();
                }
                $order->refresh();
                $CouponUsages = new CouponUsages;
                $CouponUsages->coupon_id = $coupon->id;
                $CouponUsages->user_id = $order->user_id;
                $CouponUsages->save();

                //region free shipping
                if ($coupon->free_shipping == 1) {
                    $order->total = $order->total - $order->shipping;
                    $order->shipping = 0;
                    $order->is_free_shipping = 1;
                    $order->save();
                    $order->refresh();
                }
                //endregion
            }
        }

        return $order;
    }

    function remove_coupon(Order $order)
    {
        $order->refresh();
        $order->has_coupon = false;
        $coupon_id = $order->coupon_id;
        $order->is_free_shipping = 0;
        $order->total += $order->coupon_value;
        $order->coupon_value = 0;
        $order->coupon_id = null;

        $CouponUsages = CouponUsages::query()->where(['user_id' => $order->user_id, 'coupon_id' => $coupon_id])->delete();
        $order->save();
        if (!empty($order->seller_id)) {
            $this->calculate_eranings($order->created_at->year, $order->created_at->month, $order->seller_id);
        }
        $order->refresh();
        return $order;
    }

    /*
     * 1-check order is completed ...
     * 1-1 if order is completed .
     * 1-1-1 change state to canceled .
     * 1-1-2 create replicate order and reback products to stock and add product quantity .
     * 1-1-3 the order replicate state is refund .
     * 1-1-4 if payment type is paypal or stripe
     * 1-1-4-1 if payment type is paypal : refund the money by paypal sdk (Paypal trait)
     * 1-1-4-2 if payment type is stripe : refund the money by stripe sdk (stripe trait)
     * 1-2 if not completed change status to canceled .
     * */


    function refund_order($order_id)
    {
        $order = Order::query()->where('id', $order_id)->first();
        if (empty($order)) {
            return ['success' => false, 'message' => trans('api.order.order_not_found')];
        }
        if (!empty($order->seller_id)) {
            $this->calculate_eranings($order->created_at->year, $order->created_at->month, $order->seller_id);
        }

        ProductsSerialNumber::query()->where('order_id', $order->id)->update([
            'order_product_id' => null,
            'order_id' => null,
        ]);

//        if ($order->status == Order::$on_hold || $order->status == Order::$completed || $order->status == Order::$processing || $order->status == Order::$pending_payment || $order->status == Order::$canceled) {
        $this->refund_statements_order($order_id, $order, true);
        $this->refund_product_to_stock($order_id);
        if ($order->payment_status == Order::$payment_status_paid) {

            #region refund payment method is paypal
            if ($order->payment_method == Order::$paypal) {
                $response = $this->refund_paypal($order_id);
                if ($response['success'] == true) {
                    $order->status = Order::$refunded;
                    $order->payment_status = Order::$payment_status_unpaid;
                    $order->save();
//                        $this->refund_product_to_stock($order_id);
                    return ['success' => true];
                }
            }
            #endregion

            #region refund payment method is stripe
            else if ($order->payment_method == Order::$stripe) {
                $response = $this->stripe_refund($order_id);
                if ($response['success'] == true) {
                    $order->status = Order::$refunded;
                    $order->payment_status = Order::$payment_status_unpaid;
                    $order->save();
//                        $this->refund_product_to_stock($order_id);
                    return ['success' => true];
                }
            }
            #endregion

            #region refund payment method is stripe line or transfer
            else {
                $user_wallet_payment = UserWallet::query()->where('order_id', $order_id)->where('status', 'approve')
                    ->where(function ($q) {
                        $q->where(function ($q2) {
                            $q2->where('type', 'order')->where('amount', '>', 0);
                        });
                        $q->whereIn('type', ['amount', 'withdraw', 'refund']);
                    })
                    ->sum('amount');
                UserWallet::query()->where('order_id', $order_id)->where('status', 'pending')->update(['status' => UserWallet::$cancelled]);

                $user_wallet = UserWallet::query()->where('order_id', $order_id)->first();
                $order_payment = new OrderPayment();
                $order_payment->order_id = $order_id;
                $order_payment->user_id = $order->user_id;
                $order_payment->amount = $user_wallet_payment;
                $order_payment->status = OrderPayment::$captured;
                $order_payment->payment_method = OrderPayment::$transfer;
                $order_payment->save();
                $new_user_wallet = new UserWallet();
                $new_user_wallet->user_id = $user_wallet->user_id;
                $new_user_wallet->create_by_type = auth('admin')->check() ? Admin::class : (auth('api')->check() ? User::class : Seller::class);
                $new_user_wallet->create_by_id = auth('admin')->check() ? auth('admin')->id() : (auth('api')->check() ? auth('api')->id() : auth('seller')->id());
                $new_user_wallet->amount = $user_wallet_payment;
                $new_user_wallet->status = UserWallet::$approve;
                $new_user_wallet->type = UserWallet::$refund;
                $new_user_wallet->order_id = $user_wallet->order_id;
                $new_user_wallet->order_payment_id = $order_payment->id;
                $new_user_wallet->save();
                $order->status = Order::$refunded;
                $order->payment_status = Order::$payment_status_unpaid;
                $order->save();
//                    $this->refund_product_to_stock($order_id);
                return ['success' => true];
            }
            #endregion

        } else {

            #region refund payment method is paypal or stripe
            if ($order->payment_method == Order::$paypal || $order->payment_method == Order::$stripe) {
                $order->status = Order::$refunded;
                $order->save();
                return ['success' => true];
            }
            #endregion

            #region refund payment method is stripe line or transfer
            else {

//                    $this->refund_product_to_stock($order_id);
                return ['success' => true];
            }
            #endregion

        }
        return ['success' => true];
//            $this->refund_product_to_stock($order_id);
//        } else {
//            return ['success' => false, 'message' => trans('backend.order.cant_refund_because_is_not_hold_or_canceled')];
//        }
    }

    function refund_statements_order($order_id, Order $order, $changeStatus = true)
    {

        $user_wallet_payment = UserWallet::query()->where('order_id', $order_id)->where('status', 'approve')
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('type', 'order')
                        ->where('amount', '>', 0);
                });
                $q->orWhereIn('type', ['amount', 'withdraw', 'refund']);
            })
            ->sum('amount');
        if ($user_wallet_payment == 0) {
            if (in_array($order->payment_method, [Order::$paypal, Order::$stripe]) && $order->payment_status == Order::$payment_status_paid) {
                $user_wallet_payment = $order->total;
            }
        }

        UserWallet::query()->where('order_id', $order_id)->whereIn('type', [Order::$order, Order::$pin_code])->update(['status' => UserWallet::$cancelled]);
        $user_wallet = UserWallet::query()->where('order_id', $order_id)->first();

        $order_payment = new OrderPayment();
        $order_payment->order_id = $order_id;
        $order_payment->user_id = $order->user_id;
        $order_payment->amount = $user_wallet_payment;
        $order_payment->status = OrderPayment::$captured;
        $order_payment->payment_method = OrderPayment::$transfer;
        $order_payment->save();
        $new_user_wallet = new UserWallet();
        $new_user_wallet->user_id = $order->user_id;
        $new_user_wallet->create_by_type = auth('admin')->check() ? Admin::class : (auth('api')->check() ? User::class : Seller::class);
        $new_user_wallet->create_by_id = auth('admin')->check() ? auth('admin')->id() : (auth('api')->check() ? auth('api')->id() : auth('seller')->id());
        $new_user_wallet->amount = $user_wallet_payment;
        $new_user_wallet->status = UserWallet::$approve;
        $new_user_wallet->type = UserWallet::$refund;
        $new_user_wallet->order_id = $order_id;
        $new_user_wallet->order_payment_id = $order_payment->id;
        $new_user_wallet->save();
        if ($changeStatus) {
            $order->status = Order::$refunded;
            $order->payment_status = Order::$payment_status_unpaid;
            $order->save();

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
        Mail::to($order->user->email)
            ->bcc($bcc_emails)
            ->later(0, new OrderUserMail("payment", ['order' => $order], 'statement', ['payment_recode' => $new_user_wallet->id]));
        return $order;
    }

    function refund_product_to_stock($order_id, $order_product_id = null, $quantity = null)
    {
        $order = Order::find($order_id);
        $order_products = OrdersProducts::query()->where('order_id', $order_id);
        if (!empty($order_product_id)) {
            $order_products = $order_products->where('id', $order_product_id);
        }
        $order_products = $order_products->get();
        foreach ($order_products as $item) {
            $product = Product::query()->where('id', $item->product_id)->first();
            if (empty($quantity)) {
                $quantity = $item->quantity;
            }
            $product->quantity += $quantity;
            $product->save();

            $receivers['admins'] = Admin::query()->where('status', 1)->get();
            $data = [
                'title' => trans('backend.notifications.stock_increase'),
                'body' => trans('backend.notifications.stock_increase_order', ['number' => $order->uuid, 'sku' => $product->sku])
            ];
            $this->sendNotification($receivers, 'stock_increase', $data, null, Order::class, $order->id);

            $stock_status = ProductStockStatus::make([
                'quantity' => $product->quantity
            ]);
            $product->stockStatus()->save($stock_status);
            $product->save();
            ProductsSerialNumber::query()
                ->where('order_id', $order_id)
                ->where('order_product_id', $item->id)
                ->where('product_id', $item->product_id)
                ->limit($quantity)
                ->update([
                    'order_id' => null,
                    'order_product_id' => null,
                ]);
        }
        $order = Order::query()->where('id', $order_id)->first();
        if (!empty($order)) {
            $order->seller_commission = 0;
            $this->calculate_eranings($order->created_at->format('Y'), $order->created_at->format('m'), $order->seller_id);
            $order->save();
        }
    }

    function approve_wallet($wallet_id)
    {
        $user_wallet = UserWallet::query()->where('id', $wallet_id)->first();
        $user_wallet->status = UserWallet::$approve;
        $user_wallet->save();
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }

    function set_serial_numbers(OrdersProducts $ordersProducts, $quantity = null)
    {

        if (empty($quantity)) {
            $quantity = $ordersProducts->quantity;
        }
        $product = Product::query()->find($ordersProducts->product_id);
        $product->quantity = $product->quantity - $ordersProducts->quantity;
        $product->save();


        $stock_status = ProductStockStatus::make([
            'quantity' => $product->quantity
        ]);
        $product->stockStatus()->save($stock_status);
        $product->save();

        if (!empty($ordersProducts->serial_number) && $ordersProducts->serial_number != 'null') {
            $serial_number = json_decode($ordersProducts->serial_number);
            foreach ($serial_number as $item) {
                $check = ProductsSerialNumber::query()
                    ->whereNull('order_id')
                    ->whereNull('order_product_id')
                    ->where('product_id', $ordersProducts->product_id)
                    ->where('serial_number', 'like', $item)
                    ->count();
                $update = ProductsSerialNumber::query()
                    ->whereNull('order_id')
                    ->whereNull('order_product_id')
                    ->where('product_id', $ordersProducts->product_id);
                if ($check != 0) {

                    $update = $update ->where('serial_number', 'like', $item);
                }
                $update = $update->limit(1)->update([
                    'order_id' => $ordersProducts->order_id,
                    'order_product_id' => $ordersProducts->id
                ]);


            }

        } else if (ProductsSerialNumber::query()->where('order_product_id', $ordersProducts->id)->count() < $quantity) {
            ProductsSerialNumber::query()
                ->whereNull('order_id')
                ->whereNull('order_product_id')
                ->where('product_id', $ordersProducts->product_id)
                ->limit($quantity - ProductsSerialNumber::query()->where('order_product_id', $ordersProducts->id)->count())
                ->update([
                    'order_id' => $ordersProducts->order_id,
                    'order_product_id' => $ordersProducts->id
                ]);
        }


    }

    #region change to order
    public function change_proforma_to_order($id)
    {
        $order = Order::query()
            ->where('id', $id)
            ->where('type', Order::$proforma)
            ->where('status', Order::$proforma)
            ->first();
        $userId = $order->user_id;
        $order->type = Order::$order;
        $order->status = Order::$on_hold;

        //create order payment
        $order_payment = OrderPayment::query()->where('order_id', $order->id)->first();
        if (empty($order_payment)) {
            $order_payment = new OrderPayment();
            $order_payment->order_id = $order->id;
        }
        $order_payment->order_id = $order->id;
        $order_payment->user_id = $order->user_id;
        $order_payment->amount = $order->total;
        $order_payment->payment_method = Order::$transfer;
        $order_payment->card_id = null;
        $order_payment->status = OrderPayment::$captured;
        $order_payment->stripe_url = null;
        $order_payment->payment_details = "";
        $order_payment->stripe_url = "";
        $order_payment->save();

        $user_wallet = UserWallet::query()->where('order_payment_id', $order_payment->id)->first();
        if (empty($user_wallet)) {
            $user_wallet = new UserWallet();
            $user_wallet->order_payment_id = $order_payment->id;
        }
        $order->save();
        $order->refresh();
        $user_wallet->user_id = $userId;
        $user_wallet->order_id = $order->id;
        $user_wallet->amount = $order->total * -1;
        $user_wallet->type = UserWallet::$order;
        $user_wallet->status = UserWallet::$approve;
        $user_wallet->create_by_type = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
        $user_wallet->create_by_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
        $user_wallet->save();
        $order->stripe_url = null;


    }

    public function change_proforma_to_order_stripe_link($id)
    {
        $order = Order::query()
            ->where('id', $id)
            ->where('type', Order::$proforma)
            ->where('status', Order::$proforma)
            ->first();
        $userId = $order->user_id;
        $order->type = Order::$order;
        $order->status = Order::$on_hold;
        $order->payment_method = Order::$stripe_link;

        //create order payment
        $order_payment = OrderPayment::query()->where('order_id', $order->id)->first();
        if (empty($order_payment)) {
            $order_payment = new OrderPayment();
            $order_payment->order_id = $order->id;
        }
        $order_payment->order_id = $order->id;
        $order_payment->user_id = $order->user_id;
        $order_payment->amount = $order->total;
        $order_payment->payment_method = Order::$stripe_link;
        $order_payment->card_id = null;
        $order_payment->status = OrderPayment::$captured;
        $order_payment->stripe_url = null;
        $order_payment->payment_details = "";
        $order_payment->stripe_url = $this->create_payment_link([], $order->id)['link'];

//        $order->stripe_url = $order_payment->stripe_url;
        $order_payment->save();

        $user_wallet = UserWallet::query()->where('order_payment_id', $order_payment->id)->first();
        if (empty($user_wallet)) {
            $user_wallet = new UserWallet();
            $user_wallet->order_payment_id = $order_payment->id;
        }
        $order->save();
        $order->refresh();
        $user_wallet->user_id = $userId;
        $user_wallet->order_id = $order->id;
        $user_wallet->amount = $order->total * -1;
        $user_wallet->type = UserWallet::$order;
        $user_wallet->status = UserWallet::$approve;
        $user_wallet->create_by_type = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
        $user_wallet->create_by_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
        $user_wallet->save();

        $user_wallet2 = new UserWallet();
        $user_wallet2->order_payment_id = $order_payment->id;
        $user_wallet2->user_id = $userId;
        $user_wallet2->order_id = $order->id;
        $user_wallet2->amount = $order->total;
        $user_wallet2->type = UserWallet::$order;
        $user_wallet2->status = UserWallet::$pending;
        $user_wallet2->create_by_type = auth('admin')->check() ? Admin::class : (auth('seller')->check() ? Seller::class : User::class);
        $user_wallet2->create_by_id = auth('admin')->check() ? auth('admin')->id() : (auth('seller')->check() ? auth('seller')->id() : auth('api')->id());
        $user_wallet2->save();
        return $order_payment->stripe_url;


    }
    #endregion


}
