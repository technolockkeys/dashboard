<?php


namespace App\Services;

use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ProductsBrand;
use App\Models\SubAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchService
{
    private int $length = 12;

    private int $page = 1;
    private array $sortItmes = ['title', 'created_at', 'avg_rating', 'priority', 'total_reviews'];

    private string $sortBy = 'priority';

    private string $direction = 'desc';

    private Builder $model;

    private Request $request;

    private Collection $products;

    private Currency $currency;
    private array $products_result = [];
    private array $attributeRequestExpect = [
        'length',
        'page',
        'categories',
        'categories_slug',
        'types',
        'free-shipping',
        'bundled',
        'best-seller',
        'is_saudi_branch',
        'new-arrival',
        'manufacturers',
        'colors',
        'discount',
        'highest_price',
        'brands',
        'models',
        'years',
        'search_attributes',
        'search',
        'lowest_price',
        'disply_type',
    ];

    private array $filters = [
    ];

    private array $categories = [];
    private array $categoriesIds = [];

    private array $category_slug = [];
    private array $selected = [
        'categories' => [],
        'manufacturers' => [],

    ];

    private $allCategory;
    private array $allCategoryParents = [];


    public function __construct(Request $request, Currency $currency = null)
    {

        $this->allCategory = Category::query()->where('status', 1)->get();
        $this->model = Product::query()->where('status', 1);

        $this->request = $request;
        if (empty($currency)) {
            $this->currency = Currency::query()->where('is_default', 1)->first();
        }
        foreach ($this->allCategory as $item) {
            $this->categories[$item->slug] = $item;
        }
        $parents = [];
        foreach ($this->allCategory as $item) {
            if (empty($item->parent_id)) {
                $parents[0][] = $item;

            } else {
                $parents[$item->parent_id][] = $item;

            }
        }
        $this->allCategoryParents = $parents;


    }

    function page(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    function length(int $length): self
    {
        $this->length = $length;
        return $this;
    }

    function direction(string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    function sort(string $sort = null): self
    {
        if (!empty($sort))
            $this->sortBy = $sort;

        if ($this->sortBy == 'price') {
            $this->model->orderByRaw('COALESCE(sale_price , price) ' . $this->direction)
                ->orderBy('price', $this->direction)->orderBy('sale_price', $this->direction);
        } elseif ($this->sortBy != null && in_array($this->sortBy, $this->sortItmes)) {
            $this->model->orderBy($this->sortBy, $this->direction);
        } else {
            $this->model->orderBy('priority');
        }

        return $this;
    }

    private function productResult(): self
    {
        $result = [];
        foreach ($this->products as $item) {

            $result[] = $item->api_shop_data($this->currency);

        }
        $this->products_result = $result;
        return $this;
    }

    private function request(): self
    {
        $this->categorySlug();
        if ($this->request->has('page')) {
            $this->page($this->request->page);
        }
        if ($this->request->has('sort_by')) {
            $this->sort($this->request->sort_by);
        }
        if ($this->request->has('direction')) {
            $this->direction($this->request->direction);
        }

        return $this;
    }

    private function categorySlug(): self
    {

        if ($this->request->has('categories_slug')) {
            $categories_slug = explode(',', $this->request->categories_slug);

            foreach ($categories_slug as $item) {
                if (!empty($this->categories[$item])) {
                    $category_item = $this->categories[$item];
                } else {
                    $category_item = Category::query()
                        ->select('id', 'name', 'slug', 'description', 'banner', 'meta_title', 'meta_description')
                        ->where('slug', $item)
                        ->where('status', 1)
                        ->first();
                }
                if (!empty($category_item)) {
                    $this->category_slug = [
                        'name' => $category_item['name'],
                        'description' => $category_item['description'],
                        'slug' => $category_item['slug'],
                        'banner' => media_file($category_item['banner']),
                        'meta_description' => $category_item['meta_description'],
                        'meta_title' => $category_item['meta_title'],
                    ];
                    $ids = $this->categoryGetChildernIds($category_item['id']);
                    $this->model = $this->model->whereIn('category_id', $ids);

                }
            }

        }
        return $this;
    }

    private function search(): self
    {
        if ($this->request->has('search') && !empty($this->request->search)) {
            $search = $this->request->search;
            $this->model = $this->model->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('short_title', 'like', '%' . $search . '%')
                    ->orWhere('summary_name', 'like', '%' . $search . '%');
            });
        }
        return $this;
    }


    function manufacturer(): self
    {
        return $this;
    }

    function categoryGetChildernIds($id)
    {
        $data = [$id];
        foreach ($this->allCategory as $item) {
            if ($item->parent_id == $id) {
                $data[] = $item->id;
                foreach ($this->categoryGetChildernIds($item->id) as $item2) {
                    $data[] = $item2->id;
                }
            }
        }
        return $data;
    }

    #region checkbok
    private function freeShipping(): self
    {
        if ($this->request->has('free-shipping')) {
            $this->model = $this->model->where('is_free_shipping', 1);
            $this->filters[] = [
                'group' => 'others_filter',
                'slug' => 'free-shipping',
                'name' => trans('frontend.product.is_free_shipping')
            ];
        }
        return $this;
    }

    private function bundled(): self
    {
        if ($this->request->has('bundled')) {
            $this->model = $this->model->where('is_bundle', 1);
            $this->filters[] = [
                'group' => 'others_filter',
                'slug' => 'bundled',
                'name' => trans('backend.product.bundle')
            ];
        }
        return $this;
    }

    private function bestSeller(): self
    {
        if ($this->request->has('best-seller')) {
            $this->model = $this->model->where('is_best_seller', 1);
            $this->filters[] = [
                'group' => 'others_filter',
                'slug' => 'best-seller',
                'name' => trans('frontend.product.is_best_seller')
            ];
        }

        return $this;
    }

    private function saudiBranch(): self
    {
        if ($this->request->has('saudi-branch')) {
            $this->model = $this->model->where('is_saudi_branch', 1);
            $this->filters[] = [
                'group' => 'others_filter',
                'slug' => 'saudi-branch',
                'name' => trans('frontend.product.is_saudi_branch')
            ];
        }
        return $this;
    }

    private function newArrival(): self
    {
        if ($this->request->has('new-arrival')) {
            $this->model = $this->model->where('is_featured', 1);
            $this->filters[] = [
                'group' => 'others_filter',
                'slug' => 'new-arrival',
                'name' => trans('frontend.product.is_new_arrival')
            ];
        }
        return $this;
    }

    private function discount(): self
    {
        if ($this->request->has('discount')) {
            $this->model = $this->model->whereNot('discount_type', 'none');
            $this->filters[] = [
                'group' => 'others_filter',
                'slug' => 'discount',

                'name' => trans('frontend.product.has_discount')
            ];
        }
        return $this;
    }

    private function brandQuery(): self
    {
        $productIdBrands = [];
        $brandIds = [];
        $brandIds = ProductsBrand::query()
            ->whereIn('product_id', $this->model->pluck('id')->toArray());
        if (!empty($this->request->years)) {
            $years = explode(',', $this->request->years);

            $brandIds->leftJoin('brand_model_years', 'products_brands.brand_model_year_id', 'brand_model_years.id')
                ->where(function ($q) use ($years) {
                    $q->whereIn('year', $years)->orWhereNull('year');
                })->groupBy('products_brands.brand_id');
        }
        if (!empty($this->request->brands)) {
            $productIds = [];
            //brand slugs
            $brands = explode(',', $this->request->brands);
            //get all brand ids

            $brandsId = $brandIds->groupBy('products_brands.brand_id')->pluck('products_brands.brand_id')->toArray();

            if (!empty($this->request->models)) {
                //models slugs
                $models = explode(',', $this->request->models);
                //brand names
                $brandsNames = Brand::query()->whereIn('slug', $brands)->whereIn('id', $brandsId)->get();

                $brandsId = [];
                foreach ($brands as $brand) {
                    foreach ($brandsNames as $brandsName)
                        if ($brandsName->slug == $brand) {
                            $brandsId[] = $brandsName->id;
                            $this->filters[] = [
                                'group' => 'brands',
                                'slug' => $brand,
                                'name' => $brandsName->make
                            ];
                        }
                }
                $modelsData = BrandModel::query()
                    ->whereIn('brand_id', $brandsId)
                    ->whereIn('slug', $models)->get();


                //all models is checks
                foreach ($modelsData as $modelsDatum) {
                    foreach ($models as $model) {
                        if ($modelsDatum->slug == $model)
                            $this->filters[] = [
                                'group' => 'models',
                                'slug' => $model,
                                'name' => $modelsDatum->model,
                            ];
                    }
                }


                $modelsIds = [];
                $brandsIds = [];
                foreach ($modelsData as $item) {
                    $modelsIds[] = $item->id;
                    $brandsIds[] = $item->brand_id;
                }
                $productId = ProductsBrand::query()
                    ->whereIn('brand_id', $brandsIds)
                    ->where(function ($q) use ($modelsIds) {
                        $q->whereIn('brand_model_id', $modelsIds)
                            ->orWhereNull('brand_model_id');
                    });

                $productId = $productId->groupBy('product_id')->pluck('product_id')->toArray();

                $productIds = array_merge($productIds, $productId);
                $this->model = $this->model->whereIn('id', $productId);

            }
            if (!empty($this->request->brands) && empty($this->request->models)) {
                $brandsNames = Brand::query()
                    ->when(!empty($brandIds), function ($q) use ($brandIds) {
                        $q->whereIn('id', $brandIds->groupBy('products_brands.brand_id')->pluck('products_brands.brand_id'));
                    })->whereIn('slug', $brands)->pluck('make', 'slug');
                foreach ($brands as $brand) {
                    $this->filters[] = [
                        'group' => 'brands',
                        'slug' => $brand,
                        'name' => !empty($brandsNames[$brand]) ? $brandsNames[$brand] : ''
                    ];
                }
                $productId = ProductsBrand::query()
                    ->join('brands', 'brands.id', 'products_brands.brand_id')
                    ->whereIn('brands.slug', $brands)
                    ->where('brands.status', 1)
                    ->groupBy('product_id');
                $productId = $productId->pluck('product_id')->toArray();
                $productIdBrands = $productId;
                $productIds = array_merge($productIds, $productId);
                $this->model = $this->model->whereIn('id', $productId);
            }


        }
        if (!empty($this->request->years)) {
            $years = explode(',', $this->request->years);
            foreach ($years as $year) {
                $this->filters[] = [
                    'group' => 'years',
                    'slug' => $year,
                    'name' => $year,
                ];
            }
            $productId = ProductsBrand::query()
                ->leftJoin('brand_model_years', 'products_brands.brand_model_year_id', 'brand_model_years.id')
                ->where(function ($q) use ($brandIds, $years) {
                    $q->whereIn('brand_model_years.brand_id', $brandIds->groupBy('products_brands.brand_id')->pluck('products_brands.brand_id'))
                        ->whereIn('product_id', $this->model->pluck('id')->toArray())
                        ->where(function ($q) use ($years) {
                            $q->whereIn('year', $years);
                        });
                })->orWhereNull('products_brands.brand_model_year_id');
            $this->model = $this->model->whereIn('id', $productId->pluck('product_id')->toArray());
        }
        return $this;
    }

    #endregion

    function queryBuild(): self
    {
        $this->search()
            ->categoryQuery()
            ->searchAttributeQuery()
            ->manufacturersQuery()
            ->freeShipping()
            ->bundled()
            ->bestSeller()
            ->newArrival()
            ->discount()
            ->categorySlug()
            ->brandQuery()
            ->saudiBranch();


        return $this;
    }

    function productsData(): self
    {
        $data = $this->model->clone();
        $this->page = !empty($this->request->page) ? $this->request->page : 1;
        $this->length = !empty($this->request->length) ? $this->request->length : 12;
        if ($this->page != 1) {

            $data = $data->skip(($this->page - 1) * $this->length);
        }
        $data = $data->limit($this->length);
        $orderBy = 'title';
        $direction = 'asc';
        if ($this->request->has('order-by')) {
            $orderBy = $this->request->get('order-by');
        }
        if ($this->request->has('direction')) {
            $direction = $this->request->get('direction');
        }
        if ($orderBy == 'price') {
            $data->orderByRaw('COALESCE(sale_price , price) ' . $direction)
                ->orderBy('price', $direction)->orderBy('sale_price', $direction);
        } else {
            $data->orderBy($orderBy, $direction);
        }

        $this->products = $data->get();

        return $this;
    }

    function searchAttributeQuery(): self
    {
        if ($this->request->has('search_attributes') && !empty($this->request->search_attributes)) {

            $key = str_replace(' ', '', str_replace('-', '', $this->request->search_attributes));
            $regex = "REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(JSON_EXTRACT(`value`,'$.en'), '\\s+', ''), '" . '"' . "', ''),'-','') ";
            $product_ids = ProductsAttribute::query()
                ->join('sub_attributes', 'sub_attributes.id', 'products_attributes.sub_attribute_id')
                ->where('sub_attributes.status', 1)
                ->where(\DB::raw($regex), 'like', "%$key%")
                ->pluck('product_id');
            $this->model->whereIn('id', $product_ids);
        }
        return $this;
    }

    private function others_filter(): array
    {
        return [
            'group' => 'others_filter',
            'group_name' => trans('frontend.menu.others_filter'),
            'type' => 'checkbox',
            "items" => [
                ['checked' => request()->has('best-seller') && request()->get('best-seller') == true, 'slug' => "best-seller", 'name' => trans('frontend.product.is_best_seller')],
                ['checked' => request()->has('saudi-branch') && request()->get('saudi-branch') == true, 'slug' => "saudi-branch", 'name' => trans('frontend.product.is_saudi_branch')],
                ['checked' => request()->has('new-arrival') && request()->get('new-arrival') == true, 'slug' => "new-arrival", 'name' => trans('frontend.product.is_new_arrival')],
                ['checked' => request()->has('free-shipping') && request()->get('free-shipping') == true, 'slug' => "free-shipping", 'name' => trans('frontend.product.is_free_shipping')],
                ['checked' => request()->has('bundled') && request()->get('bundled') == true, 'slug' => "bundled", 'name' => trans('backend.product.bundle')],
                ['checked' => request()->has('discount') && request()->get('discount') == true, 'slug' => "discount", 'name' => trans('frontend.product.has_discount')],

            ]
        ];
    }

    function brandFilters()
    {
        $productIds = $this->model->pluck('id');
        $brands = [];
        $models = [];
        $years = [];
        $response = [];
        if (empty($productIds)) {
            return $brands;
        }
        $brandIds = ProductsBrand::query()
            ->whereIn('product_id', $this->model->pluck('id')->toArray())
            ->pluck('brand_id')
            ->toArray();
        $brands = Brand::query()->whereIn('id', $brandIds)->get();

        if (!empty($this->request->brands)) {
            $brandRequest = explode(',', $this->request->brands);
            $brandsIds = [];
            $modelsIds = [];

            foreach ($brands as $brand) {
                if (in_array($brand->slug, $brandRequest)) {
                    $brandsIds[] = $brand->id;
                    $brand->checked = 1;
                } else {
                    $brand->checked = 0;
                }
            }
            $brandIds = $brandsIds;
            $modelsData = ProductsBrand::query()->select(
                [
                    'products_brands.brand_id',
                    'brand_models.slug',
                    'brand_models.id',
                    'brand_models.model'
                ]
            )
                ->leftJoin('brand_models', 'products_brands.brand_model_id', 'brand_models.id')
                ->where(function ($q) {
                    $q->where('status', 1)->orWhereNull('status');
                })
                ->whereIn('products_brands.brand_id', $brandsIds)
                ->whereIn('products_brands.product_id', $productIds)
                ->groupBy('products_brands.brand_model_id')->get();


            $brands_get_models = [];

            $selected_models = explode(',', $this->request->models);
            $models_inserts = [];
            foreach ($modelsData as $item) {
                if (empty($item->slug) && !in_array($item->brand_id, $brands_get_models)) {
                    $itemsBrandModels = BrandModel::query()->select('brand_models.brand_id',
                        'brand_models.slug',
                        'brand_models.id',
                        'brand_models.model')->where('brand_id', $item->brand_id)->where('status', 1)->get();
                    foreach ($itemsBrandModels as $model) {
                        if (!empty($model->slug) && !in_array($model->id, $modelsIds)) {
                            $models[] = [
                                'name' => $model->model,
                                'slug' => $model->slug,
                                'checked' => in_array($model->slug, $selected_models),
                            ];
                            $modelsIds[] = $model->id;

                        }
                    }
                    $brands_get_models[] = $item->brand_id;

                } elseif (!empty($item->slug) && !in_array($item->id, $modelsIds)) {
                    $models[] = [
                        'name' => $item->model,
                        'slug' => $item->slug,
                        'checked' => in_array($item->slug, $selected_models),
                    ];
                    $modelsIds[] = $item->id;

                }

            }
            Log::info($modelsIds);


            $years = BrandModelYear::query()
                ->select(\DB::raw('year as name'), \DB::raw('year as slug'))
                ->leftJoin('products_brands', 'products_brands.brand_model_year_id', 'brand_model_years.id')
                ->leftJoin('brand_models', 'brand_models.id', 'brand_model_years.brand_model_id')
                ->where('brand_model_years.status', 1)
                ->whereIn('products_brands.product_id', $this->model->pluck('id')->toArray())
                ->OrWhereNull('products_brands.brand_model_year_id')
                ->where(function ($q) use ($brandsIds, $modelsIds) {
                    if (!empty($modelsIds)) {
                        $q->whereIn('products_brands.brand_model_id', $modelsIds);
                    } else {
                        $q->whereIn('brand_models.brand_id', $brandsIds);
                    }
                })
                ->groupBy('year')
                ->orderBy('year');

            $years = $years->get();

        } else {
            $years = BrandModelYear::query()
                ->where('status', 1)
                ->whereIn('brand_id', $brandIds)
                ->select(\DB::raw('year as name'), \DB::raw('year as slug'))->groupBy('year')->orderBy('year')->get();

        }
        if (!empty($this->request->years)) {
            $selected_year = explode(',', $this->request->years);
            foreach ($years as $yaer) {
                if (in_array($yaer->slug, $selected_year)) {
                    $yaer->checked = 1;
                } else {
                    $yaer->checked = 0;
                }
            }
        }
        $brandsData = $brands;
        $brands = [];
        foreach ($brandsData as $item) {
            $brands[] = [
                'name' => $item->make,
                'slug' => $item->slug,
                'checked' => $item->checked,
            ];
        }
        if (!empty($brands))
            $response['brands'] = [
                'group' => 'brands',
                'group_name' => trans('frontend.menu.brands'),
                'type' => 'checkbox',
                'items' => $brands
            ];
        if (!empty($models))
            $response['models'] = [
                'group' => 'models',
                'group_name' => trans('frontend.menu.models'),
                'type' => 'checkbox',
                'items' => $models
            ];
        if (!empty($years) && !empty($brands))
            $response['years'] = [
                'group' => 'years',
                'group_name' => trans('frontend.menu.years'),
                'type' => 'checkbox',
                'items' => $years
            ];
        return $response;

    }

    function categoryQuery(): self
    {
        $this->model->whereIn('category_id', $this->allCategory->pluck('id')->toArray());
        $this->selected['categories'] = $this->allCategory->pluck('id')->toArray();
        if (($this->request->slug_type == 'category' && !empty($this->request->categories)) || !empty($this->request->categories)) {

            $item = $this->allCategory->where('slug', 'like', urldecode($this->request->categories))->first();
            if (!empty($item)) {
                $categoryIds = $this->allCategory->where('parent_id', $item->id)->pluck('id')->toArray();
                $this->model->where(function ($q) use ($categoryIds, $item) {
                    $q->whereIn('category_id', $categoryIds)->orWhere('category_id', $item->id);
                });
            }

        }


        return $this;
    }

    private function categories()
    {
        $categories_items = $this->CategoryTree();


        return [
            'group' => 'categories',
            'group_name' => trans('frontend.menu.categories'),
            'type' => 'dropdown',

            'items' => $categories_items
        ];

    }

    function CategoryTree($parent = 0)
    {

        $data = [];
        if (!empty($this->allCategoryParents[$parent]))
            foreach ($this->allCategoryParents[$parent] as $item) {
                $allCategory = $this->allCategory;
                $categoryIds = $allCategory->where('parent_id', $item->id)->pluck('id')->toArray();
                $count = $this->model->clone()->where(function ($q) use ($categoryIds, $item) {
                    $q->whereIn('category_id', $categoryIds)->orWhere('category_id', $item->id);
                })->count();
                if ($count != 0)
                    $data[] = [
                        'name' => $item->name,
                        'slug' => $item->slug,
                        'id' => $item->id,
                        'count' => $count,
                        'children' => $this->CategoryTree($item->id),
                    ];

            }
        return $data;
    }

    function manufacturersQuery(): self
    {
        if (!empty($this->request->manufacturers)) {
            $manufacturers = explode(',', $this->request->manufacturers);

            $manufacturersData = Manufacturer::query()->where('status', 1)->whereIn('slug', $manufacturers)->get();
            $ids = [];
            foreach ($manufacturersData as $item) {
                $ids[] = $item->id;
                $this->filters [] = [
                    'group' => 'manufacturers',
                    'slug' => $item->slug,
                    'name' => $item->title,
                ];
            }

            $this->model->whereIn('manufacturer_id', $ids);
            if ($this->request->has('manufacturer-type') && !empty($this->request->get('manufacturer-type'))) {
                $this->model = $this->model->where('manufacturer_type', $this->request->get('manufacturer-type'));
            }
        }
        return $this;
    }

    function manufacturers(): array
    {
        $manufacturers_ids = $this->model->clone()->groupBy('manufacturer_id')->pluck('manufacturer_id');
        $manufacturers = Manufacturer::query()->where('status', 1)->whereIn('id', $manufacturers_ids)->get();
        $manufacturers_items = [];
        foreach ($manufacturers as $manufacturer) {
            $manufacturers_items[] = [
                'name' => $manufacturer->title,
                'slug' => $manufacturer->slug,
                'checked' => !empty($this->request->manufacturers) && in_array($manufacturer->slug, explode(',', $this->request->manufacturers)),
            ];
        }
        return [
            'group' => 'manufacturers',
            'group_name' => trans('frontend.menu.manufacturers'),
            'type' => 'checkbox',
            'items' => $manufacturers_items
        ];

    }

    function get()
    {
        $this->queryBuild();
        $total = $this->model->count();
        $this->productsData();
        $this->productResult();
        $result = $this->products_result;
        $response_data = [
            'total' => $total,
            'page' => intval($this->page),
            'total_pages' => ceil($total / $this->length),
            'length' => sizeof($this->products),
            'products' => $result,
            'sql' => $this->model->toSql(),
            'bindings' => json_encode($this->model->getBindings()),
            'md5' => md5(json_encode($this->request->except('page')))


        ];

        return collect($response_data)->toArray();
    }

    function categoriesDisplay()
    {
        $request = $this->request;

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

            foreach ($category->products->take(6) as $product) {
                $products[] = $product->api_shop_data($this->currency);
            }

            if (count($products) != 0) {
                $search_results[] = [
                    'category' => $category->name,
                    'slug' => $category->slug,
                    'products' => $products,
                ];
            }
        }
        return ['total' => $total_products, 'test' => count($categories), 'page' => intval($this->page), 'total_pages' => ceil($total_products / $this->length), 'length' => sizeof($search_results), 'products' => $search_results];
    }


    function filters()
    {
        $this->queryBuild();
        $data = [];
        $data['total'] = $this->model->clone()->count();
//$data['total']= 0 ;
        $data['others_filter'] = $this->others_filter();
        if (!empty($this->request->slug_type) && $this->request->slug_type == 'category') {
//            $data['category_slug'] = $this->category_slug;
//            $data['categories_slug'] = $this->category_slug;
        } else {

//            $data['categories'] = $this->categories();
        }
        $data['categories'] = $this->categories();
        $data['checked_items'] = [
            'groups' => 'checked',
            'items' => $this->filters
        ];
        $data['manufacturers'] = $this->manufacturers();
        $data = $data + $this->brandFilters();

        return collect($data)->toArray();


    }


}
