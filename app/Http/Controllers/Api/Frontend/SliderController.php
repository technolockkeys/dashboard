<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\Slider\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function get(SliderRequest $request)
    {
        $length = 12;
        $page = 1;
        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $data = Slider::query()->where('status', 1);
        if ($request->has('type') && !empty($request->type )){
            $data->where('type' , $request->type);
        }else{
            $data->where('type' , 'main' );
        }
        $total = $data->count();
        $data->skip(($page - 1) * $length);
        $data->limit($length);
        $data = $data->get();
        $result = [];
        foreach ($data as $item){
//            if($item->type == $request->type)
                $result[]= [
                    'image'=>media_file($item->image),
                    'link'=> $item->link,
                    'type' => $item->type
                ];
        }

        return response()->api_data(['total' => $total, 'length' => $length , 'page' => intval( $page),  'total_pages' => ceil($total/$length), 'result'=>$result]);
    }
}
