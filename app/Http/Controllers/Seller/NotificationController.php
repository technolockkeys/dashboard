<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
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

        $filters[] = 'read';

        $datatable_route = route('seller.notifications.datatable');
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

        return view('seller.notification.index', compact('datatable_script',));
    }

    public function datatable(Request $request)
    {

        $model = auth('seller')->user()->notifications();

        if ($request->has('read') && $request->read != -1) {
            $model = $model->where('read', $request->read);
        }
        return datatables()->make($model)

            ->addColumn('actions', function ($q) {
                $actions = '';
                $route = "#";
                switch ($q->notifiable_type) {
                    case \App\Models\Product::class:
                        $route = "#";
                        break;
                    case \App\Models\Order::class:
                        $route = route('seller.orders.show', ['order' => Order::find($q->notifiable_id) ?->uuid]);
                        break;
                    case \App\Models\Ticket::class:
                        $route = "#";
                        break;
                    case \App\Models\Review::class:
                        $route = "#";
                        break;
                }
                $actions = ' <a href="' . $route . '"  class="btn btn-sm btn-primary read" data-id="' . $q->id . '">
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

                if ($q->sender_type == Seller::class) {
                    return '<span class="badge badge-lg badge-light-danger fw-bold "  >' . trans('backend.menu.sellers') . '</span>';
                } else if ($q->sender_type == User::class) {
                    return '<span class="badge badge-lg badge-light-success fw-bold "  >' . trans('backend.menu.users') . '</span>';
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
        auth('seller')->user()->notifications()->update(['read' => 1]);

        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }
    #endregion
}
