<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Reply\CreateRequest;
use App\Http\Requests\Backend\Reply\UpdateRequest;
use App\Models\Color;
use App\Models\Ticket;
use App\Models\TicketReply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketReplyController extends Controller
{
    public function store(CreateRequest $request, $ticket_id)
    {

        $user = auth('admin')->user() ?? auth('user')->user();

        $ticket = Ticket::find($ticket_id);
        $ticket->status = $request->status;
        $ticket->last_reply = Carbon::now();

        $reply = new TicketReply();
        $reply->reply = $request->reply;
        $reply->ticket_id = $ticket_id;
        $reply->replyable_id = $user->id;
        $reply->replyable_type = get_class($user);

        if (!empty($request->file('files'))) {
            $reply->save_files($request->file('files'));
        }

        $reply->save();

        $ticket->save();
        return redirect()->back();
    }

    public function edit()
    {
        $reply = TicketReply::findOrFail(request('id'));
        $view = view('backend.ticket.edit', compact('reply'))->render();
        return response()->data(['view' => $view]);
    }

    public function update(UpdateRequest $request)
    {
        $reply = TicketReply::findOrFail($request->id);
        $reply->reply = $request->edit_reply;
        $final = [];
        $old_files = json_decode($reply->files);
        $files = [];
        if (!empty($request->file('files'))) {
            $files = $reply->save_files($request->file('files'));
        }
        foreach ($old_files as $old_file) {
            if (!empty($request->old_files) && is_array($request->old_files) && in_array($old_file->image_data, $request->old_files)) {
                $files [] = $old_file;
            } else {
                if (Storage::disk('public')->exists('tickets/' . $old_file->hashed_name)) {
                    \Storage::disk('public')->delete('tickets/' . $old_file->hashed_name);
                }
            }
        }

        $reply->files = json_encode($files);
        $reply->save();
        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
    }

    public function destroy($id)
    {
        if (TicketReply::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);

        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
}
