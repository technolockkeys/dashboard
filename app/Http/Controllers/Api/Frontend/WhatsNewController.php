<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\ReadNewRequest;
use Illuminate\Http\Request;

class WhatsNewController extends Controller
{
    public function get(Request $request)
    {
        $user = auth('api')->user();
        $length = 12;
        $page = 1;

        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }

        $news = [];
        $unread_total = $user->whatsnew()->where('read',0)->count();
        $total = $user->whatsnew()->count();

        $whatsnew = $user->whatsnew()->skip(($page - 1) * $length)->limit($length)->get();

        foreach ($whatsnew as $new)
        {
            $news[] = [
                'id' => $new->id,
                'message_id' => $new->message_id,
                'title' => $new->title,
                'content' => $new->content,
                'read' => $new->read,
                'created_at' => $new->created_at
            ];
        }

        return response()->api_data(['total' => $total, 'unread_total' => $unread_total, 'page' => intval( $page), 'total_pages' => ceil($total/$length), 'length'=> sizeof($news), 'news' => $whatsnew]);

    }

    public function get_news($id)
    {
        $user = auth('api')->user();

        $message = $user->whatsnew()->where('id', $id)->first();
        $message?->update([
            'read' => 1
        ]);
        return response()->api_data(['message' => $message]);
    }

    public function read(ReadNewRequest $request)
    {
        $user = auth('api')->user();
        $news = $user->whatsnew()->where('id', $request->id)->first();
        if($news == null){
            return  response()->api_error(['message' => trans('backend.global.not_found')]);
        }
        $news->update([
            'read' => 1
        ]);
        return response()->api_data(['message' => trans('backend.global.success_message.updated_successfully')]);

    }
}
