<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class TicketController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index()
    {
        if (!permission_can('show tickets', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $filters[] = 'type';
        $filters[] = 'start_date';
        $filters[] = 'end_date';


        $datatable_route = route('backend.tickets.datatable');
        $delete_all_route = permission_can('delete ticket', 'admin') ? route('backend.tickets.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['system_id'] = 'system_id';
        $datatable_columns['type'] = 'type';
        $datatable_columns['model'] = 'model';
        $datatable_columns['subject'] = 'subject';
        $datatable_columns['user'] = 'users.name';
        $datatable_columns['status'] = 'status';
        $datatable_columns['last_reply'] = 'last_reply';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['actions'] = 'actions';
        $statuses = [
            'solved' => trans('backend.ticket.solved'),
            'open' => trans('backend.ticket.open'),
            'pending' => trans('backend.order.pending')
        ];
        $types = ['order' => trans('backend.ticket.order'),
            'product' => trans('backend.ticket.product'),
            'support' => trans('backend.ticket.support'),
            'shipping' => trans('backend.ticket.shipping'),
            'other' => trans('backend.ticket.other')];
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);

        return view('backend.ticket.index', compact('datatable_script', 'types', 'statuses'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show tickets', 'admin')) {
            return abort(403);
        }
        $model = Ticket::query()
            ->join('users', 'tickets.user_id', 'users.id')
            ->select('tickets.*', 'users.name as user');

        if ($request->has('status') && $request->status != null) {
            $model = $model->where('tickets.status', $request->status);
        }
        if ($request->has('type') && $request->type != null) {
            $model = $model->where('type', $request->type);
        }
        if ($request->start_date != null) {
            $model = $model->whereBetween('tickets.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('delete ticket', 'admin')) {
                    $actions .= $this->delete_button(route('backend.tickets.destroy', ['ticket' => $q->id]), $q->name);
                }
                if (permission_can('show tickets', 'admin')) {
                    $actions .= $this->btn(route('backend.tickets.show', ['ticket' => $q->id]), '', 'las la-eye', 'btn-warning btn-show btn-icon');
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
                    ($q->model_type == Product::class? route('backend.products.edit', ['product' => $q->model?->id]): null);
                $html = '<a href="' . $route . '" class="btn btn-sm  btn-hover-rise  me-1"> <span class="badge badge-light-danger"> ' . $q->model?->short_title?? $q->model?->uuid?? trans('backend.global.not_found') . '</span></a>';
                return $html;
            })
            ->editColumn("user", function ($q) {
                return'<a href="' . route('backend.users.show', ['user' => $q->user_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->user . '</span>
                                                    </a>';
            })
            ->rawColumns(['actions', 'user', 'type', 'status','model'])
            ->toJson();
    }

    #endregion

    public function show($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket->status == 'pending') {
            $badge_type = 'badge-light-primary';
        } elseif ($ticket->status == 'open') {
            $badge_type = 'badge-light-warning';
        } elseif ($ticket->status == 'solved') {
            $badge_type = 'badge-light-success';
        }
        return view('backend.ticket.show', compact('ticket'));
    }

    #region delete
    public function destroy($id)
    {
        if (Ticket::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete ticket', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Ticket::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

}
