<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
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
        $data = Page::where('status', 1);
        $total = $data->count();
        $data->skip(($page - 1) * $length);
        $data->limit($length);
        $data = $data->get();

        foreach ($data as $item) {
            $result[] = [
                'slug' => $item->slug,
                'title' => $item->title,
                'meta_title' => $item->meta_title,
                'description' => $item->description,
                'meta_description' => $item->meta_description,
                'meta_image' => media_file($item->meta_image, true),
            ];
        }
        return response()->api_data(['total' => $total, 'page' => intval($page), 'total_pages' => ceil($total / $length), 'length' => $length, 'result' => $result]);

    }

    public function show($slug)
    {
        $category = Category::query()->where('slug', $slug)->where('status', 1)->first();
        if (!empty($category)) {
            $category = [
                'type' => 'category',
                'slug' => $category->slug,
                'title' => $category->name,
                'description' => $category->description,
                'banner' => media_file($category->banner),
                'meta_title' => $category->meta_title,
                'meta_description' => $category->meta_description,
                'meta_image' => media_file($category->meta_image),
                'status' => $category->status,
            ];
            return response()->api_data($category);
        }
        $manufacturer = Manufacturer::query()->where('slug', $slug)->where('status', 1)->first();
        if (!empty($manufacturer)) {
            $category = [
                'type' => 'manufacturer',
                'banner' => null,
                'slug' => $manufacturer->slug,
                'title' => $manufacturer->title,
                'description' => $manufacturer->description,
                'meta_title' => $manufacturer->meta_title,
                'meta_description' => $manufacturer->meta_description,
                'meta_image' => media_file($manufacturer->image),
                'status' => $manufacturer->status,
            ];
            return response()->api_data($category);
        }
        $brand = Brand::query()->where('slug', $slug)->where('status', 1)->first();
        if (!empty($brand)) {
            $category = [
                'type' => 'brand',
                'banner' => null,
                'slug' => $brand->slug,
                'title' => $brand->title,
                'description' => $brand->description,
                'meta_title' => $brand?->meta_title,
                'meta_description' => $brand?->meta_description,
                'meta_image' =>media_file( $brand->image),
                'status' => $brand->status,
            ];
            return response()->api_data($category);
        }


        $page = Page::where('slug', $slug)->first();

        if (!empty($page)) {
            $page = [
                'type' => 'page',
                'slug' => $page->slug,
                'title' => $page->title,
                'description' => $page->description,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
                'meta_image' =>get_multisized_image( $page->meta_image),
                'status' => $page->status,
            ];
            return response()->api_data($page);
        }


        return response()->api_error(trans('api.page.not_found'), 404);
    }
}
