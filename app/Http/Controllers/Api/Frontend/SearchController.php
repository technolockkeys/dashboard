<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Product\GetRequest;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function products(GetRequest $request)
    {
        $search = new SearchService($request);
        if ($request->search != null && $request->disply_type == 'categories') {
            return response()->api_data($search->categoriesDisplay());
        }
        return response()->api_data($search->get());
    }

    function filter(GetRequest $request)
    {
        $search = new SearchService($request);

        return response()->api_data($search->filters());
    }
}
