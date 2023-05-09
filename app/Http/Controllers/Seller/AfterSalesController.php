<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\AfterSales\GetOrderRequest;
use App\Http\Requests\Seller\AfterSales\SaveBlackListRequest;
use App\Http\Requests\Seller\AfterSales\SendEmailReqeust;
use App\Http\Requests\Seller\AfterSales\UpdateRequest;
use App\Mail\FeedbackEmail;
use App\Mail\OrderUserMail;
use App\Mail\SendCouponNotification;
use App\Models\Order;
use App\Models\User;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\SetMailConfigurations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use parallel\Events\Error\Timeout;
use function Symfony\Component\Mime\Header\get;

class AfterSalesController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use SetMailConfigurations;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!permission_can('show after sales', 'seller')) {
            return abort(401);
        }
        $filters[] = 'users';

        $datatable_route = route('seller.after-sale.datatable');
        #region data table  columns
        $datatable_columns = [];
        $datatable_columns['index'] = '';
        $datatable_columns['uuid'] = 'uuid';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['name'] = 'users.name';
        $datatable_columns['feedback'] = 'feedback';
        $datatable_columns['feedback_date'] = 'feedback_date';
        $datatable_columns['actions'] = 'actions';

        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);
        $create_button = $this->create_button(route('seller.orders.create'), trans('seller.orders.create_new_order'));
        $order_users_id = Order::query()->where('seller_id', auth('seller')->id())->pluck('user_id')->toArray();
        $users = User::query()
            ->select('users.*')
            ->where('users.seller_id', auth('seller')->id())
            ->orWhereIn('users.id', $order_users_id)
            ->join('orders','orders.user_id' ,'users.id')
            ->groupBy('users.id')
            ->get();
        return view('seller.after_sales.index', compact('datatable_script', 'create_button', 'users'));
    }

    public function datatable(Request $request)
    {
        $users_black_list = auth('seller')->user()->black_list_users;
        $users_black_list = json_decode($users_black_list);
        $query = Order::query()
            ->select('orders.uuid',
                'orders.created_at',
                'users.name',
                'users.avatar',
                'users.uuid as user_uuid',
                'users.provider_type',
                'feedback',
                'feedback_date',
                'feedback_send_email'
            )
            ->where('orders.status', Order::$completed)
            ->where('orders.seller_id', auth('seller')->id())
            ->join('users', 'users.id', 'orders.user_id')
           ;
        if (!empty($users_black_list)) {
            $query = $query->whereNotIn('orders.user_id', $users_black_list);
        }
        $default_images = media_file(get_setting('default_images'));
        $permission_send = permission_can('send after sales', 'seller');
        $permission_resend = permission_can('resend after sales', 'seller');
        $permission_set_feedback = permission_can('set feedback after sales', 'seller');
        return datatables()->make($query)
            ->editColumn('index', function ($q) {
                return '';
            })
            ->editColumn('uuid', function ($q) {
                return '<a href="' . route('seller.orders.show', ['order' => $q->uuid]) . '" class="text-dark fw-bolder text-hover-primary fs-6">
<span class="badge badge-light-primary"><i class="las text-success la-cubes">
                        </i> ' . $q->uuid . '</span><br>
                   </a>';
            })
            ->editColumn('name', function ($q) use ($default_images) {
                $image = '';
                if ($q->provider_type == 'email') {
                    $image = asset($q->avatar);
                } else {
                    $image = $q->avatar;
                }

                return '<div class="d-flex align-items-center"><div class="symbol symbol-25px me-5">
                        <img src="' . $image . '" onerror="this.src=' . "'" . $default_images . "'" . '" alt=""></div><div class="d-flex justify-content-start flex-column">
                        <a href="' . route('seller.users.show', ['user' => $q->user_uuid]) . '" class="text-dark fw-bolder text-hover-primary fs-6">' . $q->name . '</a></div></div>';
            })
            ->editColumn('feedback', function ($q) {
                if (!empty($q->feedback)) {
                    return '<span  class="text-sm">' . mb_strimwidth($q->feedback, 0, 150, '...') . '</span>';
                }
                return '<span class="badge badge-light-danger">-</span>';
            })
            ->editColumn('feedback_date', function ($q) {
                if (!empty($q->feedback_date)) {
                    return '<span class="badge badge-light-dark">' . $q->feedback_date . '</span>';
                }
                return '<span class="badge badge-light-danger">-</span>';
            })
            ->addColumn('actions', function ($q) use ($permission_send, $permission_resend, $permission_set_feedback) {
                $view = '';
                if ($q->feedback_send_email == false) {
                    if ($permission_send) {
                        $view .= '<button   type="button"  id="' . $q->uuid . '"   class="btn send_email btn-sm ms-1 me-1 btn-light-primary"> <i class="las la-paper-plane"></i>' . trans('seller.after_sales.send_email') . '</button>';
                    }
                } else {
                    if ($permission_resend) {
                        $view .= '<button   type="button"  id="' . $q->uuid . '"   class="btn send_email btn-sm ms-1 me-1 btn-light-success"> <i class="las la-paper-plane"></i>' . trans('seller.after_sales.resend_email') . '</button>';
                    }
                }
                if ($permission_set_feedback) {
                    $view .= '<button  type="button"  id="' . $q->uuid . '"   class="btn set_feedback btn-sm ms-1 me-1 btn-light-info"> <i class="las la-comments"></i> ' . trans('seller.after_sales.set_feedback') . '</button>';
                }
                return $view;
            })
            ->rawColumns(['uuid', 'name', 'feedback', 'created_at', 'feedback_date', 'actions'])
            ->toJson();

    }

    public function get_order(GetOrderRequest $request)
    {
        $id = $request->uuid;
        $order = Order::query()
            ->select('orders.uuid',
                'orders.created_at',
                'users.name',
                'users.avatar',
                'users.provider_type',
                'feedback',
                'feedback_date',
                'feedback_send_email'
            )
            ->where('orders.status', Order::$completed)
            ->join('users', 'users.id', 'orders.user_id')
            ->where('orders.uuid', $id)
            ->where('orders.seller_id', auth('seller')->id())
            ->first();

        $order->avatar = $order->provider_type == 'email' ? asset($order->avatar) : $order->avatar;
        return response()->data(['order' => $order]);
    }

    public function update(UpdateRequest $request)
    {
        $order = Order::query()->where('uuid', $request->uuid)->first();

        $order->feedback = $request->feedback;
        $order->feedback_date = date('Y-m-d');
        $order->save();
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }

    public function send_email(SendEmailReqeust $request)
    {
        $order = Order::query()
            ->select('orders.uuid',
                'orders.created_at',
                'users.name',
                'orders.user_id',
                'users.avatar',
                'users.provider_type',
                'feedback',
                'feedback_date',
                'feedback_send_email'
            )
            ->where('orders.status', Order::$completed)
            ->join('users', 'users.id', 'orders.user_id')
            ->where('orders.uuid', $request->uuid)
            ->where('orders.seller_id', auth('seller')->id())
            ->first();

        $user = User::query()->where('id', $order->user_id)->first();
//        $order->feedback_send_email =1 ;
        Order::query()->where('uuid', $request->uuid)->update(['feedback_send_email' => 1]);
//        $order->save();
        if (!empty($user) && !empty($order)) {

            $name = $order->name;
//            $email = Mail::to($user->email)->queue(new FeedbackEmail(
//                trans('seller.after_sales.please_send_feedback_for_order', ['num' => $order->uuid]),
//                $order->name,
//                $user->seller->email));

            Mail::to($user->email)
                ->bcc(json_decode(get_setting('review_notifications_receivers')))
                ->queue(new OrderUserMail(trans('seller.after_sales.please_send_feedback_for_order', ['num' => $order->uuid]), ['order' => Order::query()->where('uuid', $request->uuid)->first()], 'feedback'));


            return $order;
        }

    }

    public function save_black_list(SaveBlackListRequest $request)
    {
        $user = auth('seller')->user();
        $user->black_list_users = $request->users;
        $user->save();
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }

}
