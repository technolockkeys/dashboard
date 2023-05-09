<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use App\Models\User;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;


    #region index
    public function index()
    {
        if (!permission_can('show notifications', 'admin')) {
            return abort(403);
        }
        $filters[] = 'read';
        $filters[] = 'type';

        $filters_type = [];
//        $filters_type[Product::class]=trans('backend.menu.products');
//        $filters_type[Review::class]=trans('backend.menu.reviews');
//        $filters_type[Order::class]=trans('backend.menu.orders');


        $filters_type['out_of_stock'] = trans('backend.notifications.out_of_stock');
        $filters_type['low_quantity'] = trans('backend.notifications.low_quantity');
        $filters_type['review_added'] = trans('backend.notifications.review_added');
        $filters_type['new_order'] = trans('backend.notifications.new_order');
        $filters_type['order_delivered'] = trans('backend.notifications.order_delivered');
        $filters_type['order_canceled'] = trans('backend.notifications.order_canceled');
        $filters_type['order_refunded'] = trans('backend.notifications.order_refunded');
        $filters_type['order_is_paid'] = trans('backend.notifications.order_is_paid');
        $filters_type['stock_increase'] = trans('backend.notifications.stock_increase');

        $datatable_route = route('backend.notifications.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['title'] = 'title';
        $datatable_columns['content'] = 'content';
        $datatable_columns['sender_type'] = 'sender_type';
        $datatable_columns['sender'] = 'sender';
        $datatable_columns['notifiable_type'] = 'notifiable_type';
        $datatable_columns['read'] = 'read';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion


        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);

        return view('backend.notification.index', compact('datatable_script', 'filters_type'));
    }

    public function datatable(Request $request)
    {

        if (!permission_can('show notifications', 'admin')) {
            return abort(403);
        }
        $model = auth('admin')->user()->notifications();

        if ($request->has('read') && $request->read != -1) {
            $model = $model->where('read', $request->read);
        }
        if ($request->has('type') && !empty($request->type)) {
            $model = $model->where('type', $request->type);
        }
        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';
                $route = null;
                if ($q->notifiable_type == \App\Models\Product::class) {
                    $route = route('backend.products.edit', ['product' => $q->notifiable_id]);

                } elseif ($q->notifiable_type == \App\Models\Order::class) {
                    $route = route('backend.orders.show', ['order' => $q->notifiable_id]);
                }
                else if ($q->title == 'Contact Us') {
                    $route = route('backend.contact_us.index');
                }
                $actions = ' <a href="' . $route . '"  class="btn btn-primary read" data-id="' . $q->id . '">
                   ' . trans('backend.notifications.read') . '</a>';

                return $actions;
            })
            ->editColumn('notifiable_type', function ($q) {
                $badge_type = $q->notifiable_type == Product::class ? 'badge-light-primary' : 'badge-light-warning';
                $type = '';
                if ($q->notifiable_type == Product::class) {
                    $badge_type = 'badge-light-primary';
                    $type = trans('backend.menu.products');
                } elseif ($q->notifiable_type == Review::class) {
                    $badge_type = 'badge-light-warning';
                    $type = trans('backend.menu.reviews');
                } elseif ($q->notifiable_type == Order::class) {
                    $badge_type = 'badge-secondary';
                    $type = trans('backend.menu.orders');
                }

                return '<span class="badge badge-lg ' . $badge_type . ' fw-bold "  >' . $type . '</span>';
            })
            ->addColumn('sender', function ($q) {
                if ($q->sender)
                    return '<span class="badge badge-lg badge-light-primary fw-bold "  >' . $q->sender ?->name . '</span>';
                return '<span class="badge badge-lg badge-light-warning fw-bold "  >' . trans('backend.notifications.system') . '</span>';
            })
            ->editColumn('sender_type', function ($q) {
                if ($q->sender) {
                    $sender = $q->sender_type;
                    if ($q->sender_type === Admin::class) {
                        $sender = trans('backend.notifications.admin');
                    } elseif ($q->sender_type === 'App\Models\Seller') {
                        $sender = trans('backend.notifications.seller');
                    } elseif ($q->sender_type === User::class) {
                        $sender = trans('backend.notifications.user');
                    }
                    return '<span class="badge badge-lg badge-light-success fw-bold "  >' . $sender . '</span>';
                }
                return '<span class="badge badge-lg badge-light-primary fw-bold "  >' . trans('backend.notifications.system') . '</span>';
            })
            ->editColumn('read', function ($q) {
                return $q->read ? '<span class="badge badge-lg badge-light-primary fw-bold "  >' . trans('backend.notifications.read') . '</span>' :
                    '<span class="badge badge-lg badge-light-primary fw-bold "  >' . trans('backend.notifications.unread') . '</span>';
            })
            ->rawColumns(['actions', 'read', 'type', 'notifiable_type', 'sender', 'sender_type'])
            ->toJson();
    }
    #endregion

    #region read notifications
    public function read(Request $request)
    {
        Notification::find($request->id)->update([
            'read' => 1
        ]);

    }

    public function read_all()
    {
        auth('admin')->user()->notifications()->update(['read' => 1]);

        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }
    #endregion
}
