<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Frontend\Download\GetRequest;
use App\Models\Download;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function all(GetRequest $request)
    {
        $length = 12;
        $page = 1;
        if ($request->length != null) {
            $length = $request->length;
        }
        if ($request->page) {
            $page = $request->page;
        }
        $data = Download::query()->where('status', 1);
        $total = $data->count();
        $data->skip(($page - 1) * $length);
        $data->limit($length);
        $data = $data->get();
        $result = [];
        foreach ($data ?? [] as $item) {
            $gallery = [];
            foreach (json_decode($item->gallery, true) as $image)
                $gallery[] = get_multisized_image($image);
            $screen_shot = [];
            foreach (json_decode($item->screen_shot, true) as $image)
                $screen_shot[] = get_multisized_image($image);

            $attributes = [];
            foreach ($item->attributes as $attribute)
                $attributes[$attribute->type.''][] = [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'link' => $attribute->link,
                ];

            $result[] = [
                'title' => $item->title,
                'meta_title' => $item->meta_title,
                'description' => $item->description,
                'meta_description' => $item->meta_description,
                'slug' => $item->slug,
                'image' => get_multisized_image($item->image),
                'gallery' => $gallery,
                'screen_shot' => $screen_shot,
                'videos' => json_decode($item->video ?? [], true),
                'attributes' => $attributes

            ];
        }

        return response()->api_data(['total' => $total, 'length' => $data->count(), 'page' => intval( $page), 'total_pages' => ceil($total/$length), 'result' => $result]);
    }

    public function get($slug)
    {
        $download = Download::where('slug', $slug)->with('attributes')->first();

        if ($download == null) {
            return response()->api_error(['message' => trans('api.cart.coupon.not_found_product'), 'status' => 404]);
        }
        $gallery = [];
        foreach (json_decode($download->gallery, true) as $image)
            $gallery[] = get_multisized_image($image);
        $screen_shot = [];
        foreach (json_decode($download->screen_shot) as $image) {
            $screen_shot ['gallery'][] = get_multisized_image($image);
        }

        $attributes = [];
        foreach ($download->attributes as $attribute)
            $attributes[$attribute->type . ''][] = [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'link' => $attribute->link,
            ];

        $download = [
            'slug' => $download->slug,
            'title' => $download->title,
            'description' => $download->description,
            'meta_title' => $download->meta_title,
            'meta_description' => $download->meta_description,
            'image' => get_multisized_image($download->image),
            'internal_image' => get_multisized_image($download->internal_image),
            'screen_shot' => $screen_shot,
            'gallery' => $gallery,
            'video' => json_decode($download->video, true),
            'attributes' => $attributes
        ];
        return response()->api_data(['download' => $download]);
    }
}
