<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    public function get_translations()
    {
        $language = trans('frontend',[], request()->header('Accept-Language'));

        $data = [request()->header('Accept-Language')=>$language];
        return response()->api_data(['translations' =>$data]);

    }
}
