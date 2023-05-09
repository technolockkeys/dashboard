<?php

namespace App\Http\Controllers\Backend;

use     App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Http\Requests\Backend\User\CreateRequest;
use App\Http\Requests\Backend\User\UpdateRequest;
use App\Http\Requests\Backend\User\WalletPaymentChangeStatusRequest;
use App\Http\Requests\Backend\User\WalletPaymentGetRequest;
use App\Http\Requests\Backend\User\WalletPaymentInfoRequest;
use App\Http\Requests\Backend\User\WalletPaymentSetRequest;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\CouponUsages;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Wishlist;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;

use App\Traits\PaymentTrait;
use App\Traits\ProductTrait;

use App\Traits\SetMailConfigurations;
use App\Traits\UserTrait;
use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use League\CommonMark\Node\Query\OrExpr;

class UserController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use PaymentTrait;
    use UserTrait;
    use SetMailConfigurations;

    #region index
    public function index()
    {
        if (!permission_can('show users', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $filters[] = 'seller';
        $filters[] = 'country';
        $filters[] = 'balance';

        $model = User::leftjoin('sellers', 'sellers.id', 'users.seller_id')
            ->leftjoin('user_wallet', 'user_wallet.user_id', 'users.id')
            ->select(
                'users.*', 'sellers.name as seller',
                DB::raw('sum(user_wallet.amount) as user_balance'),
            )->groupBy('users.id')->get();


        $datatable_route = route('backend.users.datatable');
        $delete_all_route = permission_can('delete user', 'admin') ? route('backend.users.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['uuid'] = 'uuid';
        $datatable_columns['phone'] = 'phone';
        $datatable_columns['email'] = 'email';
        $datatable_columns['user_balance'] = 'user_balance';
        $datatable_columns['seller'] = 'sellers.name';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion

        $sellers_ids = User::pluck('seller_id');
        $sellers = Seller::whereIn('id', $sellers_ids)->whereNull('deleted_at')->get();

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters, $data_send = [], $method = "post", $id = "datatable", $status = true, $search = true, $export = true, $name = '', ['user_balance']);
        $switch_script = null;
        $switch_route = route('backend.users.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = $this->create_button(route('backend.users.create'), trans('backend.user.create_new_user'));
        $countries = Country::query()->where('status', 1)->get();
        $balance = [
            'has_dept' => trans('backend.user.has_dept'),
            'balanced' => trans('backend.user.balanced'),
            'has_deposit' => trans('backend.user.has_deposit'),
        ];

        return view('backend.user.index', compact('datatable_script', 'switch_script', 'create_button', 'sellers', 'countries', 'balance'));
    }

    public function datatable(Request $request)
    {

        if (!permission_can('show users', 'admin')) {
            return abort(403);
        }
        $model = User::
        leftjoin('sellers', 'sellers.id', 'users.seller_id')
//            ->leftjoin('user_wallet', 'user_wallet.user_id', 'users.id')
            ->leftJoin('user_wallet', function ($join) {
                $join->on('user_wallet.user_id', '=', 'users.id');
                $join->where('user_wallet.status', '=', UserWallet::$approve);
            })
            ->select(
                'users.*', 'sellers.name as seller',
                DB::raw('sum(user_wallet.amount) as user_balance'),
//                DB::raw('count(orders.id) as orders_count'),
//                DB::raw('sum(orders.total) as purchase_value'),
//                DB::raw('avg(orders.total) as avg_purchase_value'),
            )->with('addresses')
            ->groupBy('users.id');


        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('users.status', $request->status);
        }

        if ($request->has('seller') && $request->seller != null) {
            $model = $model->where('users.seller_id', $request->seller);
        }

        if ($request->country != null) {
            $model = $model->whereHas('addresses', function ($q) use ($request) {
                $q->where('country_id', $request->country);
            });
        }

        if ($request->balance != null) {
            $operator = $request->balance == 'has_dept' ? '<' : ($request->balance == 'balanced' ? '=' : '>');
            $model = $model->having('user_balance', $operator, 0);
            if ($operator == '=') {
                $model->orHavingNull('user_balance');
            }

        }

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete user', 'admin')) {
                    $actions .= $this->delete_button(route('backend.users.destroy', ['user' => $q->id]), $q->name);
                }
                if (permission_can('show users', 'admin')) {
                    $actions .= $this->btn(route('backend.users.show', ['user' => $q->id]), '', 'las la-eye', 'btn-warning btn-show btn-icon');
                }

                return $actions;
            })
            ->editColumn('name', function ($q) {
                return '<div class="d-flex align-items-center"><div class="symbol symbol-45px me-5">
                        <img src="' . asset($q->avatar) . "?t=" . time() . '" onerror="this.src=' . "'" . asset('backend/media/avatars/blank.png') . "'" . '" alt=""></div><div class="d-flex justify-content-start flex-column">
                        <b   class="text-dark fw-bolder text-hover-primary fs-6">' . $q->name . '</b>

                        </div></div>';
            })
            ->editColumn('avatar', function ($q) {
                $avatar = $q->avatar ?: media_file();
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . $avatar . "'> </div>";
                return $html;
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
            ->editColumn('user_balance', function ($q) {

                $class = $q->user_balance < 0 ? 'danger' : ($q->user_balance == 0 ? 'primary' : 'success');
                return '<span class="badge badge-light-' . $class . '"> ' . currency($q->user_balance) . '</span>';
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status user', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'seller', 'name', 'avatar', 'user_balance'])
            ->toJson();
    }
    #endregion

    #region create
    function create()
    {
        $countries = Country::where('status', 1)->get();
        $sellers = Seller::where('status', 1)->get();
        return view('backend.user.create', compact('countries', 'sellers'));
    }

    public function store(CreateRequest $request)
    {
        $user = new User();

        if ($request->avatar_remove == true) {
            $user->avatar = null;
        }


        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->postal_code = $request->postal_code;
        $user->company_name = $request->company_name;
        $user->website_url = $request->website_url;
        $user->type_of_business = $request->type_of_business;
        $user->seller()->associate(Seller::find($request->seller));
        $user->state = $request->state;
        $user->street = $request->street;
        $user->city = $request->city;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->status = $request->has('status') ? 1 : 0;

        $user->save();
        if (!empty($request->file('avatar'))) {

            $avatar = $request->file('avatar');
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
            $user->save();
        }
        //address
        if (!empty($request->country) || !empty($request->city) || !empty($request->adddress)) {

            $address = new Address();
            $address->user_id = $user->id;
            $address->country_id = $request->country;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->street = $request->street;
            $address->state = $request->state;
            $address->phone = $request->phone;
            $address->postal_code = $request->postal_code;
            $address->is_default = 1;
            $address->save();
        }
        return redirect()->route('backend.users.create')->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region update
    public function update(UpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->avatar_remove == true) {
            $user->avatar = null;
        }

        if (!empty($request->file('avatar'))) {
            $avatar = $request->file('avatar');

            $image_data = $user->StoreAvatarImage('avatar', $user->id, 'user');
            $encoded_data = json_decode($image_data->content());
            $avatar_link = '/' . $encoded_data->data->path . $encoded_data->data->title;
            $user->avatar = $avatar_link;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->postal_code = $request->postal_code;
        $user->company_name = $request->company_name;
        $user->seller()->associate(Seller::find($request->seller));
        $user->website_url = $request->website_url;
        $user->type_of_business = $request->type_of_business;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->has('status') ? 1 : 0;
        $user->save();
        return redirect()->route('backend.users.show', ['user' => $user->id])->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    #endregion

    #region show
    public function show($id)
    {
        $user = User::findOrFail($id);
        $user_id = $id;
        $addresses = Address::where('user_id', $user_id)->get();
        $countries = Country::where('status', 1)->get();
        return view('backend.user.show', compact('user', 'addresses', 'countries', 'user_id'));
    }

    public function addresses($user_id)
    {
        if (permission_can('show user addresses', 'admin')) {
            $addresses = Address::where('user_id', $user_id)->get();
            $countries = Country::where('status', 1)->get();

            $view = view('backend.user.data.addresses', compact('addresses', 'countries', 'user_id'))->render();
            return response()->data(['view' => $view]);

        }
        return response()->data(['view' => 'not have permission']);

    }

    public function overview($user_id)
    {
        $user = User::findOrFail($user_id);
        $countries = Country::where('status', 1)->get();
        $sellers = Seller::where('status', 1)->get();

        $view = view('backend.user.data.overview', compact('user', 'countries', 'sellers'))->render();
        return response()->data(['view' => $view]);
    }

    #endregion

    #region carts
    public function carts($user_id)
    {
        if (!permission_can('show carts', 'admin')) {
            abort(403);
        }

        $datatable_route = route('backend.users.carts.datatable', $user_id);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['image'] = 'image';
        $datatable_columns['title'] = 'title';
        $datatable_columns['quantity'] = 'quantity';
        $datatable_columns['price'] = 'price';
        $datatable_columns['discount'] = 'discount';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $view = view('backend.user.data.carts', compact('datatable_script'))->render();
        return response()->data(['view' => $view]);
    }

    public function cart_datatable($user_id)
    {
        if (!permission_can('show carts', 'admin')) {
            return abort(403);
        }
        $model = Cart::query()->where('user_id', $user_id)
            ->select('carts.*', 'products.image')
            ->join('products', 'carts.product_id', 'products.id')
            ->whereNull('products.deleted_at');

        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete cart', 'admin')) {
                    $actions .= $this->delete_button(route('backend.tickets.destroy', ['ticket' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->editColumn('image', function ($q) {
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . media_file($q->image) . "'> </div>";
                return $html;
            })
            ->editColumn('title', function ($q) {
//                $product = json_decode($q->title, true);

                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->product->title . '</span>
                                                    </a>';
            })
            ->editColumn('discount', function ($q) {
                if ($q->discount != 0 && $q->coupon_code != null) {
                    $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->coupon_code . ': ' . $q->discount . '</span>';
                } else {
                    $html = '<span class="badge badge-light-warning badge-lg fw-bold  ">' . trans('backend.global.not_found') . '</span>';
                }
                return $html;

            })
            ->editColumn('price', function ($q) {
                return $q->price;
            })
            ->rawColumns(['actions', 'image', 'title', 'discount'])
            ->toJson();
    }


    #endregion

    #region tickets
    public function tickets($user_id)
    {
        if (!permission_can('show tickets', 'admin')) {
            abort(403);
        }
        $datatable_route = route('backend.users.tickets.data_table', $user_id);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['system_id'] = 'system_id';
        $datatable_columns['type'] = 'type';
        $datatable_columns['subject'] = 'subject';
        $datatable_columns['model'] = 'model';

        $datatable_columns['status'] = 'status';
        $datatable_columns['last_reply'] = 'last_reply';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $view = view('backend.user.data.tickets', compact('datatable_script'))->render();
        return response()->data(['view' => $view]);
    }

    public function ticket_datatable($user_id)
    {

        if (!permission_can('show tickets', 'admin')) {
            return abort(403);
        }
        $model = Ticket::query()->where('user_id', $user_id);
        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete ticket', 'admin')) {
                    $actions .= $this->delete_button(route('backend.tickets.destroy', ['ticket' => $q->id]), $q->name);
                }
                if (permission_can('show tickets', 'admin')) {
                    $actions .= $this->btn(route('backend.tickets.show', ['ticket' => $q->id, 'from' => 'user']), '', 'las la-eye', 'btn-warning btn-show btn-icon');
                }
                return $actions;
            })
            ->editColumn('type', function ($q) {
                return '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->type . '</span>';
            })
            ->editColumn('status', function ($q) {
                if ($q->status == 'pending') {
                    $badge_type = 'badge-light-warning';
                } elseif ($q->status == 'open') {
                    $badge_type = 'badge-light-primary';
                } elseif ($q->status == 'solved') {
                    $badge_type = 'badge-light-success';
                }
                return '<span class="badge badge-lg ' . $badge_type . ' fw-bold "  >' . $q->status . '</span>';
            })
            ->addColumn('model', function ($q) {
                $html = '';
                $route = $q->model_type == Order::class ? route('backend.orders.edit', ['order' => $q->model?->id]) :
                    ($q->model_type == Product::class ? route('backend.products.edit', ['product' => $q->model?->id]) : null);
                $html = '<a href="' . $route . '" class="btn btn-sm  btn-hover-rise  me-1"> <span class="badge badge-light-danger"> ' . $q->model?->short_title ?? $q->model?->uuid ?? trans('backend.global.not_found') . '</span></a>';
                return $html;
            })
            ->rawColumns(['actions', 'status', 'type', 'model'])
            ->toJson();
    }

    #endregion

    #region wishlists
    public function wishlist($user_id)
    {
        if (!permission_can('show wishlists', 'admin')) {
            return abort(403);
        }

        $datatable_route = route('backend.users.wishlists.data_table', $user_id);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['title'] = 'products.title';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $view = view('backend.user.data.wishlists', compact('datatable_script'))->render();
        return response()->data(['view' => $view]);
    }

    public function wishlist_datatable($user_id)
    {

        if (!permission_can('show wishlists', 'admin')) {
            return abort(403);
        }

        $model = Wishlist::query()
            ->where('user_id', $user_id)
            ->select('wishlists.*', 'products.title as title')
            ->join('products', 'wishlists.product_id', 'products.id')
            ->groupBy('product_id');

//        dd(Wishlist::query()->count());
        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete wishlist', 'admin')) {
                    $actions .= $this->delete_button(route('backend.wishlists.destroy', ['wishlist' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->editColumn('title', function ($q) {
                $product = json_decode($q->title, true);
                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $product[app()->getLocale()] . '</span>
                                                    </a>';
            })
            ->rawColumns(['actions', 'title'])
            ->toJson();
    }

    #endregion

    #region Orders
    public function orders($user_id)
    {
        if (!permission_can('show orders', 'admin')) {
            return abort(403);
        }

        $datatable_route = route('backend.users.orders.datatable', $user_id);

        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['uuid'] = 'uuid';
        $datatable_columns['seller'] = 'sellers.name';
        $datatable_columns['payment_method'] = 'payment_method';
        $datatable_columns['payment_status'] = 'payment_status';
        $datatable_columns['total'] = 'total';
        $datatable_columns['shipping'] = 'shipping';
        $datatable_columns['status'] = 'status';
        $datatable_columns['coupon'] = 'coupon_value';
        $datatable_columns['type'] = 'type';
        $datatable_columns['tracking_number'] = 'tracking_number';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $view = view('backend.user.data.orders', compact('datatable_script'))->render();
        return response()->data(['view' => $view]);

    }

    public function orders_datatable($user_id)
    {
        if (!permission_can('show orders', 'admin')) {
            return abort(403);
        }

        $model = Order::query()
            ->where('user_id', $user_id)
            ->leftjoin('sellers', 'sellers.id', 'orders.seller_id')
            ->select('orders.*', 'sellers.name as seller');

        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('show orders', 'admin')) {
                    $actions .= $this->btn(route('backend.orders.show', ['order' => $q->id, 'from' => 'user']), '', 'las la-eye', 'btn-warning btn-show btn-icon');
                }
                return $actions;
            })
            ->editColumn('tracking_number', function ($q) {
                $text = $q->tracking_number ?? __('backend.global.not_found');
                return '<span class="badge badge-light-info badge-lg fw-bold  ">' . $text . '</span>';
            })
            ->editColumn('total', function ($q) {
                return '<span class="badge badge-light-info badge-lg fw-bold  ">' . currency($q->total) . '</span>';
            })
            ->editColumn('seller', function ($q) {
                return $q->seller ?
                    '<a href="' . route('backend.sellers.edit', ['seller' => $q->seller_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-success badge-lg">
                                                       ' . $q->seller . '</span>
                                                    </a>'
                    : '<span class="badge small badge-light-danger"> ' . trans('backend.global.not_found') . '</span>';
            })
            ->editColumn('uuid', function ($q) {
                return '<a href="' . route('backend.orders.show', ['order' => $q->id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->uuid . '</span>
                                                    </a>';
            })
            ->editColumn('shipping', function ($q) {
                return '<span class="badge badge-light-primary badge-lg fw-bold  ">' . currency($q->shipping) . '</span>';
            })
            ->editColumn('payment_method', function ($q) {
                $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->payment_method . '</span>';
                return $html;
            })
            ->editColumn('coupon', function ($q) {
                $html = '<span class="badge badge-secondary badge-lg fw-bold  ">' . currency($q->coupon_value) . '</span>';
                return $html;
            })
            ->editColumn('type', function ($q) {
                $html = '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->type . '</span>';
                return $html;
            })
            ->editColumn('payment_status', function ($q) {
                if ($q->payment_status == 'paid') {
                    $class = 'badge-light-success';
                } elseif ($q->payment_status == 'unpaid') {
                    $class = 'badge-light-warning';
                } else {
                    $class = 'badge-light-error';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  ">' . $q->payment_status . '</span>';
                return $html;
            })
            ->editColumn('status', function ($q) {
                if ($q->status == 'completed') {
                    $class = 'badge-primary';
                } elseif ($q->status == 'on_hold' || $q->status == 'waiting') {
                    $class = 'badge-warning';
                } elseif ($q->status == 'refunded' || $q->status == 'canceled' || $q->status == 'pending_payment') {
                    $class = 'badge-secondary';
                } elseif ($q->status == 'processing') {
                    $class = 'badge-success';
                } elseif ($q->status == 'failed') {
                    $class = 'badge-error';
                }
                $html = '<span class="badge ' . $class . ' badge-lg fw-bold  ">' . $q->status . '</span>';
                return $html;

            })
            ->rawColumns(['actions', 'status', 'seller',
                'payment_method', 'payment_status', 'type', 'uuid',
                'total', 'shipping', 'coupon', 'tracking_number'])
            ->toJson();
    }

    #endregion

    #region reviews
    public function reviews($user_id)
    {
        if (!permission_can('show reviews', 'admin')) {
            return abort(403);
        }

        $datatable_route = route('backend.users.reviews.datatable', $user_id);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['product'] = 'product';
        $datatable_columns['rating'] = 'rating';
        $datatable_columns['status'] = 'status';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $switch_script = null;
        $switch_route = route('backend.users.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);

        $view = view('backend.user.data.reviews', compact('datatable_script', 'switch_script'))->render();
        return response()->data(['view' => $view]);
    }

    public function reviews_datatable($user_id)
    {
        if (!permission_can('show reviews', 'admin')) {
            return abort(403);
        }

        $model = Review::query()
            ->where('user_id', $user_id)
            ->select('reviews.*', 'products.title')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->whereNull('products.deleted_at')
            ->groupBy('product_id');

        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete review', 'admin')) {
                    $actions .= $this->delete_button(route('backend.reviews.destroy', ['review' => $q->id]), $q->name);
                }
                if (permission_can('show reviews', 'admin')) {
                    $actions .= $this->btn(route('backend.reviews.show', ['review' => $q->id, 'from' => 'user']), '', 'las la-eye', 'btn-warning btn-show btn-icon');
                }
                return $actions;
            })
            ->addColumn('product', function ($q) {
                $product = json_decode($q->title, true);
                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $product[app()->getLocale()] . '</span>
                                                    </a>';
            })
            ->addColumn('status', function ($q) {

                return $q->status;
            })
            ->rawColumns(['actions', 'status', 'type', 'product'])
            ->toJson();
    }

    #endregion

    #region coupon
    public function coupon($user_id)
    {
        if (!permission_can('show coupons', 'admin')) {
            return abort(403);
        }

        $user = User::findOrFail($user_id);

        $coupons = CouponUsages::where('user_id', $user_id)->get();
        $datatable_route = route('backend.users.coupon.datatable', ['user_id' => $user->id]);

        $datatable_columns = [];
        $datatable_columns['id'] = 'coupon_usages.id';
        $datatable_columns['code'] = 'code';
        $datatable_columns['discount'] = 'discount';
        $datatable_columns['discount_type'] = 'discount_type';
        $datatable_columns['type'] = 'type';
        $datatable_columns['number'] = 'number';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);

        $view = view('backend.user.data.coupons', compact('user', 'datatable_script'))->render();

        return response()->data(['view' => $view]);
    }

    public function coupon_datatable($user_id)
    {
        if (!permission_can('show coupons', 'admin')) {
            return abort(403);
        }

        $user = User::findOrFail($user_id);

        $model = CouponUsages::query()
            ->select('coupon_usages.*', 'coupons.code', 'coupons.discount', 'coupons.discount_type', 'coupons.type', DB::raw('COUNT(coupon_usages.coupon_id) as number'))
            ->join('coupons', 'coupons.id', 'coupon_usages.coupon_id')
            ->where('coupon_usages.user_id', $user_id)
            ->groupBy('coupon_usages.coupon_id');

        return datatables()->make($model)
            ->addColumn('actions', function ($q) use ($user_id) {
                $data['route'] = route('backend.users.coupon.order', ['user_id' => $user_id]);
                $data['coupon_id'] = $q->coupon_id;
                return $this->btn(null, '', 'las la-eye', 'btn-warning btn-show btn-icon show-coupon-usage', $data);
            })
            ->rawColumns(['actions'])
            ->toJson();

    }

    public function coupons_orders(Request $request, $user_id)
    {

        $orders = Order::where('user_id', $user_id)->where('coupon_id', $request->coupon_id)->get();

        $view = view('backend.user.data.show_coupon', compact('orders'))->render();

        return response()->data(['view' => $view]);

    }
    #endregion

    #region wallet
    public function wallet($user_id)
    {
        if (!permission_can('show wallets', 'admin')) {
            return abort(403);
        }

        $user = User::findOrFail($user_id);


        $refund = $user->wallet()->whereIn('type', ['refund', 'withdraw'])->get();
        $approved_order = $user->wallet()->where('type', 'order')->where('amount', '>', 0)->where('status', 'approve')->get();
        $failed_order = $user->wallet()->where('type', 'order')->where('status', Order::$failed)->get();


        $datatable_route = route('backend.users.wallet.datatable', ['user_id' => $user->id]);

        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['amount'] = 'amount';
//        $datatable_columns['before_balance'] = 'before_balance';
//        $datatable_columns['balance'] = 'balance';
        $datatable_columns['type'] = 'type';
        $datatable_columns['uuid'] = 'orders.uuid';
        $datatable_columns['status'] = 'status';
        $datatable_columns['note'] = 'user_wallet.note';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['action'] = 'action';

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);

        $statistics = [
            [
                'svg' => asset('backend/media/icons/duotune/arrows/arr070.svg'),
                'sum' => currency(UserWallet::query()->where('user_id', $user_id)->where('status', 'approve')->sum('amount')),
                'name' => __('backend.order.balance'),
                'number' => '-',
            ],
            [
                'svg' => asset('backend/media/icons/duotune/arrows/arr059.svg'),
                'sum' => currency($refund->sum('amount')),
                'name' => __('backend.seller.refund'),
                'number' => $user->wallet()->whereIn('type', ['refund'])->count(),
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

        $view = view('backend.user.data.wallet', compact('user', 'statistics', 'datatable_script'))->render();

        return response()->data(['view' => $view]);
    }

    public function wallet_datatable($user_id)
    {
        if (!permission_can('show wallets', 'admin')) {
            return abort(403);
        }


        $model = UserWallet::query()
            ->leftJoin('orders', 'orders.id', 'user_wallet.order_id')
            ->select('user_wallet.*', 'orders.uuid as uuid')
            ->where('user_wallet.user_id', $user_id);

//        $model = $user->wallet();
        return datatables()->make($model)
            ->editColumn('uuid', function ($q) {
//                return $q->uuid ;
                if (!empty($q->uuid)) {
                    return "<a style='cursor: pointer;' class='badge  badge-primary 'href='" . route('backend.orders.show', $q->order_id) . "'>#" . $q->uuid . "</a>";
                }
                return "-";
            })
            ->editColumn('amount', function ($q) {
                return currency($q->amount);
            })
            ->editColumn('status', function ($q) {
                if ($q->status == UserWallet::$approve) {
                    return '<span class="badge badge-success">' . trans('backend.wallet.approve') . ' </span>';
                }
                if ($q->status == UserWallet::$cancelled) {
                    return '<span class="badge badge-warning">' . trans('backend.order.canceled') . ' </span>';
                }
                if ($q->status == UserWallet::$pending) {
                    return '<span class="badge badge-primary">' . trans('backend.order.pending') . ' </span>';
                }
                if ($q->status == UserWallet::$refund) {
                    return '<span class="badge badge-danger">' . trans('backend.order.refund') . ' </span>';
                }
                return $q->status;
            })
            ->editColumn('type', function ($q) {
                if ($q->type == UserWallet::$refund) {
                    return '<span class="badge badge-danger">' . trans('backend.order.refund') . ' </span>';
                }
                if ($q->type == UserWallet::$order) {
                    return '<span class="badge badge-primary">' . trans('backend.wallet.order') . ' </span>';
                }
                if ($q->type == UserWallet::$withdraw) {
                    return '<span class="badge badge-warning">' . trans('backend.wallet.withdraw') . ' </span>';
                }
                if ($q->type == UserWallet::$amount) {
                    return '<span class="badge badge-success">' . trans('backend.wallet.amount') . ' </span>';
                }
                return $q->type;
            })
            ->editColumn('note', function ($q) {
                if (empty($q->note)) {
                    return '-';
                }
                return $q->note;
            })
            ->addColumn('action', function ($q) {
                $view = '<button data-id="' . $q->id . '" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary  show_transfer_order btn-sm"  ><i class="la la-wallet"></i> ' . trans('backend.global.show') . '</button>';
                return $view;
                return "<button type='button'  data-id='" . $q->id . "' class='btn btn-bg-light btn-active-color-primary show_payment btn-sm me-1'><i class='la la-info'></i> " . trans('backend.global.show') . "</button>";
            })
            ->rawColumns(['uuid', 'type', 'status', 'action'])
            ->toJson();
    }

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
            $order = Order::query()->whereNot('type', Order::$order)->where('id', $wallet->order_id)->first();
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
            ->whereIn('type', [Order::$order, Order::$pin_code])
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('payment_method', Order::$transfer)->whereNot('status', Order::$refunded);
                });
                $q->orWhere(function ($q2) {
                    $q2->whereIn('status', [Order::$refunded, Order::$transfer]);
                });
            })
            ->select(['id', 'uuid', 'total', 'status', 'created_at'])->get();

        $orders = [];
        foreach ($orders_data as $item) {
            $item->balance = UserWallet::query()->where('user_id', $user_id)->where('order_id', $item->id)->where('status', UserWallet::$approve)->sum('amount');
            if (($item->balance > 0 && ($item->status == Order::$refunded || $item->status == Order::$canceled)) || $item->status != Order::$refunded) {
                $item->balance = round($item->balance, 2);
                $item->balanceWithCurrency = currency($item->balance);
//                $item->balance = currency($item->balance);
                $item->total = currency($item->total);
                $item->status_trans = trans('backend.order.' . $item->status);
                $orders[] = $item;
            }

        }
        return response()->data(['user' => $user, 'orders' => $orders, 'wallet_balance' => round($wallet_balance, 2)]);
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
            if (abs($amount) > round(abs($order_balance), 2))
                return response()->error("The amount is greater than the balance");
        } elseif ($type == UserWallet::$withdraw) {
            $order_balance = UserWallet::query()->where('user_wallet.order_id', $order_id)->whereIn('user_wallet.type', [UserWallet::$refund, UserWallet::$withdraw])->where('user_wallet.status', UserWallet::$approve)->sum('amount');
            $order_balance2 = UserWallet::query()->where('user_wallet.order_id', $order_id)->whereNotIn('user_wallet.type', [UserWallet::$cancelled])->where('user_wallet.status', UserWallet::$approve)->sum('amount');
            if (abs($amount) > round(abs($order_balance), 2) && abs($amount) > round(abs($order_balance2), 2))
                return response()->error("The amount is greater than the balance ");
        }

        $wallet = $this->payment_wallet($user_id, $amount, $type, UserWallet::$approve, Admin::class, auth('admin')->id(), $order_id, null, $note, 'files');

        return response()->data(['wallet' => $wallet]);
    }

    #endregion

    #region cards
    public function cards($user_id)
    {
        if (!permission_can('show cards', 'admin')) {
            return abort(403);
        }
        $datatable_route = route('backend.users.cards.datatable', $user_id);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['last_four'] = 'last_four';
        $datatable_columns['brand'] = 'brand';
        $datatable_columns['is_default'] = 'is_default';
        $datatable_columns['created_at'] = 'created_at';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $view = view('backend.user.data.cards', compact('datatable_script'))->render();
        return response()->data(['view' => $view]);

    }

    public function cards_datatable($user_id)
    {
        $model = User::find($user_id)->cards();

        return datatables()->make($model)
            ->editColumn('last_four', function ($q) {
                return '**** **** ****<span class="badge badge-light-primary badge-lg fw-bold  "> ' . $q->last_four . '</span>';

            })
            ->editColumn('brand', function ($q) {
                return '<span class="badge badge-light-warning badge-lg fw-bold  ">' . $q->brand . '</span>';

            })
            ->editColumn('is_default', function ($q) {
                return '<span class="badge badge-light-info badge-sm  ">' . ($q->is_default ? trans('backend.card.default') : trans('backend.card.not_default')) . '</span>';

            })
            ->rawColumns(['last_four', 'brand', 'is_default'])
            ->toJson();
    }

    #endregion

    #region compares
    public function compares($user_id)
    {
        if (!permission_can('show compares', 'admin')) {
            return abort(403);
        }
        $datatable_route = route('backend.users.compares.datatable', $user_id);
        #region data table columns
        $datatable_columns['id'] = 'compares.id';
        $datatable_columns['image'] = 'image';
        $datatable_columns['title'] = 'title';
        $datatable_columns['price'] = 'price';
        $datatable_columns['created_at'] = 'created_at';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $view = view('backend.user.data.compares', compact('datatable_script'))->render();
        return response()->data(['view' => $view]);

    }

    public function compares_datatable($user_id)
    {
        $model = User::find($user_id)->compared_products();

        return datatables()->make($model)
            ->editColumn('image', function ($q) {
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . media_file($q->image) . "'> </div>";
                return $html;
            })
            ->editColumn('title', function ($q) {
                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px"><span class="badge badge-light-primary badge-lg">
                                                       ' . $q->title . '</span></a>';
            })
            ->editColumn('price', function ($q) {
                return '<span class="badge badge-light-primary badge-lg fw-bold  ">' . currency($q->price) . '</span>';
            })
            ->rawColumns(['image', 'title', 'price'])
            ->toJson();
    }

    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status user', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $user = User::findOrFail($id);
        if ($user->status == 1) {
            $user->status = 0;
        } else {
            $user->status = 1;
        }
        if ($user->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion

    #region load cities
    public function load_cities(Request $request)
    {
        $models = City::where('country_id', request()->get('country'))
            ->get();

        return response()->data(['models' => $models]);
    }

    #endregion

    #region destroy
    public function destroy($id)
    {
        if (!permission_can('delete user', 'admin')) {
            return abort(403);
        }
        if (User::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete user', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            User::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region send reminder
    public function send_reminder($userID)
    {
        $user = User::findOrFail($userID);
        $this->setMailConfigurations();

        return $this->sendReminder($user);

    }

    #endregion


    #region account statement
    public function send_account_statement($userID)
    {
        $user = User::findOrFail($userID);
        return $this->SendStatementMail($user);
    }


    #endregion

}
