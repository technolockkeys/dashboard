<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Product\GetRequest;
use App\Http\Requests\Api\User\Product\SingleProductRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\Currency;
use App\Models\Manufacturer;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ProductsBrand;
use App\Models\Review;
use App\Models\SubAttribute;
use App\Traits\ProductTrait;
use DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class ProductController extends Controller
{
    use ProductTrait;

    private $attributeRequestExpect = [
        'length',
        'page',
        'categories',
        'categories_slug',
        'types',
        'is_free_shipping',
        'is_bundled',
        'is_best_seller',
        'is_saudi_branch',
        'is_new_arrival',
        'manufacturers',
        'colors',
        'has_discount',
        'highest_price',
        'brands',
        'models',
        'years',
        'search_attributes',
        'search',
        'lowest_price',
        'disply_type',
    ];

    public function all(GetRequest $request)
    {
        $length = 12;
        $page = 1;
        $categories_slug = [];
        $category_slug = [];

        $currency = Currency::where(function ($q) use ($request) {
            $q->where('symbol', $request->header('currency'))
                ->orWhere('code', $request->header('currency'));

        })->where('status', 1)
            ->first();
        if (empty($currency)) {
            $currency = Currency::query()->where('is_default', 1)->first();
        }
        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $data = Product::query()->where('status', 1);

        if ($request->categories != null) {
            $categories = Category::query()->whereIn('slug', explode(',', $request->categories))->where('status', 1)->get();
            $ids = [];
            foreach ($categories as $category) {
                $ids = array_merge($ids, $category->api_children_ids());
            }

            $data = $data->whereIn('category_id', $ids);
        }
        if ($request->has('categories_slug') && !empty($request->categories_slug)) {
            $cate_slugs = explode(',', $request->categories_slug);
            $categories_slug = [];
            $categories_slug_id = [];
            $categories_slug_id_single = null;
            foreach ($cate_slugs as $item) {
                $category_item = Category::query()
                    ->select('name', 'slug', 'description', 'banner', 'meta_title', 'parent_id', 'meta_description', 'id')
                    ->where('slug', $item)->where('status', 1)->first();
                if (!empty($category_item)) {
                    if (count($categories_slug) != 0) {
                        if ($category_item->id == $categories_slug[count($categories_slug) - 1]['parent_id']) {
                            $categories_slug[] = $category_item;
                            $categories_slug[] = [
                                'name' => $category_item->name,
                                'description' => $category_item->description,
                                'slug' => $category_item->slug,
                                'meta_description' => $category_item->meta_description,
                                'meta_title' => $category_item->meta_title,
                                'banner' => $category_item->banner,
                                'parent_id' => $category_item->parent_id,
                            ];

                            $categories_slug_id_single = $category_item->id;
                        } else {
                            return response()->api_error([trans('api.category.not_found')]);

                        }
                    } else {
                        $categories_slug[] = [
                            'name' => $category_item->name,
                            'description' => $category_item->description,
                            'slug' => $category_item->slug,
                            'meta_description' => $category_item->meta_description,
                            'meta_title' => $category_item->meta_title,
                            'banner' => $category_item->banner,
                            'parent_id' => $category_item->parent_id,
                        ];
                        $categories_slug_id_single = $category_item->id;
                    }
                } else {
                    return response()->api_error([trans('api.category.not_found')]);

                }
            }
            if (count($categories_slug) != 0) {
                $category_slug = [
                    'name' => $categories_slug[count($categories_slug) - 1]['name'],
                    'description' => $categories_slug[count($categories_slug) - 1]['description'],
                    'slug' => $categories_slug[count($categories_slug) - 1]['slug'],
                    'banner' => media_file($categories_slug[count($categories_slug) - 1]['banner']),
                    'meta_description' => $categories_slug[count($categories_slug) - 1]['meta_description'],
                    'meta_title' => $categories_slug[count($categories_slug) - 1]['meta_title'],
                ];
                $categories_slug_id[] = $categories_slug_id_single;
                $data = $data->whereIn('category_id', $categories_slug_id);
            }
        }
        if ($request->types != null) {
            $categories = Category::whereIn('type', explode(',', $request->types))->pluck('id');
            $data = $data->whereIn('category_id', $categories);
        }

        if ($request->has('is_free_shipping')) {
            $data = $data->where('is_free_shipping', $request->has('is_free_shipping'));
        }
        if ($request->has('is_bundled')) {
            $data = $data->where('is_bundle', $request->has('is_bundled'));
        }
        if ($request->has('is_best_seller')) {

            $data = $data->where('is_best_seller', $request->has('is_best_seller'));
        }

        if ($request->has('is_saudi_branch')) {

            $data = $data->where('is_saudi_branch', $request->has('is_saudi_branch'));
        }

        //is_new_arrival == is_featured in the database
        if ($request->has('is_new_arrival')) {

            $data = $data->where('is_featured', $request->has('is_new_arrival'));
        }
        if ($request->has('manufacturer_type')) {

            $data = $data->where('manufacturer_type', $request->get('manufacturer_type'));
        }
        if ($request->manufacturers != null) {
            $manufacturers = Manufacturer::whereIn('slug', explode(',', $request->manufacturers))->pluck('id');
            $data = $data->whereIn('manufacturer_id', $manufacturers);
        }
        if ($request->colors != null) {
            $colors = Color::whereIn('slug', explode(',', $request->colors))->pluck('id');
            $data = $data->whereIn('color_id', $colors);
        }
        if ($request->has_discount != null) {
            if ($request->has_discount == 1) {
                $data = $data->whereNot('discount_type', 'none');
            } else {
                $data = $data->where('discount_type', 'none');
            }
        }

        if ($request->highest_price != null) {
            $data->selectRaw("IF(
                       products.discount_type != 'none'
                   AND
                    (         date(products.start_date_discount) <= CURDATE()  OR  products.start_date_discount  is NULL)
                   AND (
                                   date(products.end_date_discount) >= CURDATE()
                                   OR products.end_date_discount IS NULL
                                   OR products.end_date_discount = ''
                           ),
                       IF(
                                   products.discount_type = 'fixed',
                                   products.price - products.discount_value,
                                   products.price - (products.discount_value * products.price / 100)
                           ),
                       products.price) as filter_price, products.*")->havingBetween('filter_price', [$request->lowest_price, $request->highest_price])
                ->orWhereBetween('sale_price', [$request->lowest_price, $request->highest_price]);
        }

        if ($request->brands != null) {
            $brands_id = Brand::whereIn('slug', explode(',', $request->brands))->pluck('id');
            $products_ids = ProductsBrand::whereIn('brand_id', $brands_id)->pluck('product_id');
            $data->whereIn('id', $products_ids);
        }

        if ($request->models != null) {
            $models_id = BrandModel::whereIn('slug', explode(',', $request->brand_models))->pluck('id');
            $products_ids = ProductsBrand::whereIn('brand_model_id', $models_id)->orWhereNull('brand_model_id')->pluck('product_id');
            $data->whereIn('id', $products_ids);

        }

        if ($request->years != null) {
            $years_id = BrandModelYear::whereIn('year', explode(',', $request->years))->pluck('id');
            $products_ids = ProductsBrand::whereIn('brand_model_year_id', $years_id)->orWhereNull('brand_model_year_id')->pluck('product_id');
            $data->whereIn('id', $products_ids);
        }


        if ($request->search_attributes != null) {
            $data->whereHas('sub_attributes', function ($q) use ($request) {
                $q->where('value', 'like', '%' . $request->search_attributes . '%');
            });
        }

        if ($request->search != null && $request->disply_type == 'categories') {
            $categories = Category::where('status', 1)
                ->with('products', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%')
                        ->orWhere('sku', 'like', '%' . $request->search . '%')
                        ->orWhere('short_title', 'like', '%' . $request->search . '%')
                        ->orWhere('summary_name', 'like', '%' . $request->search . '%');

                })
                ->get();

            $search_results = [];
            $total_products = 0;
            foreach ($categories as $category) {
                $products = [];

                $total_products += $category->products->count();;

                foreach ($category->products->where('products.status', 1)->take(6) as $product) {
                    $products[] = $product->api_shop_data($currency);
                }

                if ($category->products->where('products.status', 1)->count() != 0) {
                    $search_results[] = [
                        'category' => $category->name,
                        'slug' => $category->slug,
                        'products' => $products,
                    ];
                }
            }
            return response()->api_data(['total' => $total_products, 'page' => intval($page), 'total_pages' => ceil($total_products / $length), 'length' => sizeof($search_results), 'products' => $search_results,]);
        }

        if ($request->search != null) {
            $data->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%')
                    ->orWhere('short_title', 'like', '%' . $request->search . '%')
                    ->orWhere('summary_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->sort_by != null && in_array($request->sort_by, ['title', 'created_at', 'avg_rating', 'priority', 'total_reviews'])) {
            $data->orderBy($request->sort_by, (empty($request->direction) ? 'desc' : $request->direction));
        } elseif ($request->sort_by == 'price') {
            $data->orderByRaw('COALESCE(sale_price , price) ' . (empty($request->direction) ? 'desc' : $request->direction))
                ->orderBy('price', (empty($request->direction) ? 'desc' : $request->direction))->orderBy('sale_price', (empty($request->direction) ? 'desc' : $request->direction));
        } else {
            $data->orderBy('priority');
        }

        $attributes_data = $request->except($this->attributeRequestExpect);

        $attributes_value = [];
        $attributes = [];
        foreach ($attributes_data as $key => $value) {
            try {

                $attributes_val = $request->get($key);
                if (!isset($attributes[$key])) {
                    $attr = Attribute::query()->where('slug', $key)->first();
                    $attributes[$key] = $attr?->id;
                }
                $attributes_val = explode(',', $attributes_val);
                foreach ($attributes_val as $item) {
                    $attributes_value[$attributes[$key]] = $item;
                }
            } catch (\Exception $exception) {

            }
        }
        if (!empty($attributes_value)) {


            $sub_attributes_ids = [];
            foreach ($attributes_value as $key => $value) {
                $sub_attributes = SubAttribute::query();
                $sub_attributes = $sub_attributes->where('attribute_id', $key)->whereIn('slug', $attributes_value);
                $sub_attributes = $sub_attributes->pluck('id');
                foreach ($sub_attributes as $item) {
                    $sub_attributes_ids [] = $item;
                }
            }

            if (!empty($sub_attributes_ids)) {
                $productAttributes = ProductsAttribute::query();
//                $productAttributes = $productAttributes->whereIn('sub_attribute_id', $sub_attributes_ids);
                foreach ($sub_attributes_ids as $item) {
                    $productAttributes = $productAttributes
                        ->whereIn('product_id',
                            ProductsAttribute::query()->select('product_id')->where('sub_attribute_id', $item)
                        );
                }
                $productAttributes = $productAttributes->pluck('product_id');
                $data = $data->whereIn('products.id', $productAttributes);
            }


        }

        $data = $data->where('products.status', 1);
        $total = $data->count();
        $data->skip(($page - 1) * $length);
        $data->limit($length);

        $data = $data->get();
        $result = [];


        foreach ($data as $item) {

            $result[] = $item->api_shop_data($currency);

        }
//        dd('after count:'. $total);
        $response_data = ['total' => $total, 'page' => intval($page), 'total_pages' => ceil($total / $length), 'length' => sizeof($result), 'products' => $result];
        if (!empty($categories_slug)) {
            $response_data['category_slug'] = $category_slug;
            $response_data['categories_slug'] = $categories_slug;
        }
        return response()->api_data($response_data);
    }

    public function show(SingleProductRequest $request, $slug)
    {


        $product = Product::where('status', 1)
            ->where('slug', $slug)
            ->with(['category', 'brands'])->first();
        if (empty($product)){
            return response()->api_error([trans('api.cart.coupon.not_found_product')] ,404);
        }
        $categories_parent= [];
        try {
            $categories_parent = $product->api_get_categories_parent();
        }catch (\Exception $exception){

        }
        $currency = request()->header('currency');
        $currency = Currency::where(function ($q) use ($currency) {
            $q->where('symbol', $currency)->orWhere('code', $currency);
        })->where('status', 1)->first();
        if (empty($currency)) {
            $currency = Currency::where('is_default', 1)->first();
        }

$tokens = [] ;
        if ($product == null) {
            return response()->api_error([trans('api.cart.coupon.not_found_product')]);
        }
        if (auth('api')->check()) {
            $user = auth('api')->user();
            $user->add_to_last_visited($product);
            $cart =Cart::query()->where('user_id', auth('api')->id())->where('product_id', $product['id'])->first() ;
            if (!empty($cart) ){
                try {
                    $tokens = json_decode($cart->note ,true);
                    if (!empty($tokens) && !empty($tokens['serial_number'])){
                        $tokens= $tokens['serial_number'];
                    }
                }catch (\Exception $exception){

                }
            }
        }

        $brands = [];
        foreach ($product->brands ?? [] as $key => $brand) {
            $years = ProductsBrand::where('product_id', $product->id)->where('brand_id', $brand->brand_id)->where('brand_model_id', $brand->brand_model_id)->pluck('brand_model_year_id');
            $brands[] = [
                'brand' => Brand::where('id', $brand->brand_id)->first()?->make,
                'model' => BrandModel::where('id', $brand->brand_model_id)->first()?->model,
                'years' => BrandModelYear::whereIn('id', $years)->pluck('year'),
            ];
        }

        $gallery = [
            get_multisized_image($product->image),
            get_multisized_image($product->secondary_image),
        ];
        foreach (json_decode($product->gallery, true) ?? [] as $image)
            $gallery[] = get_multisized_image($image);
        $pdf = [];
        foreach (json_decode($product->pdf, true) ?? [] as $file) {
            $data = media_file($file, true);
            $pdf[] = [
                'title' => $data->title,
                'path' => asset(('storage' . $data->path . $data->title))
            ];
        }
        $videos = [];
        foreach (json_decode($product->videos, true) ?? [] as $key => $video)
            $videos[$key] = $video;
        $offers = [];
        foreach ($product->offers as $key => $offer) {
            $offers[] = [
                'from' => $offer->from,
                'to' => $offer->to,
                'price' => api_currency($offer->price, $currency),
            ];
        }
        $price = $product->price;
        $sale_price = $product->sale_price;

        $discount = json_decode(json_encode($product->api_discount_form()), true);
        if (!$product->hide_price) {
            if (!empty($discount) && !empty($discount['type']) && isset($discount['type'])) {
                if (empty($sale_price)) {
                    $sale_price = ($discount['type'] == 'fixed') ? $price - $discount['value'] : $price - ($discount['value'] * $price / 100);
                } else {
                    $sale_price = ($discount['type'] == 'fixed') ? $sale_price - $discount['value'] : $sale_price - ($discount['value'] * $sale_price / 100);
                }
            }
            $price = api_currency($price, $currency);

            $sale_price = !$product->hide_price ? ($product->sale_price == 0 ? ["value" => '0', "currency" => $currency->symbol] : api_currency($sale_price, $currency)) : ["value" => '-', "currency" => $currency->symbol];

        } else {
            $price = ["value" => '-', "currency" => $currency->symbol];
            $sale_price = ["value" => '-', "currency" => $currency->symbol];
            $discount = null;
        }
        $has_token_input = isset($categories_parent[count($categories_parent) - 1]) && ($categories_parent[count($categories_parent) - 1]['slug'] == 'token' || $categories_parent[count($categories_parent) - 1]['slug'] == 'software');
        $sale_price = $this->product_price($product, 1);

        $product_info = [
            'id' => $product->id,
            'title' => $product->title,
            'short_title' => $product->short_title,
            'summary_name' => $product->summary_name,
            'description' => $product->description,
            'sku' => $product->sku,
            'slug' => $product->slug,
            'price' => $price,
            'stock' => $product->quantity,
            'sale_price' => $sale_price == $product->price ? ["value" => $product->price, "currency" => $currency->symbol] : api_currency($sale_price, $currency),
            'has_token_input' => $has_token_input,
            'is_sale' => $sale_price == $product->price ? 0 : 1,
            'avg_rating' => Review::query()->where('status', 1)->where('product_id', $product->id)->average('rating'),
            'total_reviews' => Review::query()->where('status', 1)->where('product_id', $product->id)->count(),
            'is_best_seller' => $product->is_best_seller,
            'is_saudi_branch' => $product->is_saudi_branch,
            'is_featured' => $product->is_featured,
            'is_free_shipping' => $product->is_free_shipping,
            'hide_price' => $product->hide_price,
            'type' => trans('backend.category.' . $product->category?->type),
            'twitter_image' => media_file($product->twitter_image),
            'videos' => $videos,
            'offers' => $offers,
            'pdf' => $pdf,
            'faq' => $product->faq,
            'meta' => [
                'title' => $product->meta_title,
                'description' => $product->meta_description,
            ],
            //if discount type is fixed? api_currency : value%
            'discount' => $discount,
            'gallery' => $gallery,
            'categories' => $categories_parent,
            'accessories' => $product->api_accessories($currency),
            'bundled' => $product->api_bundleds($currency),
            'attributes' => $product->api_attributes(),
            'specifications' => [
                'manufacturer' => $product->manufacturer?->title,
                'weight' => $product->weight,
                'color' => $product->color ? [
                    'name' => $product->color?->name,
                    'hex' => $product->color?->code
                ] : new stdClass(),
            ],
            'brands' => $brands
        ];

        #region related
        $related_products = Product::where('category_id', $product->category_id)
            ->whereNot('id', $product->id)->where('status', 1)->latest()->take(5)->get();
        $response_related_products = [];

        foreach ($related_products as $product) {
            $response_related_products [] = $product->api_shop_data($currency);

        }


        return response()->api_data([
            'product' => $product_info,
            'tokens' =>auth('api')->check() ?   $tokens: [],
            'related_products' => $response_related_products,
        ]);
    }

    public function filters(GetRequest $request)
    {
        $categories_ids = [];
        $manufacturers_ids = [];
        $colors_ids = [];
        $data = Product::query()->where('status', 1);
        $categories = null;
        $categories_slug = [];
        $category_slug = [];
        $attributes_slugs = $request->except($this->attributeRequestExpect);
        $attributes_data = [];
        $attributes_ids = [];
        $sub_attributes_ids = [];
        $product_is_has_attributes = [];
        $sub_attributes_slugs = [];
        foreach ($attributes_slugs as $slug => $value) {
            if (!empty($slug)) {
                $sub_slugs = explode(',', $request->get($slug));
                foreach ($sub_slugs as $item) {
                    $sub_attributes_slugs [] = $item;
                }
            }
        }
        $sub_attributes = SubAttribute::query();
        if (!empty($sub_attributes_slugs)) {
            $sub_attributes = $sub_attributes->whereIn('slug', $sub_attributes_slugs);
        }
        $sub_attributes = $sub_attributes->pluck('id', 'attribute_id');
        foreach ($sub_attributes as $key => $value) {
            $attributes_data[$key] = $value;
            $attributes_ids[] = $key;
            $sub_attributes_ids[] = $value;
        }
        $product_is_has_attributes = ProductsAttribute::query();
        if (!empty($attributes_slugs))
            foreach ($sub_attributes_ids as $item) {
                $product_is_has_attributes = $product_is_has_attributes
                    ->whereIn('product_id',
                        ProductsAttribute::query()->select('product_id')->where('sub_attribute_id', $item)
                    );

            }

        $product_is_has_attributes = $product_is_has_attributes->groupBy('product_id')->pluck('product_id');
        if ($request->has('categories_slug') && !empty($request->categories_slug)) {
            $cate_slugs = explode(',', $request->categories_slug);
            $categories_slug = [];
            $categories_slug_id = [];
            foreach ($cate_slugs as $item) {
                $category_item = Category::query()
                    ->select('name', 'slug', 'description', 'banner', 'meta_title', 'meta_description')
                    ->where('slug', $item)
                    ->where('status', 1)
                    ->first();
                if (!empty($category_item)) {
                    if (count($categories_slug) != 0) {
                        if ($category_item->id == $categories_slug[count($categories_slug) - 1]->parent_id) {
                            $categories_slug[] = $category_item;
                        }
                    } else {
                        $categories_slug[] = $category_item;
                    }
                    $categories_slug_id[] = $category_item->id;
                }

            }
            if (count($categories_slug) != 0) {
                $category_slug = $categories_slug[count($categories_slug) - 1];
                $data = $data->whereIn('category_id', $categories_slug_id);

            }


        }
        if ($request->categories != null) {
            $categories = Category::whereIn('slug', explode(',', $request->categories))->where('status', 1);
            $categories = $categories->pluck('id');
            $categories = Category::whereIn('id', $categories)->orWhereIn('parent_id', $categories)->where('status', 1);
            if ($request->product_type != null) {
                $categories = $categories->whereIn('type', explode(',', $request->product_type));
            }
            $categories = $categories->pluck('id');

            $data = $data->whereIn('category_id', $categories);
        }

        if ($request->product_type != null) {
            $categoriess = Category::whereIn('type', explode(',', $request->product_type))->where('status', 1)->get();
            $ids = [];
            foreach ($categoriess as $category) {
                $ids = array_merge($ids, $category->api_children_ids());
            }
            $data = $data->whereIn('category_id', $ids);
        }

        if ($request->free_shipping != null) {
            $data = $data->where('is_free_shipping', $request->free_shipping);
        }

        if ($request->has('bundled')) {
            $data = $data->where('is_bundle', $request->has('bundled'));
        }

        if ($request->manufacturers != null) {
            $manufacturers = Manufacturer::whereIn('slug', explode(',', $request->manufacturers))->pluck('id');
            $data = $data->whereIn('manufacturer_id', $manufacturers);
        }

        if ($request->colors != null) {
            $colors = Color::whereIn('slug', explode(',', $request->colors))->pluck('id');
            $data = $data->whereIn('color_id', $colors);
        }

        if ($request->has_discount != null) {
            if ($request->has_discount == 1) {
                $data = $data->whereNot('discount_type', 'none');
            } else {
                $data = $data->where('discount_type', 'none');
            }
        }
        if ($request->has('manufacturer_type')) {

            $data = $data->where('manufacturer_type', $request->get('manufacturer_type'));
        }

        if (!empty($sub_attributes_ids) && !empty($attributes_slugs)) {
            $data = $data->whereIn('products.id', $product_is_has_attributes);
        }
        #region categories
        $categories = Category::query()
            ->where('status', 1)
            ->where(function ($q) use ($categories, $request) {
                if (empty($categories)) {
                    $q->whereNull('parent_id')->orWhere('parent_id', 0);

                } else {
                    $q->orWhereIn('parent_id', $categories ?? [])
                        ->orWhereIn('id', $categories ?? []);
                }
            });

        if ($request->product_type != null && !empty($request->product_type)) {
            $categories = $categories->whereIn('type', explode(',', $request->product_type));
        }
        if ($request->types != null && !empty($request->types)) {
            $categories = $categories->whereIn('type', explode(',', $request->types));
        }
        if ($request->categories != null && !empty($request->categories)) {
            $categories = $categories->whereIn('slug', explode(',', $request->categories));
        }
        if ($request->categories_slug != null && !empty($request->categories_slug)) {
            $categories = $categories->whereIn('slug', explode(',', $request->categories_slug));
        }

        $categories = $categories->get();

        $categories_items = [];

        foreach ($categories as $category) {
            $categories_ids[] = $category->id;
            if ($category->parent_id == 0 || empty($category->parent_id)) {
                $child_item = [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'id' => $category->id,
                    'icon' => media_file($category->icon),
                    'banner' => media_file($category->banner),
                    'description' => $category->description,

                ];
                $childs = $category->shop_get_children();
                if (!empty($childs))
                    $child_item['children'] = $childs;
                $categories_items[] = $child_item;
            }

        }
        $products_id = Product::query()->where('status', 1)->whereIn('category_id', $categories_ids)->pluck('id');
        $res_categories = [
            'group' => 'categories',
            'group_name' => trans('frontend.menu.categories'),
            'type' => 'dropdown',
            'items' => $categories_items
        ];
        #endregion

        #region manufacturers


        $manufacturers = Manufacturer::query()->whereIn('id', $data->pluck('manufacturer_id'));
        if (!empty($categories_ids)) {
            $manufacturers_ids = Product::query()->whereIn('id', $products_id)->groupBy('manufacturer_id')->pluck('manufacturer_id');
            $manufacturers = $manufacturers->whereIn('id', $manufacturers_ids);
        }
        $manufacturers = $manufacturers->get();
        $manufacturers_items = [];
        foreach ($manufacturers as $manufacturer) {
            $manufacturers_items[] = [
                'name' => $manufacturer->title,
                'slug' => $manufacturer->slug,
                'checked' => !empty($request->manufacturers) && in_array($manufacturer->slug, explode(',', $request->manufacturers)),
            ];
        }
        $res_manufacturers = [
            'group' => 'manufacturers',
            'group_name' => trans('frontend.menu.manufacturers'),
            'type' => 'checkbox',
            'items' => $manufacturers_items
        ];
        #endregion

        #region colors
        $colors = Color::query();
//            ->whereIn('id', $data->pluck('color_id'));
        if (!empty($categories_ids) || !empty($manufacturers_ids)) {
            $colors_ids = Product::query();
            if (!empty($categories_ids)) {
                $colors_ids = $colors_ids->whereIn('category_id', $categories_ids);
            }
            if (!empty($manufacturers_ids)) {
                $colors_ids = $colors_ids->whereIn('manufacturer_id', $manufacturers_ids);
            }


            $colors_ids = $colors_ids->groupBy('color_id')->pluck('color_id');
            $colors = $colors->whereIn('id', $colors_ids);
        }

        $colors = $colors->get();
        $colors_item = [];
        foreach ($colors as $color) {
            $colors_item[] = [
                'name' => $color->name,
                'hex' => $color->code,
                'slug' => $color->slug,
                'checked' => !empty($request->colors) && in_array($color->slug, explode(',', $request->colors)),

            ];
        }
        $res_colors = [
            'group' => 'colors',
            'group_name' => trans('frontend.menu.colors'),
            'type' => 'color',
            'items' => $colors_item
        ];
        #endregion

        #region brands
        $products_brands = ProductsBrand::query()->whereIn('product_id', $data->pluck('id'));
        $products_brands = $products_brands->groupBy('brand_id')->get();
        $products_brands_model = ProductsBrand::query()
            ->whereIn('product_id', $data->pluck('id'))
            ->groupBy('brand_model_id')
            ->pluck('brand_model_id');
        $products_brands_model_year = ProductsBrand::query()->whereIn('product_id', $data->pluck('id'))->groupBy('brand_model_year_id')->pluck('brand_model_year_id');
        $all_models = false;
        $brand_ids = [];
        foreach ($products_brands as $item) {
            $brand_ids[] = $item->brand_id;
            if ($item->brand_model_id == null) {
                $all_models = true;
            }
            $brand_model_ids[] = $item->brand_model_id;
        }
        $brands = Brand::query()->where(function ($q) use ($request, $products_brands, $brand_ids) {


            $q->whereIn('id', $brand_ids);
            $brand_model_ids = [];
            if ($request->brands) {
                $q->orWhereIn('slug', explode(',', $request->brands));
            }
        });
        if ($request->slug_type == 'brand') {
            $brands = $brands->where('slug', $request->brands);
        }
        $brands = $brands->get();
        $brands_items = [];
        $brands_ids = [];
        foreach ($brands as $brand) {
            $brands_ids[] = $brand->id;
            $brands_items[] = [
                'name' => $brand->make,
                'slug' => $brand->slug,
                'checked' => !empty($request->brands) && in_array($brand->slug, explode(',', $request->brands)),

            ];
        }

        $res_brands = [
            'group' => 'brands',
            'group_name' => trans('frontend.menu.brands'),
            'type' => 'checkbox',
            'items' => $brands_items
        ];

        $brand_models = BrandModel::query();
        if (!$all_models) {
            $brand_models = $brand_models->where(function ($q) use ($products_brands_model) {
                if (!empty($products_brands_model))
                    $q->whereIn('id', $products_brands_model);
            });
        }

        if (!empty($request->brands)) {

            $brand_ids = Brand::query()->whereIn('slug', explode(',', $request->brands))->pluck('id');
            if (!empty($brand_ids)) {
                $brand_models = $brand_models->whereIn('brand_id', $brand_ids);
            }
        }
        if (!empty($brands_ids)) {
            $brand_models = $brand_models->get();
        } else {
            $brand_models = [];
        }

        $models_items = [];
        $models_ids = [];
        foreach ($brand_models as $brand) {
            $models_ids[] = $brand->id;
            $models_items[] = [
                'name' => $brand->model,
                'slug' => $brand->slug,
                'checked' => !empty($request->models) && in_array($brand->slug, explode(',', $request->models)),
            ];
        }
        $res_brand_models = [
            'group' => 'models',
            'group_name' => trans('frontend.menu.models'),
            'type' => 'checkbox',
            'items' => $models_items
        ];


        $brand_years = BrandModelYear::query()
            ->where(function ($q) use ($products_brands_model_year) {
                if (!empty($products_brands_model_year))
                    $q->whereIn('id', $products_brands_model_year);
            })
//            ->whereIn('id', $products_brands_model_year)
//            ->orWhereIn('brand_model_id', $products_brands_model_year)
            ->groupBy('year');
        if (!empty($models_ids)) {
            $brand_years = $brand_years->orWhereIn('brand_model_id', $models_ids);
        }
        if (!empty($request->models)) {
            $brand_years = $brand_years->orWhereIn('brand_model_id', explode(',', $request->models));
        }


        if (!empty($brand_ids)) {
            $brand_years = $brand_years->orWhereIn('brand_id', $brand_ids);
        }

        $brand_years = $brand_years->get();

        $years = [];

        foreach ($brand_years as $brand) {


            $years[] = [
                'name' => $brand->year,
                'slug' => $brand->year,
                'checked' => !empty($request->years) && in_array($brand->year, explode(',', $request->years)),
            ];
        }
        $res_years = [
            'group' => 'years',
            'group_name' => trans('frontend.menu.years'),
            'type' => 'checkbox',
            'items' => $years

        ];
        #endregion

        #region price range
        $currency = request()->header('currency');
        $currency = Currency::where('symbol', $currency)->orWhere('code', $currency)->first();

        $lowest_price = api_currency($data->min('price'), $currency);
        $highest_price = api_currency($data->max('price'), $currency);
//        $res_price = [
//            'group' => 'price_range',
//            'group_name' => trans('frontend.menu.price_range'),
//            'type' => 'range',
//            'lowest_price' => $lowest_price,
//            'highest_price' => $highest_price
//
//        ];
        #endregion

        #region types
        $types_data = [];
        if (!empty($categories_ids)) {
            $cate_type = Category::query()->whereIn('id', $categories_ids)->groupBy()->pluck('type');
            $has_software = false;
            $has_physical = false;

            foreach ($cate_type as $item) {
                if ($item == 'software' && !$has_software) {
                    $has_software = true;
                    $types_data [] = ['checked' => !empty($request->types) && in_array('software', explode(',', $request->types)), 'slug' => 'software', 'name' => trans('frontend.product.software')];
                } elseif ($item == 'physical' && !$has_physical) {
                    $has_physical = true;
                    $types_data [] = ['checked' => !empty($request->types) && in_array('physical', explode(',', $request->types)), 'slug' => 'physical', 'name' => trans('frontend.product.physical')];
                }
            }
        } else {
            $types_data = [
                ['checked' => !empty($request->types) && in_array('software', explode(',', $request->types)), 'slug' => 'software', 'name' => trans('frontend.product.software')],
                ['checked' => !empty($request->types) && in_array('physical', explode(',', $request->types)), 'slug' => 'physical', 'name' => trans('frontend.product.physical')]
            ];
        }
        $types = [
            'group' => 'types',
            'group_name' => trans('frontend.menu.types'),
            'type' => 'checkbox',
            'items' => $types_data
        ];
        #endregion

        #region attribute
        $all_attributes_ids = [];
        $attributes_filter = [];
        $all_sub_attributes_ids = ProductsAttribute::query()
            ->whereIn('product_id', $product_is_has_attributes)
            ->pluck('sub_attribute_id');


        if (!empty($all_sub_attributes_ids)) {
            $all_attributes_ids = SubAttribute::query()
                ->whereIn('id', $all_sub_attributes_ids)
                ->pluck('attribute_id');
        }
        if (!empty($all_attributes_ids)) {
            $attributes_filter = Attribute::query()->where('status', 1)->whereIn('id', $all_attributes_ids)->get();
            foreach ($attributes_filter as $attribute) {
                $attribute->sub_attributes = SubAttribute::query()
                    ->where('status', 1)
                    ->where('attribute_id', $attribute->id)
                    ->whereIn('id', $all_sub_attributes_ids)
                    ->get();
            }
        }
        $attribute_res = [];
        foreach ($attributes_filter as $attribute) {
            $sub_attributes_value = [];

            foreach ($attribute->sub_attributes as $item) {
                $checked = request()->has($attribute->slug);
                $checked_attr = false;
                if ($checked) {
                    $request_attribute = $request->get($attribute->slug);
                    $checked_attr = in_array($item->slug, explode(',', $request_attribute));
                }
                $sub_attributes_value[] = [
                    'name' => $item->value,
                    'slug' => $item->slug,
                    'checked' => $checked_attr,
                ];
            }
            if (!empty($sub_attributes_value)) {
                $attribute_res [] = [
                    'group' => $attribute->slug,
                    'group_name' => $attribute->name,
                    'type' => 'checkbox',
                    'items' => $sub_attributes_value
                ];
            }
        }

        #endregion

        #region checkboxes
        $checkboxes = [
            'group' => 'others_filter',
            'group_name' => trans('frontend.menu.others_filter'),
            'type' => 'checkbox',
            "items" => [
                ['checked' => request()->has('is_best_seller') && request()->get('is_best_seller') == true, 'slug' => "is_best_seller", 'name' => trans('frontend.product.is_best_seller')],
                ['checked' => request()->has('is_saudi_branch') && request()->get('is_saudi_branch') == true, 'slug' => "is_saudi_branch", 'name' => trans('frontend.product.is_saudi_branch')],
                ['checked' => request()->has('is_new_arrival') && request()->get('is_new_arrival') == true, 'slug' => "is_new_arrival", 'name' => trans('frontend.product.is_new_arrival')],
                ['checked' => request()->has('is_free_shipping') && request()->get('is_free_shipping') == true, 'slug' => "is_free_shipping", 'name' => trans('frontend.product.is_free_shipping')],
                ['checked' => request()->has('is_bundled') && request()->get('is_bundled') == true, 'slug' => "is_bundled", 'name' => trans('frontend.product.is_bundled')],
                ['checked' => request()->has('has_discount') && request()->get('has_discount') == true, 'slug' => "has_discount", 'name' => trans('frontend.product.has_discount')],

            ]
        ];
        #endregion


//        #region featured products
//        $featured_products = Product::query()->where('is_featured', 1)->take(6)->get();
//        $featured = [];
//        foreach ($featured_products as $product) {
//
//            $featured[] = $product->api_small_card_data($currency);
//        }
//        #endregion

        $response_data = [

        ];
        $response_data['checkboxes'] = $checkboxes;
        if (!empty($res_manufacturers['items'])) {
            $response_data['manufacturers'] = $res_manufacturers;
        }

        if (!empty($res_brands['items'])) {
            $response_data['brands'] = $res_brands;
        }
        if (!empty($res_brand_models['items'])) {
            $response_data['models'] = $res_brand_models;
        }
        if (!empty($res_years['items'])) {
            $response_data['years'] = $res_years;
        }
        if (!empty($res_categories['items'])) {
            $response_data['categories'] = $res_categories;
        }
//        if (!empty($types['items'])) {
//            $response_data['types'] = $types;
//        }

//        if (!empty($res_colors['items'])) {
//            $response_data['colors'] = $res_colors;
//        }
//        $response_data['price'] = $res_price;

        foreach ($attribute_res as $item) {
            $response_data[$item['group']] = $item;
        }
        if (!empty($categories_slug)) {
            $response_data['category_slug'] = $category_slug;
            $response_data['categories_slug'] = $categories_slug;
        }
        $response_data['total'] = rand(100, 1000);
        return response()->api_data($response_data);

    }

    public function suggested_product()
    {
        $currency = request()->header('currency');
        $currency = Currency::where('symbol', $currency)->orWhere('is_default', 1)->first();

        $user = auth('api')->user();
        $orders = $user->orders()
            ->orderByDesc('created_at')
            ->pluck('id');

        $order_products = OrdersProducts::whereIn('order_id', $orders);

        $categories = $order_products
            ->select(DB::raw('SUM(orders_products.quantity) as sold_quantity'), DB::raw('categories.id'))
            ->join('products', 'orders_products.product_id', 'products.id')
            ->join('categories', 'categories.id', 'products.category_id')
            ->orderByDesc('sold_quantity')
            ->with(['product.category', 'product'])
            ->groupBy('categories.id')
            ->limit(3)
            ->get();

        $order_products = OrdersProducts::whereIn('order_id', $orders)->pluck('product_id');
        $suggested_products = Product::whereIn('category_id', $categories->pluck('id'))
            ->whereNotIn('id', $order_products)->inRandomOrder()->limit(5)->get();
        $suggested_result = [];
        foreach ($suggested_products as $product) {
            $suggested_result[] = $product->api_shop_data($currency);
        }

        return response()->api_data([
            'suggested_products' => $suggested_result
        ]);

    }

    public function last_visited_products()
    {
        $currency = request()->header('currency');
        $currency = request()->header('currency');
        $currency = Currency::where(function ($q) use ($currency) {
            $q->where('symbol', $currency)->orWhere('code', $currency);
        })->where('status', 1)->first();
        if (empty($currency)) {
            $currency = Currency::where('is_default', 1)->first();
        }

        $visited_products = [];
        if (auth('api')->check()) {
            $last_visited = auth('api')->user()->last_visited;
            foreach ($last_visited as $product) {
                $visited_products[] = $product->api_shop_data($currency);
            }

            return response()->api_data(['recently_viewed' => $visited_products]);
        }
    }

    public function top_selling_products()
    {

        $currency = request()->header('currency');
        $currency = Currency::where(function ($q) use ($currency) {
            $q->where('symbol', $currency)->orWhere('code', $currency);
        })->where('status', 1)->first();
        if (empty($currency)) {
            $currency = Currency::where('is_default', 1)->first();
        }

        $products_quantity = OrdersProducts::query();

        $product_ids = $products_quantity
            ->select(DB::raw('COUNT(quantity) as sold_quantity'), 'product_id')
            ->groupBy('product_id')
            ->orderByDesc('sold_quantity')
            ->limit(request()->get('length') ?? 10)
            ->pluck('product_id');
        $top_selling_products = Product::query()->whereIn('id', $product_ids)->get();

        $top_selling = [];
        foreach ($top_selling_products as $product) {
            $top_selling[] = $product->api_shop_data($currency);
        }

        return response()->api_data(['top_selling' => $top_selling]);


    }

    public function random_products()
    {
        $random_products = Product::inRandomOrder()->limit(request()->get('length') ?? 5)->get();
        $currency = request()->header('currency');
        $currency = Currency::where('symbol', $currency)->orWhere('is_default', 1)->first();

        $products = [];
        foreach ($random_products as $product) {
            $products[] = $product->api_shop_data($currency);
        }
        return response()->api_data(['products' => $products]);
    }
}
