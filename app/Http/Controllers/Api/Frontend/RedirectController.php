<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\RedirectRequest;
use App\Models\UrlRedirect;

class RedirectController extends Controller
{
    public function redirect(RedirectRequest $request)
    {
        if($route =  UrlRedirect::check($request->url)){
            return response()->api_data(['new_route'=>$route]);
        }
        return response()->api_error(trans('frontend.page.not_found'), 404);
    }
}
