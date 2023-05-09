<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\Status\GetRequest;
use App\Models\Status;

class StatusController extends Controller
{
    function get(GetRequest $request)
    {

        $total = Status::query()->where('status', 1)->count();
        $length = 12;
        $page = 1;
        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $data = Status::query()->where('status', 1);
        $data->skip(($page - 1) * $length);
        $data->limit($length);
        $data->orderBy('order');
        $data = $data->get();
        $result = [];
        foreach ($data as $item) {
            if ($item->type == 'image')
                $value = media_file($item->value);
            else
                $value = $item->value;
            $result[] = [
                'id' => $item->id,
                'image' => get_multisized_image($item->image)['s']['url'],
                'type' => $item->type,
                'value' => $value,
                'order' => $item->order
            ];
        }

        return response()->api_data(['total' => $total, 'page' => intval( $page),  'total_pages' => ceil($total/$length),'length' => sizeof($result), 'result' => $result]);

    }
}
