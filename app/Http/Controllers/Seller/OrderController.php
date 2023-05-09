<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ChangeOrderStatusRequest;
use App\Http\Requests\Seller\Auth\LoginRequest;
use App\Http\Requests\Seller\Order\AttributeRequest;
use App\Http\Requests\Seller\Order\CouponApply;
use App\Http\Requests\Seller\Order\CreateOrder;
use App\Http\Requests\Seller\Order\GetAddressRequest;
use App\Http\Requests\Seller\Order\GetCitiesRequest;
use App\Http\Requests\Seller\Order\PriceRequest;
use App\Http\Requests\Seller\Order\SetAddressRequest;
use App\Http\Requests\Seller\Order\UpdateOrder;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Attribute;
use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\CouponUsages;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ProductsPackages;
use App\Models\ProductsSerialNumber;
use App\Models\Seller;
use App\Models\SubAttribute;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\ZonePrice;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\InvoiceTrait;
use App\Traits\OrderTrait;
use App\Traits\PaymentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use function GuzzleHttp\Promise\queue;

//use App\Traits\NotificationTrait;

class OrderController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use OrderTrait;
    use PaymentTrait;
    use InvoiceTrait;

//    use NotificationTrait;

    #region index

    function index(Request $request)
    {
        $filters[] = 'user';
        $filters[] = 'payment_method';
        $filters[] = 'payment_status';
        $filters[] = 'status';
        $filters[] = 'is_free_shipping';
        $filters[] = 'coupon';
        $filters[] = 'product';
        $filters[] = 'type';
        $filters[] = 'seller';
        $filters[] = 'shipping_method';
        $filters[] = 'start_date';
        $filters[] = 'end_date';
        $filters[] = 'currency';

        $datatable_route = route('seller.orders.datatable', ['seller' => $request->seller, 'filter_status' => $request->filter_status]);
        #region data table  columns
        $datatable_columns = [];
        $datatable_columns['DT_RowIndex'] = 'DT_RowIndex';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['uuid'] = 'orders.uuid';
        $datatable_columns['user_uuid'] = 'users.uuid';
        $datatable_columns['type'] = 'type';
        $datatable_columns['payment_method'] = 'payment_method';
        $datatable_columns['payment_status'] = 'payment_status';
        $datatable_columns['status'] = 'status';
        $datatable_columns['total'] = 'total';
        $datatable_columns['balance'] = 'balance';
        $datatable_columns['shipping'] = 'shipping';
        $datatable_columns['seller_commission'] = 'seller_commission';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters, null, null, null, null, true, true, null, ['balance']);
        $create_button = $this->create_button(route('seller.orders.create'), trans('seller.orders.create_new_order'));

//        $orders = Order::query()->where(function ($q) use ($request) {
//            if ($request->seller != null && auth('seller')->user()->is_manager) {
//                $q->where('seller_id', $request->seller);
//
//            } else {
//
//
//                $q->where('seller_id', auth('seller')->user()->id)
//                    ->orWhereIn('seller_id', auth('seller')->user()->sellers()->pluck('id'));
//            }
//        })->get();
        $orders_count = Order::query()->where('seller_id', auth('seller')->user()->id)
            ->whereIn('status', [Order::$processing, Order::$completed])
            ->where('type', Order::$order)
            ->count();
        $total_orders_value = Order::query()->where('seller_id', auth('seller')->user()->id)->whereIn('status', [Order::$processing, Order::$completed])->where('type', Order::$order)->sum('total');
        $pending_payment_orders_count = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$pending_payment)->count();
        $pending_payment_orders_value = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$pending_payment)->sum('total');

        $completed_orders_value = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$completed)->sum('total');
        $completed_orders_count = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$completed)->count();
        $processing_orders_value = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$processing)->sum('total');
        $processing_orders_count = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$processing)->count();
        $on_hold_orders_value = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$on_hold)->sum('total');
        $on_hold_orders_count = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$on_hold)->count();
        $refund_orders_value = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$refunded)->sum('total');
        $refund_orders_count = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$refunded)->count();
        $cancelled_orders_value = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$canceled)->sum('total');
        $cancelled_orders_count = Order::query()->where('seller_id', auth('seller')->user()->id)->where('type', Order::$order)->where('status', Order::$canceled)->count();


        $statistics = [[
            'svg' => asset('backend/media/icons/duotune/finance/fin003.svg'),
            'sum' => currency($total_orders_value),
            'name' => __('backend.order.total'),
            'number' => $orders_count,
        ], [
            'svg' => asset('backend/media/icons/duotune/arrows/arr020.svg'),
            'sum' => currency($completed_orders_value),
            'name' => __('backend.order.completed'),
            'number' => $completed_orders_count,
        ], [
            'svg' => asset('backend/media/icons/duotune/general/gen012.svg'),
            'sum' => currency($processing_orders_value),
            'name' => __('backend.order.processing'),
            'number' => $processing_orders_count,
        ],
//            [
//            'svg' => asset('backend/media/icons/duotune/arrows/arr015.svg'),
//            'sum' => currency($failed_orders_value),
//            'name' => __('backend.order.failed'),
//            'number' => $failed_orders_count,
//        ],
            [
                'svg' => asset('backend/media/icons/duotune/arrows/arr014.svg'),
                'sum' => currency($pending_payment_orders_value),
                'name' => __('backend.order.pending_payment'),
                'number' => $pending_payment_orders_count,
            ], [
                'svg' => asset('backend/media/icons/duotune/general/gen013.svg'),
                'sum' => currency($on_hold_orders_value),
                'name' => __('backend.order.on_hold'),
                'number' => $on_hold_orders_count,
            ], [
                'svg' => asset('backend/media/icons/duotune/arrows/arr058.svg'),
                'sum' => currency($refund_orders_value),
                'name' => __('backend.order.refunded'),
                'number' => $refund_orders_count,
            ],
//            [
//                'svg' => asset('backend/media/icons/duotune/general/gen040.svg'),
//                'sum' => currency($cancelled_orders_value),
//                'name' => __('backend.order.canceled'),
//                'number' => $cancelled_orders_count,
//            ],
        ];
        $users_id = Order::where('seller_id', auth('seller')->id())->pluck('user_id');
        $users = User::whereIn('id', $users_id)->where('status', 1)->whereNull('deleted_at')->get();
        $payment_methods = Order::payment_methods();
        $payment_statuses = Order::payment_statuses();
        $products_ids = OrdersProducts::groupBy('product_id')->pluck('product_id');
        $products = Product::query()->where('status', 1)->whereIn('id', $products_ids)->get();
        $currencies = Currency::query()->where('status', 1)->get();
        $statuses = Order::statuses();
        $types = Order::types();
        $shipping_methods = Order::shipping_methods();

        return view('seller.order.index', compact('datatable_script', 'create_button', 'users', 'currencies',
            'statistics', 'payment_methods', 'payment_statuses', 'statuses', 'types', 'shipping_methods', 'products'));
    }

    function datatable(Request $request)
    {
        $model = Order::query()
            ->whereNotIn('orders.status', [Order::$canceled, Order::$failed, Order::$waiting])
            ->leftJoin('user_wallet', 'user_wallet.order_id', 'orders.id')
            ->where('orders.seller_id', auth('seller')->id())
            ->select('orders.*', 'users.uuid as user_uuid', \DB::raw('sum(user_wallet.amount) as balance'), 'currencies.symbol')
            ->join('users', 'users.id', 'orders.user_id')->groupBy('orders.id')
            ->where(function ($q) {
                $q->where('user_wallet.status', UserWallet::$approve)->orWhereNull('user_wallet.status');
            })
            ->leftJoin('currencies', 'currencies.id', 'orders.currency_id');

        if ($request->has('user') && !empty($request->user)) {

            $model->where(function ($q) use ($request) {
                $q->where('users.uuid', $request->user);
                $q->orWhere('users.id', $request->user);
            });
        }

        if ($request->status != null) {
            $model = $model->where('orders.status', $request->status);
        }
        if ($request->has('shipping_method') && $request->shipping_method != null) {
            $model = $model->where('shipping_method', $request->shipping_method);
        }
        if ($request->has('payment_status') && $request->payment_status != null) {
            $model = $model->where('payment_status', $request->payment_status);
        }
        if ($request->has('payment_method') && $request->payment_method != null) {
            $model = $model->where('payment_method', $request->payment_method);
        }
        if ($request->has('type') && $request->type != null) {
            $model = $model->where('type', $request->type);
        }

        if ($request->has('is_free_shipping') && $request->is_free_shipping != -1) {
            $model = $model->where('is_free_shipping', $request->is_free_shipping);
        }

        if ($request->has('coupon') && $request->coupon != -1) {
            $model = $model->where('has_coupon', $request->coupon);
        }

        if ($request->product != null) {
            $model = $model->whereIn('orders.id', function ($query) use ($request) {
                $query->seleيct('order_id')->from('orders_products')->where('orders_products.product_id', $request->product);
            });
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
        if ($request->start_date != null) {
            $model = $model->whereBetween('orders.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }
//        dd($model->count());
        //        if (auth('seller')->user()->is_manager) {
//
//            if ($request->seller == null)
//                $model = $model->orWhereIn('orders.seller_id', auth('seller')->user()->sellers()->pluck('id'));
//            $datatable->editColumn('seller_name', function ($q) {
//                return $q->seller_name;
//            });
//            $datatable->editColumn('seller_manager_commission', function ($q) {
//                return currency($q->seller_manager_commission);
//            });
//
//        }
        $datatable = datatables()->make($model);

        $permission_send_pdf = permission_can('send pdf order', 'seller');
        $permission_edit_order = permission_can('edit order', 'seller');
        $permission_refund_order = permission_can('refund order', 'seller');
        return $datatable
            ->addIndexColumn()
            ->editColumn('DT_RowIndex', function ($q) {
                return '';
            })
            ->editColumn('uuid', function ($q) {

                return '<span class="badge badge-light-dark badge-lg fw-bold  ">' . $q->uuid . '</span>';
            })
            ->editColumn('user_uuid', function ($q) {
                return '<span class="badge badge-light-info badge-lg fw-bold  ">' . $q->user_uuid . '</span>';
            })
            ->editColumn('total', function ($q) {

                $html = '<span class="badge badge-light-info badge-lg fw-bold  ">' . currency($q->total) . '</span>';

                if ($q->currency_id != null && $q->symbol != '$') {

                    $html = '  <span class="badge badge-light-info mt-1 badge-lg fw-bold"> ' . currency($q->total) . ' / ' . number_format($q->total_in_sale_currency, 2) . ' ' . $q->symbol . '</span>';

                }


                if ($q->coupon_id != "") {
                    $html .= '<span class="badge badge-light-danger  mt-1 badge-lg fw-bold  "> ' . ' <i class="las  text-danger la-gift"></i> ' . currency($q->coupon_value) . (empty($q->currency_id) && $q->currency_id != 1 ? '' : ' / ' . number_format($q->coupon_value * $q->exchange_rate, 2) . ' ' . $q->symbol) . '</span>';
                }

                return $html;

            })
            ->editColumn('shipping', function ($q) {
                $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . strtoupper($q->shipping_method) . '/' . currency($q->shipping) . '</span>';
                if ($q->currency_id != null && $q->symbol != '$') {

                    $html = '  <span class="badge badge-light-info mt-1 badge-lg fw-bold"> ' . strtoupper($q->shipping_method) . '/' . currency($q->shipping) . ' / ' . number_format($q->shipping * $q->exchange_rate, 2) . ' ' . $q->symbol . '</span>';

                }
                return $html;
            })
            ->editColumn('payment_method', function ($q) {
                if ($q->type == Order::$proforma) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                $html = '<span class="badge badge-light-dark badge-lg fw-bold  "> ' . trans('seller.orders.' . $q->payment_method) . '</span>';
                if ($q->payment_method == Order::$paypal) {
                    $html = '<span class="badge badge-light-primary badge-lg fw-bold  "><i class="lab la-paypal"></i> ' . trans('seller.orders.' . $q->payment_method) . '</span>';
                } elseif ($q->payment_method == Order::$stripe) {
                    $html = '<span class="badge badge-light-info badge-lg fw-bold  "><i class="lab la-stripe"></i> ' . trans('seller.orders.' . $q->payment_method) . '</span>';
                } elseif ($q->payment_method == Order::$stripe_link) {
                    $html = '<span class="badge badge-light-success badge-lg fw-bold  "><i class="lab la-stripe"></i> ' . trans('seller.orders.' . $q->payment_method) . '</span>';
                } elseif ($q->payment_method == Order::$transfer) {
                    $html = '<span class="badge badge-light-dark badge-lg fw-bold  "><i class="las la-money-bill-alt"></i> ' . trans('seller.orders.' . $q->payment_method) . '</span>';
                }

                return $html;
            })
            ->editColumn('type', function ($q) {
                $html = '';
                if ($q->type == Order::$order) {
                    $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->type . '</span>';

                } else {
                    $html = '<span class="badge badge-light-info badge-lg fw-bold  ">' . $q->type . '</span>';

                }
                return $html;
            })
            ->editColumn('payment_status', function ($q) {
                $class = "";
                if ($q->type == Order::$proforma) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                if ($q->payment_status == Order::$payment_status_paid) {
                    $class = 'badge-light-success';
                } elseif ($q->payment_status == Order::$payment_status_unpaid) {
                    $class = 'badge-light-warning';
                } else {
                    $class = 'badge-light-error';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  ">' . $q->payment_status . '</span>';
                return $html;
            })
            ->editColumn('status', function ($q) {
                $class = "";

                if ($q->type == Order::$proforma && $q->status != Order::$canceled) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }

                if ($q->status == Order::$completed) {
                    $class = 'badge-primary';
                } elseif ($q->status == Order::$on_hold || $q->status == Order::$waiting) {
                    $class = 'badge-warning';
                } elseif ($q->status == Order::$pending_payment) {
                    $class = 'badge-secondary';
                } elseif ($q->status == Order::$processing) {
                    $class = 'badge-success';
                } elseif ($q->status == Order::$failed) {
                    $class = 'badge-error';
                } else if ($q->status == Order::$refunded || $q->status == Order::$canceled) {
                    $class = 'badge-danger';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  ">' . trans('backend.order.' . $q->status) . '</span>';
                return $html;

            })
            ->editColumn('created_at', function ($q) {
                $html = '<span class="badge badge-light  badge-lg fw-bold  ">' . $q->created_at . '</span>';
                return $html;
            })
            ->addColumn('actions', function ($q) use ($permission_send_pdf, $permission_refund_order, $permission_edit_order) {
                $actions = '';
                //show
                $actions .= ' ' . $this->btn(route('seller.orders.show', ['order' => $q->uuid]), '', 'la la-eye', 'btn-warning btn-show btn-icon');
                //print
                $actions .= ' ' . $this->btn(route('seller.orders.download', $q->uuid), '', 'fonticon-printer fs-x', 'btn-secondary btn-sm btn-show btn-icon');
                //send
                if ($permission_send_pdf) {
                    $actions .= ' ' . $this->btn(route('seller.orders.send.pdf', ['uuid' => $q->uuid]), '', 'la la-paper-plane', 'btn-primary btn-show    btn-sm btn-icon');
                }
                //edit
                if (abs($q->balance) == abs($q->total) && in_array($q->status, [Order::$pending_payment, Order::$on_hold, Order::$processing]) && $permission_edit_order) {
                    $actions .= ' ' . $this->edit_button(route('seller.orders.edit', $q->uuid));
                }
                //refund
                if ($permission_refund_order && $q->type == Order::$order && in_array($q->status, [Order::$completed, Order::$processing])) {
                    $actions .= ' <a  href="' . route('seller.orders.refund', $q->uuid) . '"  class="btn btn-light-danger btn-sm  btn-hover-rise" ><i class="las la-redo"></i> ' . trans('backend.order.refund') . '</a> ';

                }
                //cancel
                if ($permission_edit_order && (($q->type == Order::$order && in_array($q->status, [Order::$on_hold, Order::$pending_payment])) || ($q->type == Order::$proforma && $q->status != Order::$canceled))) {
                    $actions .= ' <a  href="' . route('seller.orders.cancel', $q->uuid) . '"  class="btn btn-light-dark btn-sm  btn-hover-rise" ><i class="las la-times"></i> ' . trans('backend.order.cancel') . '</a>';
                }


                return $actions;
            })
            ->editColumn('seller_commission', function ($q) {
                if ($q->type == Order::$proforma) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                return currency($q->seller_commission);
            })
            ->editColumn('balance', function ($q) {
                if ($q->type == Order::$proforma) {
                    $class = 'badge-dark';
                    return '<span class="badge ' . $class . ' badge-lg fw-bold  small "><i class="la la-minus"></i></span>';
                }
                return '<span class="badge badge-light-dark badge-lg fw-bold small ">' . ($q->balance == null ? "-" : currency($q->balance)) . '</span>';

            })
            ->rawColumns(['actions', 'user_uuid', 'uuid', 'balance', 'seller_commission', 'status', 'payment_method', 'payment_status', 'type', 'created_at', 'total', 'shipping', 'tracking_number'])
            ->toJson();
    }

    #endregion

    #region create

    function create()
    {
        $data_products = Product::query()->where('status', 1)->where('quantity', '>', 0)->get();
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
                'blocked_countries_names' => $blocked_countries,
            ];
        }
        $countries = Country::query()->where('status', 1)->get();
        $sellers = Seller::query()->where('status', 1)->where('seller_manger', auth('seller')->id())->pluck('id');
        $users = User::query()->where('status', 1)->where(function ($q) use ($sellers) {
            $q->whereIn('seller_id', $sellers)->orWhere('seller_id', auth('seller')->id());
        })->get();
        $currencies = Currency::query()->where('status', 1)->get();

        return view('orders.create', compact('users', 'currencies', 'countries', 'products'));
    }

    #region attribute
    function attribute(AttributeRequest $request)
    {
        $product = Product::query()->where('sku', $request->sku)->first();
        $sub_attribute_ids = ProductsAttribute::query()
            ->where('product_id', $product->id)
            ->groupBy('sub_attribute_id')->pluck('sub_attribute_id');
        $attributes = Attribute::query()
            ->select('attributes.*')
            ->join('sub_attributes', 'sub_attributes.attribute_id', 'attributes.id')
            ->whereIn('sub_attributes.id', $sub_attribute_ids)
            ->groupBy('attributes.id')
            ->get();
        $result = [];
        foreach ($attributes as $attribute) {
            $res = [];
            $res['title'] = $attribute->name;
            $res['id'] = $attribute->id;
            $res['values'] = [];
            $sub_attrs = SubAttribute::query()->whereIn('id', $sub_attribute_ids)->where('attribute_id', $attribute->id)->get();
            foreach ($sub_attrs as $item) {
                $res['values'][] = ['id' => $item->id, 'title' => $item->value];
            }
            $result[] = $res;
        }
        return response()->data(['result' => $result]);
    }
    #endregion

    #region address
    function get_address(GetAddressRequest $request)
    {
        $user = User::query()->where('uuid', $request->uuid)->first();
        $addresses = Address::query()
            ->where('user_id', $user->id)->get();
        $result = [];
        foreach ($addresses as $address) {
            $country = $address->get_country();
            $city = $address->city;
            $result[] = [
                'id' => $address->id,
                'text' => $country . "/" . (!empty($city) ? $city . "/" : '') . (!empty($address->address) ? $address->address : ""),
                'country' => $country,
                'country_id' => $address->country_id,
                'city' => $city,
                'address' => $address->address,
                'postal_code' => $address->postal_code
            ];
        }
        return response()->data(['addresses' => $result]);
    }

    function get_city(GetCitiesRequest $request)
    {
        $cities = City::query()->where('country_id', $request->country)->where('status', 1)->get();
        $result = [];
        foreach ($cities as $city) {
            $result [] = ['id' => $city->id, 'text' => $city->name];
        }
        return response()->data(['cities' => $result]);
    }

    function set_address(SetAddressRequest $request)
    {
        $user = User::query()->where('uuid', $request->user_id)->first();
        $address = new Address();
        $address->user_id = $user->id;
        $address->country_id = $request->country;
        $address->city = $request->city;
        $address->phone = $request->number;
        $address->address = $request->address;
        $address->postal_code = $request->postal_code;
        $address->street = $request->address_street;
        $address->state = $request->address_state;
        $address->save();
        return response()->data(['message' => trans('backend.global.success_message.created_successfully')]);
    }
    #endregion

    #region price
    function get_price(PriceRequest $request)
    {
        $order_uuid = $request->order_uuid;
        $order_product = null;

        $address = Address::find($request->address);
        $zone_id = $address->country->zone_id;
        $product = Product::query()
            ->where('status', 1)
            ->where('sku', $request->sku)->first();
        if (empty($product)) {
            return response()->error(trans('seller.orders.product_not_found'));
        }

        if (!empty($order_uuid)) {
            $order = OrdersProducts::query()
                ->select('orders_products.quantity')
                ->join('orders', 'orders.id', 'orders_products.order_id')
                ->where('orders.uuid', $order_uuid)
                ->where('orders_products.product_id', $product->id)
                ->first();

        }

        $price = empty($product->sale_price) ? $product->price : $product->sale_price;
        $quantity = $request->quantity;


        $packege = ProductsPackages::query()
            ->where('from', '<=', $quantity)
            ->where('to', '>=', $quantity)
            ->where('product_id', $product->id)
            ->first();
        if (!empty($packege)) {
            $price = $packege->price;
        }
        $product_rate = auth('seller')->user()->seller_product_rate;
        $weight_waiting = 0;
        if ($quantity <= $product->quantity) {
            $weight_avalibale = $product->weight * $quantity;
        } else {
            $weight_avalibale = $product->weight * $product->quantity;
            $weight_waiting = $product->weight * ($quantity - $product->quantity);
        }
        $shipping_price = 0;
        $dhl = 0;
        $aramex = 0;
        $fedex = 0;
        $ups = 0;

        $shipping_price_dhl = 0;
        $shipping_price_aramex = 0;
        $shipping_price_fedex = 0;
        $shipping_price_ups = 0;
        if ($product->is_free_shipping != 1) {

            $this->shipping_cost($address->country_id, $weight_avalibale);
            $shipping_price_dhl = $this->shipping_cost($address->country_id, $weight_avalibale, false, Order::$DHL);
            $shipping_price_dhl = $shipping_price_dhl['shipping'];
            $shipping_price_aramex = $this->shipping_cost($address->country_id, $weight_avalibale, false, Order::$Aramex)['shipping'];
            $shipping_price_fedex = $this->shipping_cost($address->country_id, $weight_avalibale, false, Order::$FedEx)['shipping'];
            $shipping_price_ups = $this->shipping_cost($address->country_id, $weight_avalibale, false, Order::$UPS)['shipping'];
            switch (get_setting('shipping_default')) {
                case Order::$DHL:
                    $shipping_price = $shipping_price_dhl;
                    break;
                case Order::$Aramex:
                    $shipping_price = $shipping_price_aramex;
                    break;
                case Order::$FedEx:
                    $shipping_price = $shipping_price_fedex;
                    break;
                case Order::$UPS:
                    $shipping_price = $shipping_price_ups;
                    break;
            }
        }
        $min_price_for_unit = $price - ($price * ($product_rate / 100));


        return response()->data([
            'quantity' => $product->quantity + (empty($order) ? 0 : $order->quantity),
            'unit_price' => $price,
            'total' => $quantity * $price,
            'shipping_price' => $shipping_price,
            'shipping_methods' => [
                'dhl' => $shipping_price_dhl,
                'aramex' => $shipping_price_aramex,
                'fedex' => $shipping_price_fedex,
                'ups' => $shipping_price_ups
            ],
            'min_price_for_unit' => $min_price_for_unit,
            'total_min_price' => $min_price_for_unit * $quantity,
            'packege' => $packege,
        ]);
    }

    #endregion

    #region coupon
    function apply_coupon(CouponApply $request)
    {
        $code = $request->coupon_code;
        $coupon = Coupon::query()
            ->where('code', $code)
            ->where('status', 1)
            ->where('starts_at', '<=', date('Y-m-d H:i:s'))
            ->where('ends_at', '>=', date('Y-m-d H:i:s'))
            ->first();
        if (empty($coupon)) {
            return response()->error(trans('seller.orders.coupon_is_not_available'));
        }
        $coupon_used = CouponUsages::query()->where('coupon_id', $coupon->id)->count();
        if ($coupon_used >= $coupon->max_use) {
            return response()->error(trans('seller.orders.this_coupon_has_expired'));
        }
        $user = User::query()->where('uuid', $request->user_id)->first();
        if (empty($user)) {
            return response()->error(trans('seller.orders.not_found_user'));
        }
        $coupon_used_per_user = CouponUsages::query()->where('user_id', $user->id)->where('coupon_id', $coupon->id)->count();
        if ($coupon_used_per_user >= $coupon->per_user) {
            return response()->error(trans('seller.orders.this_coupon_has_expired'));
        }
        $products = [];
        if ($coupon->type == Coupon::$Product) {
            $products = Product::query()->whereIn('id', $coupon->products_ids)->pluck('sku');
        }
        $note = "";
        if (!empty($coupon->minimum_shopping)) {
            $note = "the minimum shopping is <span data-amount='{$coupon->minimum_shopping}'  class='minimum_shopping'>{$coupon->minimum_shopping}</span> <span class='currency'>$</span>,<br> If your shopping cart is less than these  <span data-amount='{$coupon->minimum_shopping}' class='minimum_shopping'>{$coupon->minimum_shopping}</span>  <span class='currency'>$</span> <br> the discount will not be applied ";
        }
        return response()->data(['coupon' => $coupon, 'products' => $products, 'note' => $note]);
    }

    #endregion

    function store(CreateOrder $request)
    {
        $exchange_rate = $request->currency_rate;
        $currency = Currency::query()->where('status', 1)->where('symbol', $request->currency_symbol)->first();


        $user = User::query()->where('uuid', $request->user)->first();
        $address = Address::query()->where('id', $request->address)->first();
        $total = 0;
        $products = [];
        $type = $request->type;
        $shipping_method = $request->shipping_method;
        $payment_method = $request->payment_method;
        $note = $request->note;
        foreach ($request->orders_item as $item) {
            $product = [];
            $product['id'] = $item;
            $product['price'] = $request->get('product_price_' . $item);
            $product['quantity'] = $request->get('quantity_' . $item);
            $products[] = $product;
        }

        $files = $request->hasFile('payment_files') ? $request->file('payment_files') : [];
        $coupon_code = $request->has('coupon_code') ? $request->coupon_code : "";
        $status = $request->status;
        $shipment_value = $request->shipment_value;
        $shipment_description = $request->shipment_description;

        $response = $this->create_orders($user->id, $address->id, $products, $payment_method, $type, $shipping_method, auth('seller')->id(), $files, $note, $coupon_code, '', $status, $shipment_value, $shipment_description, $currency->symbol, $exchange_rate);
        if (!$response) {
            return response()->error(trans('api.order.cart_is_empty'));
        }
        //        dd($response);
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
            $data['order']['invoice_url'] = route('seller.orders.download', $response['order']->uuid);
        }

        return response()->data($data);
    }
    #endregion

    #region show
    public function show($id)
    {
        $order = Order::query()->where('uuid', $id)->firstOrFail();

        $products = $order?->order_products()->whereNull('orders_products.parent_id')->get();


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
        return view('orders.show', compact('order', 'currency', 'order_payment_status', 'currency', 'order_balance', 'files', 'products', 'address', 'qr', 'user', 'order_statuses'));
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
    }

    #endregion

    #region edit orders
    function edit($uuid)
    {
        $order = Order::query()
//            ->where('status', Order::$pending_payment)
            ->where('seller_id', auth('seller')->id())
            ->where('uuid', $uuid)->first();

        if (in_array($order->status, [Order::$refunded, Order::$canceled, Order::$completed])) {
            return redirect()->route('seller.orders.index')->with('error', trans('backend.order.cannot_change_this_order'));
        }
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
        $order_product_quantity = OrdersProducts::query()->where('order_id', $order->id)->pluck('product_id', 'quantity')->toArray();
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
        $sellers = Seller::query()->where('seller_manger', auth('seller')->id())->pluck('id');

        $users = User::query()->where(function ($q) use ($order) {
            $q->where('status', 1);
            $q->orWhere('id', $order->user_id);
        })->where(function ($q) use ($sellers) {
            $q->whereIn('seller_id', $sellers)->orWhere('seller_id', auth('seller')->id());
        })->get();
        $coupon = Coupon::query()->where('id', $order->coupon_id)->first();
        foreach ($order_product as $item) {
            $item->price = $item->price / $item->quantity;
        }
        $coupon_products = [];

        if (!empty($coupon) && $coupon->type == Coupon::$Product) {
            $coupon_products = Product::query()->whereIn('id', $coupon->products_ids)->pluck('sku');
        }

        $currency = Currency::query()->where('id', $order->currency_id)->first();

        return view('orders.edit', compact('users', 'order_statuses', 'currency', 'coupon_products', 'countries', 'products', 'address', 'order', 'coupon', 'files', 'order_product'));
    }

    function update(UpdateOrder $request, $uuid)
    {
        $order_uuid = $uuid;
        $user = User::query()->where('uuid', $request->user)->first();

//        $address = Address::query()->where('id', $request->address)->first();
        $total = 0;
        $products = [];
        $type = $request->type;
        $shipping_method = $request->shipping_method;
        $shipping_method = $request->shipping_method;
        $payment_method = $request->payment_method;
        $note = $request->note;
        foreach ($request->orders_item as $item) {
            $product = [];
            $product['id'] = $item;
            $product['price'] = $request->get('product_price_' . $item);
            $product['quantity'] = $request->get('quantity_' . $item);
            $product['attributes'] = $request->has('product_attributes_' . $item) ? $request->get('product_attributes_' . $item) : [];
            $products[] = $product;
        }
        $files = $request->hasFile('payment_files') ? $request->file('payment_files') : [];
        $coupon_code = $request->has('coupon_code') ? $request->coupon_code : "";
        $shipment_value = $request->shipment_value;
        $shipment_description = $request->shipment_description;
        $status = $request->status;

        $response = $this->update_order($order_uuid, $user->id, $request->address, $products, $payment_method, $type, $shipping_method, auth('seller')->id(), $files, $note, $coupon_code, $shipment_description, $shipment_value, $status);
        $data = [];
        if (isset($response['order'])) {
//            $this->payment_wallet($user->id, ($response['order']->total) * -1, 'order', 'approve', Seller::class, auth('seller')->id(), $response['order']->id);
            if ($payment_method == Order::$stripe_link) {
                if (isset($data['order']) && !empty($data['order'])) {
//                    $order_id = $data['order']->id;
//                    $order_payment = OrderPayment::query()->where('order_id', $order_id)->where('payment_method', Order::$stripe_link)->first();
                    $data['order']['stripe_url'] = !empty($order_payment) ? $order_payment->stripe_url : '';
//                    $this->payment_wallet($user->id, ($response['order']->total), 'order', 'pending', Seller::class, auth('seller')->id(), $response['order']->id, $order_payment->id);
                }
            } else {
                $data['order'] = $response['order'];
//                if ($payment_method == Order::$transfer && $request->has('payment_files')) {
//                    $order_payment = OrderPayment::query()->where('order_id', $response['order']->id)->where('payment_method', Order::$transfer)->first();
//                    $this->payment_wallet($user->id, ($response['order']->total), 'order', 'pending', Seller::class, auth('seller')->id(), $response['order']->id, $order_payment->id);
//                }
            }
            $data['order']['invoice_url'] = route('seller.orders.download', $response['order']->id);
        }
        return response()->data($data);
    }
    #endregion

    #region send pdf to user
    function send_pdf_to_user($uuid)
    {
        $order = Order::query()->where('uuid', $uuid)->first();
        $this->sendByEmail($order->id);
        return redirect()->back()->with('success', trans('backend.global.sent_successfully'));
    }

    #endregion

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

    #region status
    public function change_status(ChangeOrderStatusRequest $request, $id)
    {
        $order = Order::query()->where('uuid', $id)->first();
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
            }
            if (($order->status == Order::$completed || $order->status == Order::$processing) && $order->payment_status == Order::$payment_status_paid) {

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

                        $user_wallet->create_by_type = Seller::class;
                        $user_wallet->create_by_id = auth('seller')->id();
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

        }

        $order->save();
        if ($order->payment_status == Order::$payment_status_paid && !empty($order->seller_id)) {
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

        return redirect()->route('seller.orders.index')->with('success', trans('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region refund order
    function order_refund($id)
    {
        if (!permission_can('refund order', 'seller')) {
            return abort(403);
        }
        $order = Order::query()->where('uuid', $id)->first();

        $response = $this->refund_order($order->id);
        if ($response['success'] == true) {
            return redirect()->back()->with('success', trans('backend.order.refund_successfully'));
        } else {

            return redirect()->back()->with('success', $response['message']);
        }
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
//            if ($user_wallet->amount > 0) {
//                $user_wallet->amount = $user_wallet->amount * -1;
//            }
            $user_wallet->amount = 0;
            $user_wallet->save();
        } else if ($transfer_type == 'total') {
            $user_wallet->status = UserWallet::$approve;
            if ($user_wallet->amount < 0) {
                $user_wallet->amount = $user_wallet->amount * -1;
            }
//            $user_wallet->amount = 0;

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


    function cancel($uuid)
    {
        $order = Order::query()
            ->where('uuid', $uuid)
            ->where('seller_id', auth('seller')->id())
            ->whereIn('status', [Order::$on_hold, Order::$proforma, Order::$pending_payment])->first();

        if (empty($order)) {
            return redirect()->back()->with('error', trans('api.order.order_not_found'));
        }

        ProductsSerialNumber::query()->where('order_id' , $order->id)->update([
            'order_product_id'=>null,
            'order_id'=>null,
        ]);
        $this->refund_statements_order($order->id, $order, false);
//        UserWallet::query()->where('order_id', $order->id)->update(['status' => UserWallet::$cancelled]);
//        OrderPayment::query()->where('order_id', $order->id)->update(['status' => UserWallet::$cancelled]);
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

    }

    #region change to order
    public function change_to_order(Request $request, $uuid)
    {
        $order = Order::query()
            ->where('uuid', $uuid)
            ->where('type', Order::$proforma)
            ->where('status', Order::$proforma)
            ->first();
        if ($request->has('payment_method') && $request->payment_method == 'stripe_link') {
            $stripe_link = $this->change_proforma_to_order_stripe_link($order->id);
            return response()->data(['message' => trans('backend.global.success_message.updated_successfully'), 'reload' => 0, 'stripe_link' => $stripe_link]);
        } else {
            $this->change_proforma_to_order($order->id);
            return response()->data(['message' => trans('backend.global.success_message.updated_successfully'), 'reload' => 1]);
        }
    }
    #endregion
}
