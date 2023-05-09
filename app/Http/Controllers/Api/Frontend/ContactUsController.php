<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\ContactUsRequest;
use App\Models\ContactUs;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{

    public function create(ContactUsRequest $request)
    {
        $contact_us = ContactUs::create($request->all());
        if ($request->model_id) {
            $model = Product::where('sku', $request->model_id)->first();
            $contact_us->model()->associate($model);
            $contact_us->save();
        }

        return response()->api_data(['message' => trans('frontend.contact.added_successfully')]);
    }
}
