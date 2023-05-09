<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\CreateTicketReplyRequest;
use App\Http\Requests\Api\Frontend\CreateTicketRequest;
use App\Models\Ticket;
use App\Models\TicketReply;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function reply(CreateTicketReplyRequest $request)
    {

        $ticket = Ticket::find($request->ticket_id);
        if($ticket->status == Ticket::$SOLVED){
            return response()->api_error(trans('frontend.ticket.ticket_is_solved'));
        }
        $ticket->last_reply = Carbon::now();
        $reply = TicketReply::make([
            'reply' => $request->reply,
        ]);

        $reply->replyable()->associate(auth('api')->user());
        $reply->ticket()->associate($ticket);
        if (!empty($request->file('files'))) {
            $reply->save_files($request->file('files'));
        }
        $reply->save();

        $reply->files = $reply->get_files();
        return response()->api_data(['message' => trans('frontend.ticket.reply_created'), 'reply' => $reply]);

    }

}
