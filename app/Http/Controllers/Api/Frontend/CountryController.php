<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function all(Request $request)
    {
        $length = 12;
        $page = 1;
        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $data = Country::where('status', 1);
        $total = $data->count();
//        $data->skip(($page - 1) * $length);
//        $data->limit($length);
        $data = $data->get();

        foreach ($data as $item) {
            $result[] = [
                'id'=> $item->id,
                'name' => $item->name,
            ];
        }
        return response()->api_data(['length' => sizeof($data),'total' => $total, 'page' => intval( $page),  'total_pages' => ceil($total/$length),  'result' => $result,]);

    }
}
