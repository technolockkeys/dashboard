<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ChangeOrderStatusRequest;
use App\Http\Requests\Backend\Orders\ApplyCouponCodeRequest;
use App\Http\Requests\Backend\Orders\GetPriceRequest;
use App\Http\Requests\Backend\Orders\GetUserBySellerRequest;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\CouponUsages;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\ProductsPackages;
use App\Models\ProductsSerialNumber;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserWallet;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\EraningsTrait;
use App\Traits\InvoiceTrait;
use App\Traits\NotificationTrait;
use App\Traits\OrderTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\Finder\in;

class OrderController extends Controller
{
    use ButtonTrait;
    use DatatableTrait;
    use OrderTrait;
    use InvoiceTrait;
    use EraningsTrait;
    use NotificationTrait;

    #region index
    public function index()
    {
        if (!permission_can('show orders', 'admin')) {
            return abort(403);
        }

        $filters[] = 'user';
        $filters[] = 'payment_method';
        $filters[] = 'payment_status';
        $filters[] = 'status';
        $filters[] = 'coupon';
        $filters[] = 'product';
        $filters[] = 'is_free_shipping';
        $filters[] = 'type';
        $filters[] = 'seller';
        $filters[] = 'shipping_method';
        $filters[] = 'start_date';
        $filters[] = 'end_date';
        $filters[] = 'currency';
        $datatable_route = route('backend.orders.datatable', ['status_filter' => request()->status_filter]);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['uuid'] = 'uuid';
        $datatable_columns['user'] = 'users.name ';
        $datatable_columns['seller'] = 'sellers.name';
        $datatable_columns['shipping_method'] = 'shipping_method';
//        $datatable_columns['payment_method'] = 'payment_method';
        $datatable_columns['payment_status'] = 'payment_status';
        $datatable_columns['total'] = 'total';
        $datatable_columns['balance'] = 'balance';
        $datatable_columns['status'] = 'status';
        $datatable_columns['coupon'] = 'coupon_value';
        $datatable_columns['type'] = 'type';
        $datatable_columns['tracking_number'] = 'tracking_number';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $users_id = Order::query()->whereNotIn('status' ,[Order::$canceled])->pluck('user_id');
        $users = User::whereIn('id', $users_id)->whereNull('deleted_at')->get();
        $sellers_ids = Order::pluck('seller_id');
        $sellers = Seller::whereIn('id', $sellers_ids)
            ->whereNull('deleted_at')->get();
        $payment_methods = Order::payment_methods();
        $payment_statuses = Order::payment_statuses();

        $products_ids = OrdersProducts::groupBy('product_id')->pluck('product_id');
        $products = Product::whereIn('id', $products_ids)->get();
        $statuses = Order::statuses();
        $statuses['failed'] = trans('backend.order.failed');
        $types = Order::types();
        $shipping_methods = Order::shipping_methods();
        $currencies = Currency::query()->where('status', 1)->get();
        $sellers['no_user'] = trans('backend.order.no_seller');
//        dd($sellers);
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters, null, null, null, null, true, true, null, ['balance']);
        return view('backend.order.index', compact('datatable_script', 'users',
            'payment_methods', 'payment_statuses', 'statuses', 'types', 'currencies', 'sellers', 'shipping_methods', 'products'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show orders', 'admin')) {
            return abort(403);
        }

        $model = Order::query()
            ->select(['orders.*', 'currencies.symbol', \DB::raw('sellers.name as seller'), \DB::raw('sum(user_wallet.amount) as balance'), \DB::raw('users.name as user'), \DB::raw('users.email as email')])
            ->leftJoin('user_wallet', 'user_wallet.order_id', 'orders.id')
            ->leftjoin('users', 'orders.user_id', 'users.id')
            ->leftjoin('sellers', 'sellers.id', 'orders.seller_id')
            ->leftjoin('currencies', 'currencies.id', 'orders.currency_id')
            ->whereNotIn('orders.status', [Order::$canceled, Order::$waiting])
            ->where(function ($q) {
                $q->where('user_wallet.status', UserWallet::$approve)->orWhereNull('user_wallet.status');
            })
            ->groupBy('orders.id')
            ->whereNull('orders.deleted_at');

//            ->whereNull('users.deleted_at');

        #region filters
        if ($request->has('user') && $request->user != null) {

            $model->where(function ($q) use ($request) {
                $q->where('users.uuid', $request->user);
                $q->orWhere('users.id', $request->user);
            });
        }

        if ($request->start_date != null) {
            $model = $model->whereBetween('orders.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }
        if ($request->has('shipping_method') && $request->shipping_method != null) {
            $model = $model->where('shipping_method', $request->shipping_method);
        }
        if ($request->has('payment_status') && $request->payment_status != null) {
            $model = $model->where('payment_status', $request->payment_status);
        }
        if (($request->has('status') && $request->status != null)) {
            $model = $model->where('orders.status', $request->status);
        }

        if ($request->has('payment_method') && $request->payment_method != null) {
            $model = $model->where('payment_method', $request->payment_method);
        }

        if ($request->has('type') && $request->type != null) {
            $model = $model->where('orders.type', $request->type);
        }

        if ($request->has('coupon') && $request->coupon != -1) {
            $model = $model->where('has_coupon', $request->coupon);
        }

        if ($request->has('is_free_shipping') && $request->is_free_shipping != -1) {
            $model = $model->where('is_free_shipping', $request->is_free_shipping);
        }

        if ($request->has('seller') && $request->seller != null) {
            $model = $model->where(function ($q) use ($request) {
                $request->seller == 'no_user' ? $q->whereNull('orders.seller_id') :
                    $q->where('orders.seller_id', $request->seller)//                        ->orWhere('orders.seller_manager_id', $request->seller)
                ;
            });
        }

        if ($request->product != null) {
            $model = $model->whereIn('orders.id', function ($query) use ($request) {
                $query->select('order_id')->from('orders_products')->where('orders_products.product_id', $request->product);
            });
        }

        if ($request->user != null) {
            $model = $model->where('orders.user_id', $request->user);
        }
        if ($request->currency != null) {
            if ($request->currency != 1) {
                $model = $model->where('orders.currency_id', $request->currency);
            } else {
                $model = $model->where(function ($q) use ($request) {
                    $q->where('orders.currency_id', $request->currency)->orWhereNull('orders.currency_id');
                });

            }
        }
        #endregion
        $permission['show'] = permission_can('show orders', 'admin');
        $permission['edit'] = permission_can('edit order', 'admin');
        $permission['create'] = permission_can('create order', 'admin');
        $permission['refund'] = permission_can('refund order', 'admin');
        $permission['change_status'] = permission_can('change status order', 'admin');
        $permission['send'] = permission_can('send pdf order', 'admin');

        return datatables()->make($model)
            ->addColumn('actions', function ($q) use ($permission) {
                $actions = '';
                if ($permission['show']) {
                    $actions .= $this->btn(route('backend.orders.show', ['order' => $q->id]), '', 'las la-eye', 'btn-warning btn-show btn-icon');
                }

                $actions .= ' <a href=' . route("backend.orders.download", $q->uuid) . ' type="button" class="btn btn-secondary text-xl  btn-hover-rise btn-icon btn-sm "> <i class="fonticon-printer fs-1x"></i></a> ';

                if (!empty($q->email) && $permission['send']) {
                    $actions .= ' <a type="button" href=' . route("backend.orders.send.pdf.user", $q->id) . ' class="btn btn-primary  btn-hover-rise text-xl btn-icon btn-sm " >  <i class="las la-paper-plane"></i>   </a> ';
                }
                if ($permission['edit'] && abs($q->balance) == abs($q->total) && !in_array($q->status, [Order::$completed, Order::$refunded, Order::$canceled]) && $q->type != Order::$pin_code) {
                    $actions .= $this->edit_button(route('backend.orders.edit', $q->id));
                }
                if ($permission['refund'] && $q->type == Order::$order && in_array($q->status, [Order::$completed, Order::$processing])) {
                    $actions .= ' <a  href="' . route('backend.orders.refund', $q->id) . '"  class="btn btn-light-danger btn-sm  btn-hover-rise" ><i class="las la-redo"></i> ' . trans('backend.order.refund') . '</a>';
                }
                if ($permission['change_status'] && (($q->type == Order::$order && in_array($q->status, [Order::$on_hold, Order::$pending_payment])) || ($q->type == Order::$proforma && $q->status != Order::$canceled))) {
                    $actions .= ' <a  href="' . route('backend.orders.cancel', $q->uuid) . '"  class="btn btn-light-dark btn-sm  btn-hover-rise" ><i class="las la-times"></i> ' . trans('backend.order.cancel') . '</a>';
                }


                return $actions;
            })
            ->editColumn('tracking_number', function ($q) {
                $text = $q->tracking_number ?? __('backend.global.not_found');
                return '<span class="badge badge-light-info badge-lg fw-bold  small ">' . $text . '</span>';
            })
            ->editColumn('total', function ($q) {

                return '<span class="badge badge-light-info badge-lg fw-bold small ">' . currency($q->total) . (!empty($q->currency_id) && $q->currency_id != 1 ? ' / ' . ($q->total * $q->exchange_rate) . ' ' . $q->symbol : '') . '</span>';
            })
            ->editColumn('shipping', function ($q) {
                return '<span class="badge badge-light-primary badge-lg fw-bold  small ">' . currency($q->shipping) . (!empty($q->currency_id) && $q->currency_id != 1 ? ' / ' . ($q->shipping * $q->exchange_rate) . ' ' . $q->symbol : '') . '</span>';
            })
            ->editColumn('seller', function ($q) {
                return $q->seller ?
                    '<a href="' . route('backend.sellers.edit', ['seller' => $q->seller_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->seller . '</span>
                                                    </a>'
                    :
                    '<span class="badge small badge-light-danger"> ' . trans('backend.global.not_found') . '</span>';
            })
            ->editColumn('payment_method', function ($q) {
                if ($q->type == Order::$proforma) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                $html = '<span class="badge badge-light-primary badge-lg fw-bold small ">' . trans('backend.order.' . $q->payment_method) . '</span>';
                return $html;
            })
            ->editColumn('coupon', function ($q) {
                $html = '<span class="badge badge-secondary badge-lg fw-bold small ">' . currency($q->coupon_value) . (!empty($q->currency_id) && $q->currency_id != 1 ? ' / ' . ($q->coupon_value * $q->exchange_rate) . ' ' . $q->symbol : '') . '</span>';
                return $html;
            })
            ->editColumn('type', function ($q) {
                if ($q->type == Order::$order) {
                    $html = '<span class="badge badge-light-primary badge-lg fw-bold  small">' . trans('backend.order.' . $q->type) . '</span>';
                } else if ($q->type == Order::$proforma) {
                    $html = '<span class="badge badge-light-dark badge-lg fw-bold small">' . trans('backend.order.' . $q->type) . '</span>';
                } else if ($q->type == Order::$pin_code) {
                    $html = '<span class="badge badge-light-info badge-lg fw-bold small ">' . trans('backend.order.' . $q->type) . '</span>';
                }
                return $html;
            })
            ->editColumn('payment_status', function ($q) {
                if ($q->type == Order::$proforma) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                if ($q->payment_status == 'paid') {
                    $class = 'badge-light-success';
                } elseif ($q->payment_status == 'unpaid') {
                    $class = 'badge-light-warning';
                } else {
                    $class = 'badge-default text-dark';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold small ">' . $q->payment_status . '</span>';
                $html .= '<span class="badge badge-light-primary mt-3 badge-lg fw-bold  small">' . trans('backend.order.' . $q->payment_method) . '</span>';
                return $html;
//                return $html;
            })
            ->editColumn('status', function ($q) {
                $class = 'badge-dark';
                if (Order::$proforma == $q->type && $q->status != Order::$canceled) {
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                if ($q->status == Order::$completed) {
                    $class = 'badge-primary';
                } elseif ($q->status == Order::$on_hold) {
                    $class = 'badge-warning';
                } elseif ($q->status == Order::$refunded || $q->status == Order::$pending_payment) {
                    $class = 'badge-secondary';
                } elseif ($q->status == Order::$processing) {
                    $class = 'badge-success';
                } elseif ($q->status == Order::$failed) {
                    $class = 'badge-default text-dark';
                } elseif ($q->status == Order::$canceled) {
                    $class = 'badge-danger';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  small ">' . trans('backend.order.' . $q->status) . '</span>';
                return $html;

            })
            ->editColumn('shipping_method', function ($q) {
                $class = 'badge-dark';
                if ($q->shipping_method == Order::$DHL) {
                    $class = 'badge-light-primary';
                } elseif ($q->shipping_method == Order::$UPS) {
                    $class = 'badge-light-warning';
                } elseif ($q->shipping_method == Order::$FedEx) {
                    $class = 'badge-light-success';
                } elseif ($q->shipping_method == Order::$Aramex) {
                    $class = 'badge-light-danger';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold small ">' . $q->shipping_method . '</span> ';
                $html .= '<span class="badge  small badge-light-primary mt-1 badge-lg fw-bold  ">' . currency($q->shipping) . (!empty($q->currency_id) && $q->currency_id != 1 ? ' / ' . ($q->shipping * $q->exchange_rate) . ' ' . $q->symbol : '') . '</span>';
                return $html;
            })
            ->editColumn('user', function ($q) {
                if (!empty($q->user_id))
                    return '<div class="d-flex align-items-center me-5">
                                    <img src="http://localhost:8000/storage/user/c4ca4238a0b923820dcc509a6f75849b.jpg" onerror="this.src=' . "'" . asset('backend/media/avatars/blank.png') . "'" . '" class="me-4 w-30px" style="border-radius: 4px" alt="">

                                    <div class="me-5">
                                        <a href="' . route('backend.users.show', ['user' => $q->user_id]) . '" class="text-gray-800 fw-bolder text-hover-primary small fs-6"> ' . $q->user . '</a>

                                    </div>
                                </div>';
                return '-';

            })
            ->editColumn('created_at', function ($q) {
                return '<span class="badge badge-light-dark badge-lg fw-bold small ">' . $q->created_at . '</span>';
            })
            ->editColumn('id', function ($q) {
                $icon = $q->has_coupon ? '<span class="badge badge-danger badge-lg fw-bold small "><i class="las la-gift"></i> ' . $q->id . '</span>' : $q->id;
                return $icon;
            })
            ->editColumn('balance', function ($q) {
                if ($q->type == Order::$proforma) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                return '<span class="badge badge-light-dark badge-lg fw-bold small ">' . ($q->balance == null ? "-" : currency($q->balance)) . '</span>';

            })
            ->rawColumns(['actions', 'user', 'balance', 'created_at', 'seller', 'status', 'payment_method', 'payment_status', 'type', 'total', 'shipping', 'coupon', 'tracking_number', 'id', 'shipping_method'])
            ->toJson();
    }
    #endregion

    #region show
    public function show($id)
    {
        if (!permission_can('show orders', 'admin')) {
            return abort(403);
        }

        $order = Order::findOrFail($id);
//        dd($order->order_payment);
        $products = $order->order_products()->whereNull('parent_id')->withTrashed()->get();
        $address = Address::find($order->address_id);
        $user = User::where('id', $order->user_id)->withTrashed()->first();

        $order_statuses = Order::statuses();
        //on hold
        if ($order->status == Order::$on_hold) {
            $order_statuses = [
                'on_hold' => trans('backend.order.on_hold'),
                'pending_payment' => trans('backend.order.pending_payment'),
                'processing' => trans('backend.order.processing'),
                'completed' => trans('backend.order.completed'),
                'canceled' => trans('backend.order.canceled'),
            ];
        } //pending payment
        elseif ($order->status == Order::$pending_payment) {
            $order_statuses = [
                'pending_payment' => trans('backend.order.pending_payment'),
                'processing' => trans('backend.order.processing'),
                'completed' => trans('backend.order.completed'),
                'canceled' => trans('backend.order.canceled'),
            ];
        } //processing
        elseif ($order->status == Order::$processing) {
            $order_statuses = [
                'processing' => trans('backend.order.processing'),
                'completed' => trans('backend.order.completed'),
                'refunded' => trans('backend.order.refunded')
            ];
        } //completed
        elseif ($order->status == Order::$completed) {
            $order_statuses = ['completed' => trans('backend.order.completed'), 'refunded' => trans('backend.order.refunded')];
        } //failed
        elseif ($order->status == Order::$failed) {
            $order_statuses = ['failed' => trans('backend.order.failed')];
        } //refunded
        elseif ($order->status == Order::$refunded) {
            $order_statuses = ['refunded' => trans('backend.order.refunded')];
        } elseif ($order->status == Order::$canceled) {
            $order_statuses = ['canceled' => trans('backend.order.canceled')];
        }
        $order_payment_status = Order::payment_statuses();
        if ($order->payment_status == Order::$payment_status_paid) {
            $order_payment_status = ['paid' => trans('backend.order.paid')];
        }
        $qr = $this->generateQrCode($order->uuid);

        foreach ($products as $item) {

            if ($item->is_bundle == 1) {

                $item->bundle_products = $order->order_products()->where('orders_products.parent_id', $item->pivot->id)->withTrashed()->get();

                foreach ($item->bundle_products as $item2) {

                    $item2->all_serile_numbers = ProductsSerialNumber::query()
                        ->where(function ($q) use ($item2) {
                            $q->where('order_product_id', $item2->pivot->id);
                            $q->orWhereNull('order_product_id');
                        })->where(function ($q) use ($item2) {
                            $q->where('order_id', $item2->pivot->order_id)->orWhereNull('order_id');
                        })->where('product_id', $item2->id)->get();
                    $item2->serile_number = ProductsSerialNumber::query()->where('order_product_id', $item2->pivot->id)->where('order_id', $item2->pivot->order_id)->where('product_id', $item2->id)->pluck('id')->toArray();
                    $item2->serile_number_show = ProductsSerialNumber::query()->where('order_product_id', $item2->pivot->id)->where('order_id', $item2->pivot->order_id)->where('product_id', $item2->id)->pluck('serial_number')->toArray();
                }

            } else {
                $item->all_serile_numbers = ProductsSerialNumber::query()
                    ->where(function ($q) use ($item) {
                        $q->where('order_product_id', $item->pivot->id);
                        $q->orWhereNull('order_product_id');
                    })->where(function ($q) use ($item) {
                        $q->where('order_id', $item->pivot->order_id)->orWhereNull('order_id');
                    })->where('product_id', $item->id)->get();
                $item->serile_number = ProductsSerialNumber::query()->where('order_product_id', $item->pivot->id)->where('order_id', $item->pivot->order_id)->where('product_id', $item->id)->pluck('id')->toArray();
                $item->serile_number_show = ProductsSerialNumber::query()->where('order_product_id', $item->pivot->id)->where('order_id', $item->pivot->order_id)->where('product_id', $item->id)->pluck('serial_number')->toArray();

            }
        }
        if ($order->currency_id == 1 || $order->currency_id == null) {
            $currency = Currency::query()->where('id', 1)->first();
        } else {
            $currency = Currency::query()->where('id', $order->currency_id)->first();
        }
        $order_balance = UserWallet::query()->where('order_id', $order->id)->where('status', UserWallet::$approve)->sum('amount');
        $files = UserWallet::query()->where('order_id', $order->id)->whereNotNull('files')->pluck('files');
        $coupon = null;
        if (!empty($order->coupon_id)) {
            $coupon = Coupon::query()->where('id', $order->coupon_id)->first();
        }

        return view('orders.show', compact('order', 'currency', 'order_balance', 'files', 'coupon', 'order_payment_status', 'products', 'address', 'qr', 'user', 'order_statuses'));
    }

    #endregion

    #region download
    public function download($id)
    {

        $order = Order::query()->where('uuid', $id)->first();


        if (empty($order)) {
            return abort(404);
        }
        $this->PrintInvoicePDF($order->id);


        return;
    }
    #endregion

    #region status
    public function change_status(ChangeOrderStatusRequest $request, $id)
    {
        $last_status = null;
        $order = Order::find($id);
        $last_status = ['status' => $order->status, 'payment_status' => $order->payment_status];
        if ($request->has('payment_status'))
            $order->payment_status = $request->payment_status;
        if ($request->has('tracking_number'))
            $order->tracking_number = $request->tracking_number;
        if ($request->has('shipment_value'))
            $order->shipment_value = $request->shipment_value;
        if ($request->has('shipment_description'))
            $order->shipment_description = $request->shipment_description;

        if ($request->has('status')) {
            $old_status = $order->status;
            $can_change_status = false;
            if ($old_status == Order::$on_hold && in_array($request->status, [Order::$completed, Order::$processing, Order::$pending_payment, Order::$on_hold, Order::$canceled])) {
                $can_change_status = true;
            } elseif ($old_status == Order::$pending_payment && in_array($request->status, [Order::$completed, Order::$processing, Order::$pending_payment, Order::$canceled])) {
                $can_change_status = true;

            } elseif ($old_status == Order::$processing && in_array($request->status, [Order::$completed, Order::$processing, Order::$refunded])) {
                $can_change_status = true;
            } elseif ($old_status == Order::$completed && in_array($request->status, [Order::$completed, Order::$refunded])) {
                $can_change_status = true;
            }
            if ($can_change_status) {
                $order->status = $request->status;
            }
            if ($order->status == Order::$processing && $old_status != $order->status) {
                $order_products = OrdersProducts::query()->where('order_id', $order->id)->get();
                foreach ($order_products as $order_product) {

                    $this->set_serial_numbers($order_product);
                }
            }

            if ($order->status == Order::$refunded) {
                $this->refund_product_to_stock($order->id);
                $this->refund_statements_order($order->id, $order);
            }
            if (($order->status == Order::$completed || $order->status == Order::$processing) && $order->payment_status == Order::$payment_status_paid && !empty($order->seller_id)) {

                $this->calculate_eranings($order->updated_at->format('Y'), $order->updated_at->format('m'), $order->seller_id);
            }
        }
        $order->save();
        if (($order->status == Order::$completed || $order->status == Order::$processing) && $order->payment_status == Order::$payment_status_paid) {
            if (!empty($order->seller_id))
                $this->calculate_eranings($order->updated_at->year, $order->updated_at->month, $order->seller_id);

            if ($order->payment_status == Order::$payment_status_paid) {
                $amount = UserWallet::query()->where('user_id', $order->user_id)
                    ->where('order_id', $order->id)
                    ->where('status', UserWallet::$approve)
                    ->sum('amount');

                if ($amount < 0) {
                    //user wallet
                    $user_wallet = UserWallet::query()->where('status', UserWallet::$pending)->where('order_id', $order->id)->where('amount', $amount * -1)->first();
                    if (empty($user_wallet)) {
                        $user_wallet = new UserWallet();
                        $orderPayment = OrderPayment::create([
                            'user_id' => $order->user_id,
                            'card_id' => null,
                            'order_id' => $order->id,
                            'amount' => $amount * -1,
                            'payment_details' => '',
                            'status' => OrderPayment::$captured,
                            'payment_method' => OrderPayment::$transfer,
                            'stripe_url' => '',
                            'files' => null,

                        ]);
                        $user_wallet->order_payment_id = $orderPayment->id;

                    }
                    $user_wallet->user_id = $order->user_id;
                    $user_wallet->create_by_type = Admin::class;
                    $user_wallet->create_by_id = auth('admin')->id();
                    $user_wallet->amount = $amount * -1;
                    $user_wallet->type = UserWallet::$order;
                    $user_wallet->status = UserWallet::$approve;
                    $user_wallet->order_id = $order->id;
                    $user_wallet->note = null;
                    $user_wallet->files = null;
                    $user_wallet->save();
                    //payment order
                    UserWallet::query()->where('status', UserWallet::$pending)->where('order_id', $order->id)->update(['status' => UserWallet::$cancelled]);
                    OrderPayment::query()->where('status', OrderPayment::$pending)->where('order_id', $order->id)->update(['status' => OrderPayment::$captured]);
                }
            }
        }
        $order = $order->refresh();
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

        return redirect()->route('backend.orders.index')->with('success', trans('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region change to order
    public function change_to_order(Request $request, $id)
    {
        if ($request->has('payment_method') && $request->payment_method == 'stripe_link') {
            $stripe_link = $this->change_proforma_to_order_stripe_link($id);
            return response()->data(['message' => trans('backend.global.success_message.updated_successfully'), 'reload' => 0, 'stripe_link' => $stripe_link]);
        } else {
            $this->change_proforma_to_order($id);
            return response()->data(['message' => trans('backend.global.success_message.updated_successfully'), 'reload' => 1]);
        }
    }
    #endregion

    #region print
    function print_pdf($id)
    {
        $pdf = \PDF::loadHTML(view('backend.order.export')->render());
        return $pdf->download('123.pdf');
    }
    #endregion

    #region create
    function create()
    {
        if (!permission_can('create order', 'admin')) {
            return abort(403);
        }

        $sellers = Seller::query()->where('status', 1)->get();
        $users = User::query()->where('status', 1)->get();
        $countries = Country::query()->where('status', 1)->get();
        $data_products = Product::query()->where('status', 1)->where(function ($q) {
            $q->where('quantity', '>', 0);
//            $q->orWhere('is_bundle', 1);
        })->get();
        $products = [];
        foreach ($data_products as $item) {
            $blocked_countries_id = empty($item->blocked_countries) ? [] : $item->blocked_countries;
            $countries_data = [];
            $blocked_countries = [];
            if (!empty($blocked_countries_id)) {
                $countries_data = Country::query()->whereIn('id', $blocked_countries_id)->where('status', 1)->get();
                foreach ($countries_data as $country_item) {
                    $blocked_countries [] = $country_item->name;
                }
            }
            $blocked_countries = implode(',', $blocked_countries);

            $products[] = [
                'sku' => $item->sku,
                'slug' => $item->slug,
                'title' => $item->title,
                'quantity' => $item->quantity,
                'color' => empty($item->color) ? "" : $item->color->name,
                'is_free_shipping' => $item->is_free_shipping,
                'image' => media_file($item->image),
                'price' => !empty($item->sale_price) ? $item->sale_price : $item->price,
                'blocked_countries' => $blocked_countries_id,
                'blocked_countries_names' => $blocked_countries];
        }
        $currencies = Currency::query()->where('status', 1)->get();
        return view('orders.create', compact('sellers', 'users', 'currencies', 'countries', 'products'));
    }

    function get_user_by_seller(GetUserBySellerRequest $request)
    {
        $users = User::query();
        if ($request->seller != "-1") {
            $users->where('seller_id', $request->seller);
        }
        $users = $users->get();
        return response()->data(['users' => $users]);
    }

    function get_address_by_user(Request $request)
    {
        $addressesss = Address::query()->select('addresses.*')->join('users', 'users.id', 'addresses.user_id')->where('uuid', $request->uuid)->orderByDesc('is_default')->get();
        $addresses = [];
        foreach ($addressesss as $key => $address) {
            $country = $address->get_country();
            $city = $address->city;
            $addresses[] = [
                'id' => $address->id,
                'text' => $country . "/" . (!empty($city) ? $city . "/" : '') . (!empty($address->address) ? $address->address : ""),
                'country' => $country,
                'country_id' => $address->country_id,
                'city' => $city,
                'address' => $address->address,
                'postal_code' => $address->postal_code
            ];
        }
        return response()->data(['addresses' => $addresses]);
    }

    function get_price(GetPriceRequest $request)
    {
        $weight_avalibale = 0;
        $address = Address::find($request->address);
        $seller_id = $request->seller;
        $product = Product::query()->where('status', 1)->where('sku', $request->sku)->first();
        if (empty($product)) {
            return response()->error(trans('seller.orders.product_not_found'));
        }
        $quantity = $request->quantity;
        $obj_price = $this->price_product($product, $quantity);
        $price = $obj_price['price'];
        $quantity = $request->quantity;
//        $packege = ProductsPackages::query()
//            ->where('from', '<=', $quantity)
//            ->where('to', '>=', $quantity)
//            ->where('product_id', $product->id)
//            ->first();
//        if (!empty($packege)) {
//            $price = $packege->price;
//        }
        if (!empty($seller_id) && $seller_id > 0) {
            $product_rate = Seller::query()->find($seller_id)->seller_product_rate;
        } else {
            $product_rate = 0;
        }
//        if ($quantity <= $product->quantity) {
//            $weight_avalibale = $product->weight * $quantity;
//        }
//        if ($product->is_free_shipping) {
//            $shipping_costs = 0;
//            $shipping_price = 0;
//        } else {
//            $shipping_costs = $this->shipping_cost($address->country_id, $weight_avalibale, false);
////             $shipping_costs= $shipping_costs['shipping_costs'];
////             dd($shipping_costs);
//            $shipping_price = $shipping_costs['shipping'][Order::$DHL];
//        }
        if (!empty($seller_id) && $seller_id > 0) {
            $min_price_for_unit = $price - ($price * ($product_rate / 100));
            $min_price_for_unit_total = $min_price_for_unit * $quantity;
        } else {
            $min_price_for_unit = null;
            $min_price_for_unit_total = null;
        }

        return response()->data([
            'quantity' => $product->quantity,
            'unit_price' => $price,
            'total' => $quantity * $price,
            'shipping_price' => 0,
            'shipping_methods' => [
                'dhl' => 0,
                'aramex' => 0,
                'fedex' => 0,
                'ups' => 0
            ],
            'min_price_for_unit' => $min_price_for_unit,
            'total_min_price' => $min_price_for_unit_total,
            'packege' => $obj_price['package_price'],
        ]);
    }

    function store(Request $request)
    {
        if (!permission_can('create order', 'admin')) {
            return abort(403);
        }

        $request->validate([
            'user' => 'required|exists:users,uuid',
            'address' => 'required|exists:addresses,id',
            'orders_item' => 'required|array',
            'payment_method' => 'required',
            'type' => 'required',
            'status' => 'required',
            'shipping_method' => 'required',
            'currency_symbol' => 'required',
            'currency_rate' => 'required',
        ]);
        //currency..

        $exchange_rate = $request->currency_rate;
        $currency = Currency::query()->where('status', 1)->where('symbol', $request->currency_symbol)->first();


        $user_id = User::query()->where('uuid', $request->user)->first()->id;
        $address_id = $request->address;
        $products = [];
        foreach ($request->orders_item as $item) {
            $sku = $item;
            $quantity = $request->get('quantity_' . $sku);
            $price = $request->get('product_price_' . $sku);
            $products[] = ['id' => $sku, 'quantity' => $quantity, 'price' => $price];
        }
        $payment_method = $request->payment_method;
        $type = $request->type;
        $shipping_method = $request->shipping_method;
        $shipping_method = $request->shipping_method;
        $seller_id = $request->seller;
        if ($seller_id == -1 || $seller_id == "all") $seller_id = null;
        $files = $request->file('payment_files');
        $note = $request->note;
        $coupon_code = $request->coupon_code;
        $status = $request->status;
        $shipment_value = $request->shipment_value;
        $shipment_description = $request->shipment_description;

        try {
            $response = $this->create_orders($user_id, $address_id, $products, $payment_method, $type, $shipping_method, $seller_id, $files, $note, $coupon_code, null, $status, $shipment_value, $shipment_description, $currency->symbol, $exchange_rate);
        } catch (\Exception $exception) {
            return response()->error($exception->getMessage());
        }
        if (!$response) {

            return response()->error(trans('api.order.cart_is_empty'));
        }
        $data = [];
        if (isset($response['order'])) {
            $data['order'] = $response['order'];
            if ($payment_method == Order::$stripe_link) {
                if (isset($data['order']) && !empty($data['order'])) {
                    $order_id = $data['order']->id;
                    $order_payment = OrderPayment::query()->where('order_id', $order_id)->where('payment_method', Order::$stripe_link)->first();
                    $data['order']['stripe_url'] = !empty($order_payment) ? $order_payment->stripe_url : '';

                }
            } else {
                $data['order'] = $response['order'];
                if ($payment_method == Order::$transfer && $request->has('payment_files')) {
                    $order_payment = OrderPayment::query()->where('order_id', $response['order']->id)->where('payment_method', Order::$transfer)->first();
                }
            }
            $data['order']['invoice_url'] = route('backend.orders.download', $response['order']->uuid);
        }


        return response()->data($data);
    }

    #region apply coupon
    function apply_coupon(ApplyCouponCodeRequest $request)
    {
        $coupon = Coupon::query()->where('code', $request->coupon_code)
            ->where('starts_at', '<=', date('Y-m-d H:i:s'))
            ->where('ends_at', '>=', date('Y-m-d H:i:s'))
            ->where('status', 1)->first();
        if (empty($coupon)) {
            return response()->error(trans('backend.order.coupons.this_coupon_is_invalid'));
        }
        $user_id = $request->user_id;
        $user = User::query()->where('uuid', $user_id)->first();
        $coupon_pre_user_used = CouponUsages::query()->where('coupon_id', $coupon->id)->where('user_id', $user->id)->count();
        $coupon_used = CouponUsages::query()->where('coupon_id', $coupon->id)->count();
        $products = [];
        if ($coupon->type == Coupon::$Product) {
            $products = Product::query()->whereIn('id', $coupon->products_ids)->pluck('sku');
        }
        if ($coupon_pre_user_used >= $coupon->per_user || $coupon_used >= $coupon->max_use) {
            return response()->error(trans('backend.order.coupons.this_coupon_is_invalid'));
        }
        $note = "";
        if (!empty($coupon->minimum_shopping)) {
            $note = "the minimum shopping is <span data-amount='{$coupon->minimum_shopping}'  class='minimum_shopping'>{$coupon->minimum_shopping}</span> <span class='currency'>$</span>,<br> If your shopping cart is less than these  <span data-amount='{$coupon->minimum_shopping}' class='minimum_shopping'>{$coupon->minimum_shopping}</span>  <span class='currency'>$</span> <br> the discount will not be applied ";
        }

        return response()->data(['coupon' => $coupon, 'products' => $products, 'note' => $note]);
    }
    #endregion

    #endregion

    #region edit order
    function edit($id)
    {
        if (!permission_can('edit order', 'admin')) {
            return abort(403);
        }

        $order = Order::query()
            ->where('id', $id)->first();
        $order_statuses = Order::statuses();
        //on hold
        if ($order->status == Order::$on_hold) {
            $order_statuses = [
                'on_hold' => trans('backend.order.on_hold'),
                'pending_payment' => trans('backend.order.pending_payment'),
                'processing' => trans('backend.order.processing'),
                'completed' => trans('backend.order.completed'),
                'canceled' => trans('backend.order.canceled'),
            ];
        } //pending payment
        elseif ($order->status == Order::$pending_payment) {
            $order_statuses = [
                'pending_payment' => trans('backend.order.pending_payment'),
                'processing' => trans('backend.order.processing'),
                'completed' => trans('backend.order.completed'),
                'canceled' => trans('backend.order.canceled'),
            ];
        } //processing
        elseif ($order->status == Order::$processing) {
            $order_statuses = [
                'processing' => trans('backend.order.processing'),
                'completed' => trans('backend.order.completed'),
                'refunded' => trans('backend.order.refunded')
            ];
        } //completed
        elseif ($order->status == Order::$completed) {
            $order_statuses = ['completed' => trans('backend.order.completed'), 'refunded' => trans('backend.order.refunded')];
        } //failed
        elseif ($order->status == Order::$failed) {
            $order_statuses = ['failed' => trans('backend.order.failed')];
        } //refunded
        elseif ($order->status == Order::$refunded) {
            $order_statuses = ['refunded' => trans('backend.order.refunded')];
        }
        $order_product_id = OrdersProducts::query()->where('order_id', $order->id)->pluck('product_id');
        $data_products = Product::query()
            ->where(function ($q) {
                $q->where('status', 1);
                $q->where('quantity', '>', 0);
            });
        if (!empty($order_product_id)) {
            $data_products = $data_products->orWhereIn('id', $order_product_id);
        }

        $data_products = $data_products->get();
        $products = [];
        foreach ($data_products as $item) {
            $blocked_countries_id = empty($item->blocked_countries) ? [] : $item->blocked_countries;
            $countries_data = [];
            $blocked_countries = [];
            if (!empty($blocked_countries_id)) {
                $countries_data = Country::query()->whereIn('id', $blocked_countries_id)->where('status', 1)->get();
                foreach ($countries_data as $country_item) {
                    $blocked_countries [] = $country_item->name;
                }
            }
            $blocked_countries = implode(',', $blocked_countries);
            $order_product_quantity = OrdersProducts::query()->where('order_id', $order->id)->pluck('product_id', 'quantity')->toArray();

            $products[] = [
                'sku' => $item->sku,
                'title' => $item->title,
                'quantity' => $item->quantity + (isset($order_product_quantity[$item->id]) ? $order_product_quantity[$item->id] : 0),
                'color' => empty($item->color) ? "" : $item->color->name,
                'is_free_shipping' => $item->is_free_shipping,
                'image' => media_file($item->image),
                'price' => !empty($item->sale_price) ? $item->sale_price : $item->price,
                'blocked_countries' => $blocked_countries_id,
                'blocked_countries_names' => $blocked_countries];
        }

        $countries = Country::query()->where('status', 1)->get();
        $payment = OrderPayment::query()->where('order_id', $order->id)->first();
        $files = [];
        if (!empty($payment)) {
            $files = $payment->files;
        }
        $order_product = OrdersProducts::query()->select('products.*', 'orders_products.*')->join('products', 'orders_products.product_id', 'products.id')->whereNull('parent_id')->where('order_id', $order->id)->get();

        $address = Address::query()->where('user_id', $order->user_id)->get();

        $users = User::query()->where(function ($q) use ($order) {
            $q->where('status', 1);
            $q->orWhere('id', $order->user_id);
        })->get();
        $coupon = Coupon::query()->where('id', $order->coupon_id)->first();
        foreach ($order_product as $item) {
            $item->price = $item->price / $item->quantity;
        }
        $sellers = Seller::query()->where('status', 1);
        if (!empty($order->seller_id)) {
            $sellers->orWhere('id', $order->seller_id);
        }
        $sellers = $sellers->get();

        $coupon_products = [];

        if (!empty($coupon) && $coupon->type == Coupon::$Product) {
            $coupon_products = Product::query()->whereIn('id', $coupon->products_ids)->pluck('sku');
        }
        $currency = Currency::query()->where('id', $order->currency_id)->first();
        return view('orders.edit', compact('users', 'order_statuses', 'currency', 'sellers', 'countries', 'products', 'coupon_products', 'address', 'order', 'coupon', 'files', 'order_product'));

    }

    function update(Request $request, $id)
    {
        if (!permission_can('edit order', 'admin')) {
            return abort(403);
        }


        $order = Order::query()->where('id', $id)->first();
        $last_status = ['status' => $order->status, 'payment_status' => $order->payment_status];

        if (empty($order)) {
            return response()->error(trans('api.order.order_not_found'));
        }
        $user = User::query()->where('status', 1)->where('uuid', $request->user)->first();
        if (empty($user)) {
            return response()->error(trans('seller.orders.not_found_user'));
        }
        $seller = null;
        if ($request->seller > 0) {
            $seller = $request->seller;
        }

        $order->shipment_value = $request->shipment_value;
        $order->shipment_description = $request->shipment_description;
        $order->note = $request->note;
        //products is array objects [sku , quantity , price ]
        $products = [];
        foreach ($request->orders_item as $sku) {
            $products [] = [
                'id' => $sku,
                'quantity' => $request->get('quantity_' . $sku),
                'price' => $request->get('product_price_' . $sku)
            ];

        }
        $files = $request->file('files');
        $status = $request->status;
        $response = $this->update_order($order->uuid, $user->id, $request->address, $products, $request->payment_method, $request->type, $request->shipping_method, $seller, $files, $request->note, $request->coupon_code, $request->shipment_description, $request->shipment_value, $status);
        $data = [];
        if (isset($response['order'])) {
            if ($request->payment_method == Order::$stripe_link) {
                if (isset($data['order']) && !empty($data['order'])) {
                    $data['order']['stripe_url'] = !empty($order_payment) ? $order_payment->stripe_url : '';
                }
            } else {
                $data['order'] = $response['order'];
            }
            $data['order']['invoice_url'] = route('backend.orders.download', $response['order']->id);
        }
        $order->refresh();
        if ($last_status['payment_status'] != $order->payment_status) {
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
        return response()->data($data);

    }
    #endregion

    #region send pdf to user

    function send_pdf_to_user($id)
    {

        if (!permission_can('send pdf order', 'admin')) {
            return abort(403);
        }
        $this->sendByEmail($id);
        return redirect()->back()->with('success', trans('backend.global.sent_successfully'));
    }

    #endregion

    #region order payment approve
    function order_payment_approve(Request $request)
    {
        $id = $request->id;
        return $this->approve_wallet($id);
    }

    function order_payment_show(Request $request)
    {
        $wallet = UserWallet::query()->where('id', $request->id)->first();
        $files = empty($wallet->files) ? json_encode([]) : $wallet->files;
        $files = json_decode($files);
        $wallet->files = [];
        $files_data = [];
        if (!empty($files)) {
            foreach ($files as $item) {
                $files_data [] = asset("storage/" . $item);
            }
        }
        $wallet->files = $files_data;
        if (!empty($wallet->order_id)) {
            $order = Order::query()->where('id', $wallet->order_id)->first();
            $order->total = currency($order->total);
            $order->wallet = UserWallet::query()->where('order_id', $order->id)->where('status', UserWallet::$approve)->sum('amount');
            $order->payment_method = trans('backend.order.' . $order->payment_method);
//            $wallet->payment_method = $order->payment_method;
        } else {
            $order = new Order();
            $order->total = currency(0);
            $order->wallet = currency(0);
            $order->payment_method = trans('backend.order.transfer');
//            $order->payment_method = Order::$transfer;
        }
        if (!empty($wallet->order_payment_id)) {
            $order_payment = OrderPayment::query()->where('id', $wallet->order_payment_id)->first();
            $wallet->payment_method = trans('backend.order.stripe_link');
        }
        return response()->data(['wallet' => $wallet, 'order' => $order]);
    }

    function order_payment_update(Request $request)
    {

        $id = $request->id;
        $transfer_type = $request->transfer_type;
        $transfer_value = $request->transfer_type;
        $user_wallet = UserWallet::query()->where('id', $id)->first();
        if (empty($user_wallet) || $user_wallet->status == UserWallet::$approve) {
            return response()->error(trans('backend.wallet.you_cannot_edit_statement'));
        }
        if ($transfer_type == 'part_credit') {
            $transfer_value = $request->value_transfer;
            $user_wallet->status = UserWallet::$approve;
            $user_wallet->amount = $transfer_value;
            $user_wallet->save();

        } elseif ($transfer_type == 'credit') {
            $user_wallet->status = UserWallet::$approve;
            if ($user_wallet->amount > 0) {
                $user_wallet->amount = $user_wallet->amount * -1;
            }
            $user_wallet->amount = 0;
            $user_wallet->save();
        } else if ($transfer_type == 'total') {
            $user_wallet->status = UserWallet::$approve;
            if ($user_wallet->amount < 0) {
                $user_wallet->amount = $user_wallet->amount * -1;
            }
            $user_wallet->save();
        }
        //check order is paid  ;
        $user_wallet_order = UserWallet::query()->where('order_id', $user_wallet->order_id)->sum('amount');
        if (!empty($user_wallet->order_payment_id))
            OrderPayment::query()->where('id', $user_wallet->order_payment_id)->update(['status' => 'captured', 'amount' => $user_wallet->amount]);
        if ($user_wallet_order == 0) {
            if (!empty($user_wallet->order_id))
                Order::query()->where('id', $user_wallet->order_id)->update(['payment_status' => 'paid']);

        }


        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }
    #endregion

    #region refund order
    function order_refund($id)
    {
        if (!permission_can('refund order', 'admin')) {
            return abort(403);
        }
        $response = $this->refund_order($id);
        if ($response['success'] == true) {
            return redirect()->back()->with('success', trans('backend.order.refund_successfully'));
        } else {

            return redirect()->back()->with('success', $response['message']);
        }
    }
    #endregion

    #region update serial numbers

    function update_serial_numbers(Request $request)
    {
        $order_product_id = $request->order_product_id;
        $serials_numbers = $request->serials_numbers;

        ProductsSerialNumber::query()->where('order_product_id', $order_product_id)->update([
            'order_product_id' => null,
            'order_id' => null
        ]);
        $order_products = OrdersProducts::query()->find($order_product_id);
        ProductsSerialNumber::query()->whereIn('id', $serials_numbers)->update([
            'order_product_id' => $order_product_id,
            'order_id' => $order_products->order_id
        ]);

        return response()->data(['message' => 'test']);
    }

    #endregion

    #region shipping cost
    function get_shipping_cost(Request $request)
    {
        //validate ..
        $request->validate([
            'shipping_method' => 'required',
            'products' => 'required',
            'address' => 'required'
        ]);
        // get shipping cost ..
        $total_all_weight = 0;
        foreach ($request->products as $item) {
            $product = Product::query()->where('sku', $item['sku'])->first();
            if (!empty($product)) {
                $total_all_weight += ($product->weight * $item['quantity']);
            }
        }
        $address = Address::query()->where('id', $request->address)->first();

        $data = $this->shipping_cost($address->country_id, $total_all_weight, null, $request->shipping_method);
        return response()->data($data);
//        $shipping_cost = $this->get_shipping_cost();
    }
    #endregion

    //region cancel
    function cancel($uuid)
    {
        $order = Order::query()->where('uuid', $uuid)->first();
        if (empty($order)) {
            return redirect()->back()->with('error', trans('api.order.order_not_found'));
        }
        ProductsSerialNumber::query()->where('order_id' , $order->id)->update([
            'order_product_id'=>null,
            'order_id'=>null,
        ]);

        if (in_array($order->status, [Order::$on_hold, Order::$pending_payment, Order::$proforma])) {
            $this->refund_statements_order($order->id, $order, false);
            $user_wallet_count = UserWallet::query()
                ->where('order_id', $order->id)
                ->whereNot('amount', 0)
                ->where('type', UserWallet::$refund)->where('status', UserWallet::$approve)->count();
            if ($user_wallet_count == 0) {
                $order->status = Order::$canceled;
            } else {
                $order->status = Order::$refunded;
            }
            $order->save();
            return redirect()->back()->with('success', trans('backend.global.success_message.updated_successfully'));

        } else {
            return redirect()->back()->with('error', trans('backend.order.cant_change_status'));
        }

    }
    //endregion

}

