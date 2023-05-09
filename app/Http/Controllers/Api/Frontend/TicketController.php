<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\CreateTicketRequest;
use App\Http\Requests\Api\User\User\ProfileRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ticket;
use App\Traits\SerializeDateTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use SerializeDateTrait;

    function generate_order_code()
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
    }

    public function create(CreateTicketRequest $request)
    {
//        dd($request->all());
        $ticket = Ticket::make([
            'type' => $request->type,
            'subject' => $request->subject,
            'details' => $request->details,
            'sent_at' => Carbon::now()
        ]);
        $ticket->system_id = $this->generate_order_code();
        $ticket->status = 'open';

        if (in_array($request->type, ['order', 'product']) && $request->model_id) {
            $model = $request->type == 'order' ? Order::where('uuid', $request->model_id)->first()
                : Product::where('sku', $request->model_id)->first();
            $ticket->model()->associate($model);
        }


        $ticket->user()->associate(auth('api')->user());

        if (!empty($request->file('files'))) {
            $ticket->save_files($request->file('files'));
        }
        $ticket->save();

        $ticket->files = $ticket->get_files();
        return response()->api_data(['message' => trans('frontend.ticket.ticket_created'), 'ticket' => $ticket]);

    }


    public function tickets(ProfileRequest $request)
    {
        $tickets = Ticket::query()
            ->where('user_id', auth('api')->id())
            ->with('replies')
            ->orderByDesc('id');

        $length = 12;
        $page = 1;
        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $total = $tickets->count();
        $tickets = $tickets->skip(($page - 1) * $length)->limit($length)->get();
        $result = [];
        foreach ($tickets as $item) {
            $result[] = [
                'id' => $item->id,
                'system_id' => $item->system_id,
                'subject' => $item->subject,
                'details' => $item->details,
                'viewed' => $item->viewed,
                'last_reply' => $item->last_reply == '0000-00-00 00:00:00' ? "-" : $item->last_reply,
                'sent_at' => $item->sent_at == '0000-00-00 00:00:00' ? "-" : $item->sent_at,
                'created_at' => $item->created_at->format('Y-m-d H:i'),
                'status' => $item->status,
                'type' => $item->type,
                'files' => $item->get_files(),
                'replies' => $item->get_replies()
            ];
        }
        return response()->api_data(['total' => $total, 'page' => intval($page), 'total_pages' => ceil($total / $length), 'length' => $length, 'tickets' => $result]);
    }

    public function ticket(ProfileRequest $request, $system_id)
    {
        $ticket = Ticket::query()
            ->where('tickets.user_id', auth('api')->id())
            ->where('tickets.system_id', $system_id)->first();
        if ($ticket == null) {
            return response()->api_error(trans('frontend.ticket.ticket_not_found'));
        }
        $model= $ticket->model_id ;

        if ($ticket->type == 'product')
            $model =$ticket->model()->first()?->title;
        else if ($ticket->type == 'order')
        {
            $model =$ticket->model()->first()?->uuid;

        }
        $result = [
            'id' => $ticket->id,
            'system_id' => $ticket->system_id,
            'subject' => $ticket->subject,
            'details' => $ticket->details,
            'viewed' => $ticket->viewed,
            'last_replay' => $ticket->last_replay,
            'sent_at' => $ticket->sent_at,
            'status' => $ticket->status,
            'model' => $model,
            'model_id' => $ticket->model_id ,
            'type' => $ticket->type,
            'files' => $ticket->get_files(),
            'replies' => $ticket->get_replies()
        ];

        return response()->api_data(['ticket' => $result]);
    }


}
