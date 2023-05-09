<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\Frontend\User\Payment\CardController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\BrandModelRequest;
use App\Http\Requests\Api\Frontend\BrandYearRequest;
use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use Mpdf\Tag\Br;

class BrandController extends Controller
{
    public function get_models(BrandModelRequest $request)
    {
        $brand = Brand::where('slug', $request->slug)->first();

        $models = $brand->models()->where('status',1)->get();
        $res_models = [];
        foreach ($models as $model) {
            $res_models[] = [
                'name' => $model->model,
                'slug' => $model->slug
            ];
        }
        return response()->api_data(['models' => $res_models]);
    }

    public function get_years(BrandYearRequest $request)
    {
        $model = BrandModel::where('slug', $request->slug)->first();
        $years = $model->years()->where('status',1)->get();
        foreach ($years as $year) {
            $res_years[] = [
                'name' => $year->year,
                'slug' => $year->year
            ];
        }
        return response()->api_data(['years' => $res_years]);

    }
}
