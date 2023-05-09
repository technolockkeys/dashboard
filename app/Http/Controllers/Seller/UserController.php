<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\User\WalletPaymentChangeStatusRequest;
use App\Http\Requests\Backend\User\WalletPaymentGetRequest;
use App\Http\Requests\Backend\User\WalletPaymentInfoRequest;
use App\Http\Requests\Backend\User\WalletPaymentSetRequest;
use App\Http\Requests\Seller\User\Addresses\DeleteRequest;
use App\Http\Requests\Seller\User\Addresses\CreateRequest as CreateRequestAddress;
use App\Http\Requests\Seller\User\Addresses\EditRequest as EditRequestAddress;
use App\Http\Requests\Seller\User\CreateRequest;
use App\Http\Requests\Seller\User\Payment\CreateReqeust as CreateRequestPayment;
use App\Mail\PaymentReminder;
use App\Mail\SendStatementMail;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserWallet;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\PaymentTrait;
use App\Traits\SetMailConfigurations;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Tag\Q;

class UserController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use PaymentTrait;
    use SetMailConfigurations;
    use UserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!permission_can('show seller users', 'seller')) {
            return abort(403);
        }
        $datatable_route = route('seller.users.datatable');
        $delete_all_route = false;
        $filters = [];
        $datatable_columns = [];
        $datatable_columns['index'] = '';
        $datatable_columns['uuid'] = 'uuid';
        $datatable_columns['name'] = 'name';
        $datatable_columns['email'] = 'email';
        $datatable_columns['phone'] = 'phone';
        $datatable_columns['orders_count'] = 'orders_count';
        $datatable_columns['purchase_value'] = 'purchase_value';
        $datatable_columns['avg_purchase_value'] = 'avg_purchase_value';
        $datatable_columns['actions'] = 'actions';
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $countries = Country::query()->where('status', 1)->get();
        return view('seller.user.index', compact('datatable_script', 'countries'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show seller users', 'seller')) {
            return abort(403);
        }
        $orders_sum = Order::query()
            ->selectRaw('count(*) as orders , user_id')
            ->whereNull('deleted_at')
            ->groupBy('seller_id');
        $model = User::query()
            ->select('users.*')
            ->leftJoin('orders', function ($join) {
                $join->on('orders.user_id', 'users.id');

            })
            ->select('users.*',
                DB::raw('count(orders.total) as orders_count'),
                DB::raw("COALESCE( sum(orders.total),'0') as purchase_value"),
                DB::raw("COALESCE( avg(orders.total),'0') as avg_purchase_value")
            )
            ->where(function ($q) {
                $q->where('orders.type', Order::$order)->orWhereNull('orders.type');
            })
            ->where('users.seller_id', auth('seller')->id())
            ->groupBy('users.id');
        return datatables()->make($model)
            ->filterColumn('orders_count', function ($query, $keyword) {

            })
            ->filterColumn('purchase_value', function ($query, $keyword) {

            })->filterColumn('avg_purchase_value', function ($query, $keyword) {

            })
            ->addColumn('index', function () {
                return '';
            })
            ->addColumn('name', function ($q) {
                return '<div class="d-flex align-items-center"><div class="symbol symbol-45px me-5">
                        <img style="width: 25px !important;height: 25 !important;" src="' . asset($q->avatar) . '" onerror="this.src=' . "'" . asset('backend/media/avatars/blank.png') . "'" . '" alt=""></div>
                        <div class="d-flex justify-content-start flex-column">
                        <a href="' . route('seller.users.show', ['user' => $q->uuid]) .
                    '" class="  ">
                            <span class="text-dark   ">' . $q->name . '</span></a>

                        </div></div>';
            })
            ->addColumn('orders_count', function ($q) {
                return (empty($q->orders_count) ? '<span class="badge badge-secondary">-' : '<span class="badge badge-primary">' . $q->orders_count) . '</span>';
            })
            ->editColumn('purchase_value', function ($q) {
                return (empty($q->purchase_value) ? '<span class="badge badge-secondary">-' : '<span class="badge badge-info">' . currency($q->purchase_value)) . '</span>';
            })
            ->editColumn('avg_purchase_value', function ($q) {
                return (empty($q->avg_purchase_value) ? '<span class="badge badge-secondary">-' : '<span class="badge badge-dark">' . currency($q->avg_purchase_value)) . '</span>';
            })
            ->addColumn('actions', function ($q) {
                $html = '';
                if (permission_can('show seller user', 'seller')) {

                    $html = '<a href="' . route('seller.users.show', $q->uuid) . '" data-id="' . $q->uuid . '"  class="btn show-user btn-icon btn-primary btn-sm"><i class="fa fa-eye"></i></b>';
//                $html .= ' <a   class="btn btn-icon btn-info btn-sm"><i class="fa fa-address-book"></i></a>';
                }
                return $html;
            })
            ->rawColumns(['actions', 'name', 'orders_count', 'purchase_value', 'avg_purchase_value'])
            ->toJson();

    }

    public function store(CreateRequest $request)
    {
        if (!permission_can('create seller user', 'seller')) {
            return abort(403);
        }
        $user = new User();
        $user->name = $request->full_name;
        $user->country_id = $request->country;
        $user->city = $request->city;
        $user->provider_type = 'email';
        $user->email = $request->email_address;
        $user->phone = $request->phone_number;
        $user->address = $request->full_address;
        $user->street = $request->street;
        $user->company_name = $request->company_name;
        $user->postal_code = $request->postal_code;
        $user->website_url = $request->website_url;
        $user->type_of_business = $request->type_of_business;
        $user->state = 1;
        $user->seller_id = auth('seller')->id();
        $user->avatar = '';
        $user->save();
        if ($request->has('avatar')) {
            try {
                if (!file_exists(public_path('storage/user'))) {
                    mkdir(public_path('storage/user'), true);
                }
            } catch (\Exception $e) {
            }
            $image_data = $user->StoreAvatarImage('avatar', $user->id, 'user');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $user->avatar = $avatar_link;
        }
        $user->save();
        //create address
        $address = new Address();
        $address->user_id = $user->id;
        $address->country_id = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->street = $request->street;
        $address->address = $request->full_address;
        $address->phone = $request->phone_number;
        $address->postal_code = $request->postal_code;
        $address->is_default = 1;
        $address->save();
        return response()->data(['message' => trans('backend.global.success_message.created_successfully')]);
    }

    public function show($id)
    {
        if (!permission_can('show seller user', 'seller')) {
            return abort(403);
        }
        $user = User::query()->where('uuid', $id)->where(function ($q) {
            $q->where('seller_id', auth('seller')->id());
            if (auth('seller')->user()->is_manager) {
                $sellers = Seller::query()->where('seller_manger', auth('seller')->id())->pluck('id');
                $q->orWhereIn('seller_id', $sellers);
            }
        })->first();
        if (empty($user)) {
            return abort(404);
        }
        $countries = Country::query()->where('status', 1)->get();

        #region address
        $datatable_route = route('seller.users.address.datatable', $id);
        $delete_all_route = false;
        $filters = [];
        $datatable_columns = [];
        $datatable_columns['index'] = '';
        $datatable_columns['country_id'] = 'country_id';
        $datatable_columns['state'] = 'state';
        $datatable_columns['city'] = 'city';
        $datatable_columns['address'] = 'address';
        $datatable_columns['street'] = 'street';
        $datatable_columns['postal_code'] = 'postal_code';
        $datatable_columns['phone'] = 'phone';
        $datatable_columns['actions'] = 'actions';
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters, null, null, 'address_table', null, true, false, '_addresses');
        #endregion

        #region order
        $datatable_route = route('seller.orders.datatable', ['user' => $id, 'seller' => auth('seller')->id()]);
        #region data table  columns
        $datatable_columns_order = [];
        $datatable_columns_order['DT_RowIndex'] = '';
        $datatable_columns_order['uuid'] = 'uuid';
//        $datatable_columns_order['user_uuid'] = 'user_uuid';
        $datatable_columns_order['type'] = 'type';
//        $datatable_columns_order['payment_method'] = 'payment_method';
//        $datatable_columns_order['payment_status'] = 'payment_status';
        $datatable_columns_order['total'] = 'total';
//        $datatable_columns_order['shipping'] = 'shipping';
        $datatable_columns_order['seller_commission'] = 'seller_commission';
        $datatable_columns_order['status'] = 'status';
        $datatable_columns_order['created_at'] = 'created_at';
        #endregion
        $datatable_script_order = $this->create_script_datatable($datatable_route, $datatable_columns_order, false, [], false, null, 'datatable_orders', false, true, false, '_order');
        #endregion

        #region payment orders
        $refund = $user->wallet()->whereIn('type', ['refund', 'withdraw'])->get();
        $approved_order = $user->wallet()->where('type', 'order')->where('amount', '>', 0)->where('status', 'approve')->get();
        $failed_order = $user->wallet()->where('type', 'order')->where('status', Order::$failed)->get();

        $statistics = [
            [
                'svg' => asset('backend/media/icons/duotune/arrows/arr070.svg'),
                'sum' => currency(UserWallet::query()->where('user_id', $user->id)->where('status', 'approve')->sum('amount')),
                'name' => __('backend.order.balance'),
                'number' => '-',
            ],
            [
                'svg' => asset('backend/media/icons/duotune/arrows/arr059.svg'),
                'sum' => currency($refund->sum('amount')),
                'name' => __('backend.seller.refund'),
                'number' =>  $user->wallet()->whereIn('type', ['refund'])->count(),
            ],
            [
                'svg' => asset('backend/media/icons/duotune/arrows/arr084.svg'),
                'sum' => currency($approved_order->sum('amount')),
                'name' => __('backend.user.approved'),
                'number' => $approved_order->count(),
            ],
            [
                'svg' => asset('backend/media/icons/duotune/arrows/arr011.svg'),
                'sum' => currency($failed_order->sum('amount')),
                'name' => __('backend.order.failed'),
                'number' => $failed_order->count(),
            ]
        ];


        $datatable_route = route('seller.users.payment_recodes.index', ['user' => $id, 'seller' => auth('seller')->id()]);
        #region data table columns
        $datatable_columns_payment_recode = [];
        $datatable_columns_payment_recode['index'] = '';
        $datatable_columns_payment_recode['type'] = 'type';
        $datatable_columns_payment_recode['uuid'] = 'orders.uuid';
        $datatable_columns_payment_recode['amount'] = 'amount';
        $datatable_columns_payment_recode['status'] = 'status';
        $datatable_columns_payment_recode['created_at'] = 'created_at';
        $datatable_columns_payment_recode['note'] = 'note';
        $datatable_columns_payment_recode['action'] = 'action';
        #endregion
        $datatable_script_payment = $this->create_script_datatable($datatable_route, $datatable_columns_payment_recode, false, [], false, null, 'user_payment', false, true, false, '_payment');

        #endregion

        $orders = Order::query()
            ->select('orders.created_at', DB::raw('sum(user_wallet.amount) as total'), 'orders.uuid', 'orders.created_at')
            ->join('user_wallet', 'user_wallet.order_id', 'orders.id')
            ->where('orders.user_id', $user->id)
            ->groupBy('user_wallet.order_id')
            ->get();
        return view('seller.user.show', compact('user', 'statistics', 'countries', 'datatable_script', 'orders', 'datatable_script_order', 'datatable_script_payment'));

    }

    public function update(Request $request, $id)
    {
//        this id is uuid
        $user = User::query()->where('uuid', $id)->first();

        $request->validate([
            'full_name' => 'required',
            'country' => 'required',
            'city' => 'required',
            'street' => 'required',
            'full_address' => 'required',
            'email_address' => 'required|unique:users,email,' . $user->id,
            'phone' => 'required|unique:users,phone,' . $user->id,
        ]);
        $user = User::query()->where('uuid', $id)->first();
        $user->country_id = $request->country;
        $user->email = $request->email_address;
        $user->city = $request->city;
        $user->address = $request->full_address;
        $user->state = $request->state;
        $user->type_of_business = $request->type_of_business;
        $user->street = $request->street;
        $user->website_url = $request->website_url;
        $user->name = $request->full_name;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;
        $user->company_name = $request->company_name;
        $address = Address::query()->where('is_default', 1)->where('user_id', $user->id)->first();
        if (!$address) {
            $address = new Address();
        }
        $address->country_id = $request->country;
        $address->city = $request->city;
        $address->address = $request->full_address;
        $address->state = $request->state;
        $address->is_default = 1;
        $address->phone = $request->phone;
        $address->postal_code = $request->postal_code;
        $address->street = $request->street;
        $address->user_id = $user->id;

        $address->save();


        $avatar_link = $user->avatar;
        if ($request->has('avatar')) {
            try {
                if (!file_exists(public_path('storage/user'))) {
                    mkdir(public_path('storage/user'), true);
                }
            } catch (\Exception $e) {
            }
            $image_data = $user->StoreAvatarImage('avatar', $user->id, 'user');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $user->avatar = asset($avatar_link) . '?t=' . time();
        }
        $user->save();
        $user->refresh();
        $user->country = Country::query()->where('id', $user->country_id)->first()?->name;
        $user->image_url = asset($avatar_link) . '?t=' . time();
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully'), 'user' => $user]);
    }

    #region addresses
    //datatable
    function address_datatable(Request $request, $uuid)
    {
        $model = Address::query()
            ->select('addresses.*')
            ->join('users', 'users.id', 'addresses.user_id')->where('users.uuid', $uuid);
        return datatables()
            ->make($model)
            ->addColumn('index', function ($q) {
                return '';
            })
            ->editColumn('country_id', function ($q) {
                return $q->get_country();
            })
            ->editColumn('state', function ($q) {
                if (empty($q->state)) {
                    return '<span class="badge badge-light-danger fs-8 fw-bolder">' . trans('backend.global.not_found') . '</span>';
                } else {
                    return $q->state;
                }
            })
            ->editColumn('street', function ($q) {
                if (empty($q->street)) {
                    return '<span class="badge badge-light-danger fs-8 fw-bolder">' . trans('backend.global.not_found') . '</span>';
                } else {
                    return $q->street;
                }
            })
            ->editColumn('address', function ($q) {
                if (empty($q->address)) {
                    return '<span class="badge badge-light-danger fs-8 fw-bolder">' . trans('backend.global.not_found') . '</span>';
                } else {
                    return $q->address;
                }
            })
            ->editColumn('city', function ($q) {
                if (empty($q->city)) {
                    return '<span class="badge badge-light-danger fs-8 fw-bolder">' . trans('backend.global.not_found') . '</span>';
                } else {
                    return $q->city;
                }
            })
            ->editColumn('phone', function ($q) {
                if (empty($q->phone)) {
                    return '<span class="badge badge-light-danger fs-8 fw-bolder">' . trans('backend.global.not_found') . '</span>';
                } else {
                    return $q->phone;
                }
            })
            ->editColumn('postal_code', function ($q) {
                if (empty($q->postal_code)) {
                    return '<span class="badge badge-light-danger fs-8 fw-bolder">' . trans('backend.global.not_found') . '</span>';
                } else {
                    return $q->postal_code;
                }
            })
            ->addColumn('actions', function ($q) use ($uuid) {
                $html = '';
                if (permission_can('edit address seller user', 'seller')) {
                    $html = '<button    data-id="' . $q->id . '" data-user="' . $uuid . '" type="button" class="btn btn-light-danger delete-address me-1 btn-sm btn-icon text-danger"><i class="la la-trash"></i></button>';
                    $html .= '<button data-id="' . $q->id . '" data-user="' . $uuid . '" data-is_default="' . $q->is_default . '" data-address=' . "'" . json_encode($q) . "'" . ' type="button" class="btn btn-light-primary me-1 btn-sm btn-icon text-primary edit_address"><i class="la la-edit"></i></button>';

                }
                return $html;
            })
            ->rawColumns(['actions'
                , 'country_id'
                , 'state'
                , 'street'
                , 'address'
                , 'city'
                , 'phone'
                , 'postal_code'
            ])
            ->toJson();

    }

    //create
    public function create_new_address(CreateRequestAddress $request)
    {
        $user = User::query()->where('uuid', $request->uuid)->where('seller_id', auth('seller')->id())->first();
        if (empty($user)) {
            return response()->error(trans('seller.orders.not_found_user'));
        }
        $address = new Address();
        $address->country_id = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->user_id = $user->id;
        $address->address = $request->full_address;
        $address->street = $request->street;
        $address->postal_code = $request->postal_code;
        $address->phone = !empty($request->address_full_phone) ? $request->address_full_phone : $user->phone;
        $address->country_id = $request->country;
        if ($request->has('default_address')) {
            Address::query()->where('user_id', $user->id)->update(['is_default' => 0]);
            $address->is_default = 1;
            $user->country_id = $request->country;
            $user->state = $request->state;
            $user->city = $request->city;
            $user->address = $request->full_address;
            $user->street = $request->street;
            $user->postal_code = $request->postal_code;
            $user->save();
        }
        $address->save();

        return response()->data(['message' => trans('backend.global.success_message.created_successfully')]);
    }

    //destroy
    public function delete_address(DeleteRequest $request)
    {
        $uuid = $request->uuid;
        $id = $request->id;
        $user = User::query()->where(function ($q) {
            $q->where('users.seller_id', auth('seller')->id());
            //is manger
            if (auth('seller')->user()->is_manager) {
                //seller ids
                $sellers = Seller::query()->where('seller_manger', auth('seller')->id())->pluck('id');
                $q->orWhereIn('users.seller_id', $sellers);
            }
        })->where('uuid', $uuid)->first();
        if (!empty($user)) {
            Address::query()->where('id', $id)->where('user_id', $user->id)->delete();
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        } else {
            return response()->error(trans('seller.orders.not_found_user'));
        }
    }

    //edit
    public function edit_address(EditRequestAddress $request)
    {
        $user = User::query()->where('uuid', $request->uuid)->where('seller_id', auth('seller')->id())->first();
        if (empty($user)) {
            return response()->error(trans('seller.orders.not_found_user'));
        }
        $address = Address::find($request->id);
        $address->country_id = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->user_id = $user->id;
        $address->address = $request->full_address;
        $address->street = $request->street;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->address_full_phone;
        $address->country_id = $request->country;
        if ($request->default_address == 1) {
            Address::query()->where('user_id', $user->id)->update(['is_default' => 0]);
            $address->is_default = 1;
            $user->country_id = $request->country;
            $user->state = $request->state;
            $user->city = $request->city;
            $user->address = $request->full_address;
            $user->street = $request->street;
            $user->postal_code = $request->postal_code;
            $user->save();
        }

        $address->save();
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }
    #endregion

    #region payment recorde
    public function payment_recode_datatable(Request $request)
    {
        //request = user && seller
        $user = $request->user;
        $seller = auth('seller')->id();
        $query = UserWallet::query()
            ->select('user_wallet.*', 'orders.uuid')
            ->join('users', 'users.id', 'user_wallet.user_id')
            ->leftJoin('orders', 'orders.id', 'user_wallet.order_id')
            ->where('users.uuid', $user)
            ->where('users.seller_id', $seller);
        return datatables()->make($query)
            ->editColumn('uuid', function ($q) {
                return '<a class="badge badge-light-primary  badge py-3 px-4 " target="_blank" href="' . route('seller.orders.show', $q->uuid) . '">' . $q->uuid . '</a>';
            })
            ->editColumn('type', function ($q) {

                if ($q->type == UserWallet::$withdraw) {
                    return '<span class="badge badge-light-primary  badge py-3 px-4 ">' . trans('seller.user.' . $q->type) . '</span>';
                } elseif ($q->type == UserWallet::$order) {
                    return '<span class="badge badge-light-info     badge py-3 px-4 ">' . trans('seller.user.' . $q->type) . '</span>';
                } elseif ($q->type == UserWallet::$refund) {
                    return '<span class="badge badge-light-danger   badge py-3 px-4 ">' . trans('seller.user.' . $q->type) . '</span>';
                } elseif ($q->type == UserWallet::$amount) {
                    return '<span class="badge badge-light-success  badge py-3 px-4 ">' . trans('seller.user.' . $q->type) . '</span>';
                }
            })
            ->editColumn('status', function ($q) {
                if ($q->status == UserWallet::$approve) {
                    return '<span class="badge badge-light-success  badge  py-3 px-4 ">' . trans('seller.user.' . $q->status) . '</span>';
                } elseif ($q->status == UserWallet::$pending) {
                    return '<span class="badge badge-light-info     badge py-3 px-4 ">' . trans('seller.user.' . $q->status) . '</span>';
                } elseif ($q->status == UserWallet::$cancelled) {
                    return '<span class="badge badge-light-danger   badge  py-3 px-4 ">' . trans('seller.user.' . $q->status) . '</span>';
                }
            })
            ->editColumn('amount', function ($q) {
                if ($q->status == UserWallet::$approve) {
                    return '<span class="badge badge-light-success  badge py-3 px-4  ">' . currency($q->amount) . '</span>';
                } elseif ($q->status == UserWallet::$pending) {
                    return '<span class="badge badge-light-info     badge py-3 px-4  ">' . currency($q->amount) . '</span>';
                } elseif ($q->status == UserWallet::$cancelled) {
                    return '<span class="badge badge-light-danger   badge py-3 px-4  ">' . currency($q->amount) . '</span>';
                }
            })
            ->editColumn('note', function ($q) {
                return '<span style="font-size: small">' . $q->note . '</span>';
            })
            ->addColumn('index', function ($q) {
                return '';
            })
            ->addColumn('action', function ($q) {
                $view = '<button data-id="' . $q->id . '" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary  show_transfer_order btn-sm"  ><i class="la la-wallet"></i> ' . trans('backend.global.show') . '</button>';
                return $view;
            })
            ->editColumn('created_at', function ($q) {
                return '<span style="font-size: small">' . $q->created_at . '</span>';
            })
            ->rawColumns(['type', 'amount', 'action', 'status', 'uuid', 'note', 'created_at'])
            ->toJson();
    }

    function get_balance(Request $request)
    {
        $user_uuid = $request->uuid;
        $userWallet = UserWallet::query()
            ->join('users', 'users.id', 'user_wallet.user_id')
            ->where('users.uuid', $user_uuid)
            ->where('user_wallet.status', 'approve')
            ->sum('amount');
        $orders = Order::query()
            ->leftJoin('user_wallet', 'user_wallet.order_id', 'orders.id')
            ->select('orders.id', 'orders.uuid', 'orders.created_at', DB::raw('sum(user_wallet.amount) as balance'))
            ->where('user_wallet.status', UserWallet::$approve)
            ->groupBy('orders.id')
            ->get();
        foreach ($orders as $key => $order) {
            $order->balance = currency($order->balance);
        }


        return response()->data(['balance' => $userWallet, 'orders' => $orders]);
    }

    function set_balance(CreateRequestPayment $request)
    {

        $order = Order::query()->where('uuid', $request->order)->first();
        $wallet = $this->payment_wallet($request->user_id, $request->amount, UserWallet::$amount, UserWallet::$approve, Seller::class, auth('seller')->id(), $order?->id, null, $request->note, '_files');
        return response()->data(['wallet' => $wallet]);
    }


    #endregion

    #region send reminder
    public function send_reminder(Request $request)
    {
        $user = User::findOrFail($request->id);
        return $this->sendReminder($user);

    }

    #endregion


    #region account statement
    public function send_account_statement(Request $request)
    {
        $user = User::findOrFail($request->id);
        return $this->SendStatementMail($user);
    }


    #endregion
    public function wallet_payment_info(WalletPaymentInfoRequest $request)
    {
        $wallet = UserWallet::query()->where('id', $request->id)->first();
        if (empty($wallet)) {
            return response()->error(trans('seller.orders.not_found_user'));
        }
        $user_id = $wallet->user_id;
        $total_balance = UserWallet::query()->where('status', UserWallet::$approve)->where('user_id', $user_id)->sum('amount');
        $order = null;
        $order_payment = null;
        if (!empty($wallet->order_id)) {
            $order = Order::query()->where('id', $wallet->order_id)->first();
            $order_payment = OrderPayment::query()->where($wallet->order_payment)->first();
        }
        try {
            $wallet_create = $wallet->create_by()->first();
        } catch (\Exception $exception) {
            $wallet_create = null;
        }
        return view('backend.user.data.payment.show', compact('wallet', 'wallet_create', 'order', 'order_payment', 'total_balance'));
    }

    public function wallet_payment_change_status(WalletPaymentChangeStatusRequest $request)
    {
        $wallet = UserWallet::query()->where('id', $request->id)->first();
        if (!empty($wallet)) {
            if ($request->type == 'approve') {
                $wallet->status = UserWallet::$approve;
                if (!empty($wallet->order_id) && !empty($wallet->order_payment_id)) {
                    OrderPayment::query()->where('id', $wallet->order_payment_id)->where('order_id', $wallet->order_id)->update(['status' => OrderPayment::$captured]);
                }
            } else {
                $wallet->status = UserWallet::$cancelled;

                OrderPayment::query()->where('id', $wallet->order_payment_id)->where('order_id', $wallet->order_id)->update(['status' => OrderPayment::$voided]);
            }
            $wallet->save();
        }
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }

    public function wallet_payment_get(WalletPaymentGetRequest $request)
    {
        $user_id = $request->user;
        //get user
        $user = User::query()->where('id', $user_id)->first();
        //get wallet balance
        $wallet_balance = UserWallet::query()->where('user_id', $user_id)->where('status', UserWallet::$approve)->sum('amount');
        //all orders for this users
        $orders_data = Order::query()
            ->where('user_id', $user_id)
            ->whereNot('status', [Order::$canceled, Order::$refunded, Order::$failed, Order::$waiting])
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('payment_method', Order::$transfer)->whereNot('status', Order::$refunded);
                });
                $q->orWhere(function ($q2) {
                    $q2->where('status', Order::$refunded);
                });
            })
            ->where('type', Order::$order)
            ->orderByDesc('id')
            ->select(['id', 'uuid', 'total', 'status', 'created_at'])->get();
        $orders = [];
        foreach ($orders_data as $item) {
            $item->balance = UserWallet::query()->where('user_id', $user_id)->where('order_id', $item->id)->where('status', UserWallet::$approve)->sum('amount');
            if (($item->balance > 0 && $item->status == Order::$refunded) || $item->status != Order::$refunded) {
                $item->balance = round($item->balance , 2);
                $item->balanceWithCurrency = currency($item->balance);
//                $item->balance = currency($item->balance);
                $item->total = currency($item->total);
                $item->status_trans = trans('backend.order.' . $item->status);
                $orders[]= $item;
            }
        }
        return response()->data(['user' => $user, 'orders' => $orders, 'wallet_balance' => $wallet_balance]);
//        return view('backend.user.data.payment.create', compact('user', 'orders', 'wallet_balance'));
    }

    public function wallet_payment_set(WalletPaymentSetRequest $request)
    {
        $user_id = $request->user_id;
        $amount = $request->amount;
        $type = $request->type;
        $order_id = $request->order_id;
        $note = $request->note;
        if ($type == UserWallet::$order) {
            $order_balance = UserWallet::query()->where('user_wallet.order_id', $order_id)->where('user_wallet.type', UserWallet::$order)->where('user_wallet.status', UserWallet::$approve)->sum('amount');
            if (abs($amount) > round(abs($order_balance),2))
                return response()->error("The amount is greater than the balance");
        } elseif ($type == UserWallet::$withdraw) {
            $order_balance = UserWallet::query()->where('user_wallet.order_id', $order_id)->whereIn('user_wallet.type', [UserWallet::$refund ,UserWallet::$withdraw])->where('user_wallet.status', UserWallet::$approve)->sum('amount');
            $order_balance2 = UserWallet::query()->where('user_wallet.order_id', $order_id)->whereNotIn('user_wallet.type', [UserWallet::$cancelled ])->where('user_wallet.status', UserWallet::$approve)->sum('amount');

            if (abs($amount) > round(abs($order_balance),2)&&  abs($amount) > round(abs($order_balance2),2))
                return response()->error("The amount is greater than the balance "  );
        }

        $wallet = $this->payment_wallet($user_id, $amount, $type, UserWallet::$approve, Seller::class, auth('seller')->id(), $order_id, null, $note, 'files');

        return response()->data(['wallet' => $wallet]);
    }
}
