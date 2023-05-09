<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Products\CheckSkuRequest;
use App\Http\Requests\Backend\Products\CheckSlugRequest;
use App\Http\Requests\Backend\Products\CreateNewRequest;
use App\Http\Requests\Backend\Products\GetProducts;
use App\Http\Requests\Backend\Products\ImportExcelFileRequest;
use App\Http\Requests\Backend\Products\StoreNewSubAttribute;
use App\Http\Requests\Backend\Products\StoreRequest;
use App\Http\Requests\Backend\Products\UpdateRequest;
use App\Http\Requests\Backend\Shared\ChangeColumnStatusRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Imports\NewProductsImport;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use App\Models\Category;
use App\Models\Color;
use App\Models\Country;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\OutOfStock;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ProductsBrand;

use App\Models\ProductsImport;
use App\Models\ProductsPackages;
use App\Models\ProductsSerialNumber;
use App\Models\Review;
use App\Models\SubAttribute;
use App\Models\User;
use App\Models\ZonePrice;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\ProductTrait;
use App\Traits\ShippingTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;
    use ProductTrait;
    use ShippingTrait;

    #region  index
    function index()
    {
        if (!permission_can('show product', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $filters[] = 'type';
        $filters[] = 'category';
        $filters[] = 'brand';
        $filters[] = 'manufacturer';
        $filters[] = 'is_featured';
        $filters[] = 'is_visibility';
        $filters[] = 'is_super_sales';
        $filters[] = 'is_best_seller';
        $filters[] = 'is_today_deal';
        $filters[] = 'is_on_sale';
        $filters[] = 'is_saudi_branch';
        $filters[] = 'price_is_hidden';
        $filters[] = 'max_price';
        $filters[] = 'min_price';
        $filters[] = 'is_bundle';
        $filters[] = 'is_free_shipping';
        $filters[] = 'discount_offer';
        $filters[] = 'has_serial_numbers';
        $filters[] = 'type';
        $filters[] = 'quantity';
        $filters[] = 'manufacturer_type';
        $filters[] = 'brand';
        $filters[] = 'model';
        $filters[] = 'year';
        $filters[] = 'start_date';
        $filters[] = 'end_date';

        $datatable_route = route('backend.products.datatable');
        $delete_all_route = permission_can('delete product', 'admin') ? route('backend.products.delete-selected') : null;
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['sku'] = 'sku';
        $datatable_columns['title'] = 'title';
        $datatable_columns['short_title'] = 'short_title';
        $datatable_columns['color_id'] = 'color_id';
        $datatable_columns['manufacturer'] = 'manufacturers.title';
        $datatable_columns['type'] = 'categories.type';
        $datatable_columns['image'] = 'image';
        $datatable_columns['price'] = 'price';
        $datatable_columns['sale_price'] = 'sale_price';
        $datatable_columns['quantity'] = 'quantity';
        $datatable_columns['priority'] = 'priority';
        $datatable_columns['category_id'] = 'category_id';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['is_featured'] = 'is_featured';
        //is best offer
        $datatable_columns['is_super_sales'] = 'is_super_sales';
        $datatable_columns['is_best_seller'] = 'is_best_seller';
        $datatable_columns['is_free_shipping'] = 'is_free_shipping';

        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.products.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $switch_column = route('backend.products.change.column');
        $switch_script_featured = $this->change_column_switch_script($switch_column, 'is_featured');
        $switch_script_super_sales = $this->change_column_switch_script($switch_column, 'is_super_sales');
        $switch_script_best_seller = $this->change_column_switch_script($switch_column, 'is_best_seller');
        $switch_script_free_shipping = $this->change_column_switch_script($switch_column, 'is_free_shipping');

        $types = ['software', 'physical'];
        $categories_id = Product::query()->where('status', 1)->pluck('category_id');
        $categories = Category::query()->where('status', 1)->whereIn('id', $categories_id)->get();
        $manufacturers = Manufacturer::query()->where('status', 1)->get();
        $manufacturer_types = [
            'software' => trans('backend.manufacturer.software'),
            'token' => trans('backend.manufacturer.token'),
        ];

        $brands = Brand::query()->get();
        $models = BrandModel::query()->get();
        $create_button = '';
        if (permission_can('create product', 'admin')) {
            $create_button = $this->create_button(route('backend.products.create'), trans('backend.product.create_new_product'));
        }
        return view('backend.product.index', compact('datatable_script', 'switch_script',
            'create_button', 'switch_script_featured', 'switch_script_free_shipping', 'switch_script_super_sales',
            'switch_script_best_seller', 'types', 'categories', 'manufacturers', 'manufacturer_types', 'brands',
            'models'));
    }

    function datatable(Request $request)
    {
        if (!permission_can('show product', 'admin')) {
            return abort(403);
        }
        $model = Product::query()
            ->select('categories.type as type',
                'categories.name as name',
                'products.*',
                'manufacturers.title as manufacturer_title',
                DB::raw('COUNT(products_serial_numbers.product_id) as serials_count')
            )
            ->join('categories', 'products.category_id', 'categories.id')
            ->leftjoin('products_serial_numbers', 'products_serial_numbers.product_id', 'products.id')
            ->leftjoin('manufacturers', 'products.manufacturer_id', 'manufacturers.id')
            ->groupBy('products.id');
//        if ($request->order[0]['column'] == 0) {
//            $model->orderByDesc('id');
//        }

        if ($request->quantity) {
            if ($request->quantity == 'empty') {
                $model = $model->where("quantity", '<=', 0);
            } elseif ($request->quantity == 'low') {
                $model = $model->whereBetween("quantity", [1, get_setting('low_product_quantity_alert')]);
            } elseif ($request->quantity == 'normal') {
                $model = $model->where("quantity", '>', get_setting('low_product_quantity_alert'));
            }
        }
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('products.status', $request->status);
        }
        if ($request->has('discount_offer') && $request->discount_offer != -1) {
            if ($request->discount_offer == 1) {
                $model->where('products.discount_value', '>', 0.0)->orWhereHas('offers');
            } else
                $model->where('discount_value', 0)->WhereDoesntHave('offers');

        }
        if ($request->has('is_free_shipping') && $request->is_free_shipping != -1) {
            $model = $model->where('is_free_shipping', $request->is_free_shipping);
        }
        if ($request->has('is_today_deal') && $request->is_today_deal != -1) {
            $model = $model->where('is_today_deal', $request->is_today_deal);
        }
        if ($request->has('is_best_seller') && $request->is_best_seller != -1) {
            $model = $model->where('is_best_seller', $request->is_best_seller);
        }
        if ($request->has('is_super_sales') && $request->is_super_sales != -1) {
            $model = $model->where('is_super_sales', $request->is_super_sales);
        }
        if ($request->has('is_visibility') && $request->is_visibility != -1) {
            $model = $model->where('is_visibility', $request->is_visibility);
        }
        if ($request->has('is_featured') && $request->is_featured != -1) {
            $model = $model->where('is_featured', $request->is_featured);
        }
        if ($request->has('is_bundle') && $request->is_bundle != -1) {
            $model = $model->where('is_bundle', $request->is_bundle);
        }
        if ($request->has('type') && $request->type != null) {
            $model = $model->where('type', $request->type);
        }
        if ($request->is_on_sale != -1) {
            if ($request->is_on_sale == 1)
                $model = $model->whereNotNull('sale_price');
            if ($request->is_on_sale == 0)
                $model = $model->whereNull('sale_price');

        }
        if ($request->is_saudi_branch != -1) {
            $model = $model->where('is_saudi_branch', $request->is_saudi_branch);
        }
        if ($request->price_is_hidden != -1) {
            $model = $model->where('hide_price', $request->price_is_hidden);
        }
        if ($request->min_price != null || $request->max_price != null) {
            $model = $model->whereBetween('price', [$request->min_price ?? 0, $request->max_price ?? 10000000]);
        }
        if ($request->has('category') && $request->category != null) {
            $model = $model->where('category_id', $request->category);
        }
        if ($request->has('manufacturer') && $request->manufacturer != null) {
            $model = $model->where('manufacturer_id', $request->manufacturer);
        }
        if ($request->type != null) {
            $model = $model->where('categories.type', $request->type);
        }
        if ($request->manufacturer_type != null) {
            $model = $model->where('manufacturer_type', $request->manufacturer_type);
        }
        if ($request->has_serial_numbers != -1) {
            $model = $request->has_serial_numbers == 0 ?
                $model->having('serials_count', 0) :
                $model->having('serials_count', '>', 0);
        }

        if ($request->start_date != null) {
            $model = $model->whereBetween('products.created_at', [\Illuminate\Support\Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }
        if ($request->brand != null) {
            $model = $model->whereHas('brands', function ($q) use ($request) {
                $q->where('brand_id', $request->brand);
            });

        }
        if ($request->model != null) {
            $model = $model->whereHas('brands', function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('brand_model_id', $request->model)->orWhereNull('brand_model_id');
                });
            });
        }
        if ($request->year != null) {
            $model = $model->whereHas('brands', function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('brand_model_year_id', $request->year)->orWhereNull('brand_model_year_id');
                });
            });
        }


        $permission = array(
            'edit' => permission_can('edit product', 'admin'),
            'create' => permission_can('create product', 'admin'),
            'delete' => permission_can('delete product', 'admin'),
            'change_status' => permission_can('change status product', 'admin'),
            'change_feature' => permission_can('change feature product', 'admin'),
            'change_visibility' => permission_can('change visibility product', 'admin'),
            'change_super_sales' => permission_can('change super sales product', 'admin'),
            'change_best_seller' => permission_can('change best seller product', 'admin'),
            'change_today_deal' => permission_can('change today deal product', 'admin'),
            'change_free_shipping' => permission_can('change free shipping product', 'admin'),
            'show_product_series_number' => permission_can('show product serial number', 'admin'),
        );
        $default_images = media_file(get_setting('default_images'));
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->editColumn('sku', function ($q) {
                return $q->sku;
            })
            ->editColumn('title', function ($q) {
                return $q->title;
            })
            ->editColumn('short_title', function ($q) {
                return $q->short_title;
            })
            ->editColumn('created_at', function ($q) {
                return '<span class="badge badge-info">' . $q->created_at . '</span>';
            })
            ->editColumn('quantity', function ($q) {
                $class = $q->quantity == 0 ? 'danger' : 'primary';
                return '<span class="badge badge-light  badge-light-' . $class . '">' . $q->quantity . '</span>';
            })
            ->editColumn('priority', function ($q) {
                $class = $q->priority == 1 ? 'danger' : 'primary';
                return '<span class="badge badge-light  badge-light-' . $class . '">' . $q->priority . '</span>';
            })
            ->editColumn('updated_at', function ($q) {
                return '<span class="badge badge-light">' . $q->updated_at . '</span>';
            })
            ->editColumn('price', function ($q) {
                return '<span class="badge badge-light-info">' . currency($q->price) . '</span>';
            })
            ->editColumn('manufacturer', function ($q) {
                return $q->manufacturer ?
                    '<a href="' . route('backend.manufacturers.edit', ['manufacturer' => $q->manufacturer]) . '"
                                                       class="symbol    symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->manufacturer?->title . '</span>
                                                    </a>'
                    : '<span class="badge badge-light-danger"> <i class="las la-bell"></i>' . trans('backend.global.not_found') . '</span>';
            })
            ->editColumn('sale_price', function ($q) {
                if (empty($q->sale_price)) {
                    return '<span class="badge badge-light-danger"> <i class="las la-bell"></i>' . trans('backend.global.not_found') . '</span>';

                }
                return '<span class="badge badge-light-dark">' . currency($q->sale_price) . '</span>';
            })
            ->editColumn('status', function ($q) use ($permission) {
                return $this->status_switch($q->id, $q->status, 'status', !$permission['change_status']);
            })
            ->editColumn('is_featured', function ($q) use ($permission) {
                return $this->status_switch($q->id, $q->is_featured, 'is_featured', !$permission['change_feature']);
            })
            ->editColumn('is_visibility', function ($q) use ($permission) {
                return $this->status_switch($q->id, $q->is_visibility, 'is_visibility', !$permission['change_visibility']);
            })
            ->editColumn('is_best_seller', function ($q) use ($permission) {
                return $this->status_switch($q->id, $q->is_best_seller, 'is_best_seller', !$permission['change_best_seller']);
            })
            ->editColumn('is_super_sales', function ($q) use ($permission) {
                $text = $q->is_best_seller == 1 ? 'active' : 'disabled';
                $class = $q->is_best_seller == 1 ? 'primary' : 'warning';
                return '<span class="badge badge-lg badge-light-' . $class . '">' . trans('backend.global.' . $text) . '</span>';
            })
            ->editColumn('is_today_deal', function ($q) use ($permission) {
                return $this->status_switch($q->id, $q->is_today_deal, 'is_today_deal', !$permission['change_today_deal']);
            })
            ->editColumn('is_free_shipping', function ($q) use ($permission) {
                return $this->status_switch($q->id, $q->is_free_shipping, 'is_free_shipping', !$permission['change_free_shipping']);
            })
            ->addColumn('actions', function ($q) use ($permission) {
                $actions = '';
                if ($permission['edit']) {
                    $actions .= $this->edit_button(route('backend.products.edit', ['product' => $q->id]));
                }
                if ($permission['delete']) {
                    $actions .= $this->delete_button(route('backend.products.destroy', ['product' => $q->id]));
                }
                if ($permission['show_product_series_number']) {
                    $actions .= $this->btn(route('backend.products.series.number', ['id' => $q->id]), trans('backend.product.serial_number'), '', 'btn btn-primary');
                }
                return $actions;
            })
            ->editColumn('image', function ($q) use ($default_images) {
                return '<img width="75px" onerror="this.src=' . "'" . $default_images . "'" . '" src="' . media_file($q->image) . '">';
            })
            ->editColumn('category_id', function ($q) {
                if ((!empty($q->category) && !empty($q->category->name))) {
                    return '<a href="' . route('backend.categories.edit', ['category' => $q->category_id]) . '"
                                                       class="symbol    symbol-50px  ">
                                                       <span class="badge badge-light-primary">
                                                       ' . $q->category?->name . '</span>
                                                    </a>';
                } else {
                    return '<span class="badge badge-light-danger"> <i class="las la-bell"></i>' . trans('backend.global.not_found') . '</span>';
                }
            })
            ->editColumn('color_id', function ($q) {
                $color = $q->color;
                if (!empty($color)) {
                    return '<a href="' . route('backend.colors.edit', ['color' => $q->color_id]) . '"
                                                       class="symbol    symbol-50px  ">
                                                       <span class="badge" style="background:' . $q->color?->code . '"">
                                                       ' . $q->color?->name . '</span>
                                                    </a>';
                } else {
                    return '<span class="badge badge-light-danger"> <i class="las la-bell"></i>' . trans('backend.global.not_found') . '</span>';
                }
            })
            ->editColumn('type', function ($q) {
                if (!empty($q->category) && $q->category->type) {
                    if ($q->type == 'software')
                        return '<span class="badge badge-light-warning">' . trans('backend.product.' . $q->category->type) . '</span>';
                    return '<span class="badge badge-light-success">' . trans('backend.product.' . $q->category->type) . '</span>';
                }
                return '-';


            })
            ->rawColumns(['actions', 'status', 'category_id', 'image',
                'is_featured',
                'type',
                'price',
                'sale_price',
                'created_at',
                'updated_at',
                'is_visibility',
                'is_super_sales',
                'is_best_seller',
                'is_today_deal',
                'color_id',
                'quantity',
                'priority',
                'is_free_shipping',
                'manufacturer'

            ])
            ->toJson();
    }
    #endregion

    #region edit
    function edit($id)
    {
        if (!permission_can('edit product', 'admin')) {
            return abort(403);
        }
        $product = Product::findOrFail($id);
        $sku = $product->sku;
//        $products_brands = ProductsBrand::query()->where('product_id', $id)->groupBy('brand_model_id')->get();
//        $products_years = ProductsBrand::query()->where('product_id', $id)->groupBy('brand_model_year_id')->pluck('brand_model_year_id')->toArray();
//        $brand_models = ProductsBrand::query()->where('product_id', $id)->groupBy('brand_model_id')->pluck('brand_model_id')->sortDesc()->toArray();
//        $brand_models_brand = ProductsBrand::query()->where('product_id', $id)->groupBy('brand_model_id')->pluck('brand_id')->sortDesc()->toArray();
//        $brand_brands_ids = ProductsBrand::query()->where('product_id', $id)->groupBy('brand_id')->pluck('brand_id')->toArray();

        $null_brands = ProductsBrand::query()
            ->select('products_brands.*',
                "brand_model_year_id AS min_year",
                "brand_model_year_id AS max_year")
            ->where('product_id', $id)
            ->where(function ($q) {
                $q->whereNull('brand_model_id')->orWhereNull('brand_model_year_id');
            });
        $products_brands = ProductsBrand::query()
            ->select('products_brands.*',
                \DB::raw("MIN(brand_model_years.year) AS min_year"),
                \DB::raw("MAX(brand_model_years.year) AS max_year"))
            ->leftJoin('brand_model_years', 'brand_model_years.id', 'products_brands.brand_model_year_id')
            ->where('product_id', $id)
            ->union($null_brands)
            ->groupBy(['brand_id', 'brand_model_id'])->with(['brand', 'brand_model', 'brand_year'])->get();
        foreach ($products_brands as $item) {
            $item->from_year_id = ProductsBrand::query()
                ->leftJoin('brand_model_years', 'brand_model_years.id', 'products_brands.brand_model_year_id')
                ->where('product_id', $id)
                ->where('brand_model_years.year', $item->min_year)
                ->where('brand_model_years.brand_model_id' , $item->brand_model_id)
                ->first()?->id;
            $item->to_year_id = ProductsBrand::query()
                ->leftJoin('brand_model_years', 'brand_model_years.id', 'products_brands.brand_model_year_id')
                ->where('product_id', $id)
                ->where('brand_model_years.brand_model_id' , $item->brand_model_id)

                ->where('brand_model_years.year', $item->max_year)->first()?->id;
        }


        $productsPackages = ProductsPackages::query()->where('product_id', $id)->get();
        $to = [];
        $from = [];
        $packages_price = [];

        foreach ($productsPackages as $item) {
            $to [] = $item->to;
            $from [] = $item->from;
            $packages_price [] = $item->price;
        }

        $products_attributes = ProductsAttribute::query()->where('product_id', $id)->pluck('sub_attribute_id')->toArray();
        $products_serial_numbers = ProductsSerialNumber::query()->where('product_id', $id)->get();
        $serial_number_value = [];
        $serial_number_ids = [];
        foreach ($products_serial_numbers as $item) {
            $serial_number_ids[] = $item->id;
            $serial_number_value[] = $item->serial_number;
        }
        $categories = Category::query()->where('status', 1)->get();
        $colors = Color::query()->where('status', 1)->get();
        $brands = Brand::query()->where('status', 1)->get();
        $attributes = Attribute::query()->where('status', 1)->get();
        $b = empty($product->bundled) ? [] : json_decode($product->bundled);
        $a = empty($product->accessories) ? [] : json_decode($product->accessories);
        $bundles = Product::query()->where('status', 1)->whereIn('id', $b)->pluck('title', 'id')->toArray();
        $accessories = Product::query()->where('status', 1)->whereIn('id', $a)->pluck('title', 'id')->toArray();

        $manufacturers = Manufacturer::where('status', 1)->get();

        $countries = Country::query()->where('status', 1)->get();
        $google_merchant = json_decode($product->google_merchant, true);
        // shipping method + shipping cost + shipping zone
        $shipping_cost = [];
        /*$fedex = get_setting('fedex');
        $aramex = get_setting('aramex');
        $ups = get_setting('ups');
        for ($i = 1; $i <= 10; $i++) {
            $ship_cost = ZonePrice::query()
                ->where("zone_id", $i)
                ->where('weight', "<=", $product->weight)
                ->where('weight', ">=", $product->weight)->first();
        $manufacturers = Manufacturer::where('status',1)->get();

            if (empty($ship_cost)) {
                $ship_cost = 0;
            } else {
                $ship_cost = $ship_cost->price;
            }
            $shipping_item['zone'] = $i;
            $fedex_cost = ($fedex + $ship_cost);
            $aramex_cost = ($aramex + $ship_cost);
            $ups_cost = ($ups + $ship_cost);

            $shipping_item['cost'] = [
                "DHL" => $ship_cost,
                "fedex" => $fedex_cost,
                "aramex" => $aramex_cost,
                "ups" => $ups_cost,
            ];
            $shipping_item['countries'] = Country::query()->where('status', 1)->where('zone_id', $i)->pluck('name');
            $shipping_cost[] = $shipping_item;
        }
*/

        #region reviews

        $filters = [];
        $datatable_route = route('backend.reviews.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'users.name';
//        $datatable_columns['title'] = 'products.title';
        $datatable_columns['rating'] = 'rating';
        $datatable_columns['status'] = 'status';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $switch_route = route('backend.reviews.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters, ['product' => $product->id, 'status' => -1], null, 'review_table');
        #endregion


        return view('backend.product.edit', compact('product', 'to', 'sku', 'bundles', 'google_merchant', 'shipping_cost', 'countries',
            'accessories', 'serial_number_value', 'serial_number_ids', 'from', 'packages_price', 'products_brands', 'switch_script', 'datatable_script',
            'products_attributes', 'products_serial_numbers', 'categories', 'colors', 'brands', 'attributes', 'manufacturers'));
    }

    function update(UpdateRequest $request, $id)
    {
        $google_merchant = [];
        $google_merchant['sku'] = $request->has('g_sku') && !empty($request->g_sku) ? $request->g_sku : "";
        $google_merchant['title'] = $request->has('g_title') && !empty($request->g_title) ? $request->g_title : "";
        $google_merchant['image'] = $request->has('g_image') && !empty($request->g_image) ? $request->g_image : "";
        $google_merchant['gallery'] = $request->has('g_gallery') ? $request->g_gallery : "";
        $google_merchant['image_url'] = $request->has('g_image') && !empty($request->g_image) ? media_file($request->g_image) : "";
        $google_merchant['gallery_url'] = [];
        if ($request->has('g_gallery') && !empty($request->g_gallery)) {
            foreach (json_decode($request->g_gallery) as $item_gallery) {
                $google_merchant['gallery_url'][] = media_file($item_gallery);
            }
        }
        $google_merchant['description'] = $request->has('g_description') && !empty($request->g_description) ? $request->g_description : "";
        $google_merchant['category'] = $request->has('g_category') && !empty($request->g_category) ? $request->g_category : "";
        $google_merchant['manufacturer'] = $request->has('g_manufacturer') && !empty($request->g_manufacturer) ? $request->g_manufacturer : "AfterBrand";
        $google_merchant['link'] = $request->has('g_link') && !empty($request->g_link) ? $request->g_link : "";
        $google_merchant['price'] = $request->has('g_price') && !empty($request->g_price) ? $request->g_price : "";
        $google_merchant['sale_price'] = $request->has('g_sale_price') && !empty($request->g_sale_price) ? $request->g_sale_price : "";
        $google_merchant['in_stock'] = $request->has('g_in_stock') && !empty($request->g_in_stock) ? $request->g_in_stock : "";
        $google_merchant['weight'] = $request->has('g_weight') && !empty($request->g_weight) ? $request->g_weight : "";
        $google_merchant['mpn'] = $request->has('g_mpn') && !empty($request->g_mpn) ? $request->g_mpn : "";
        $google_merchant['gtin'] = $request->has('g_gtin') && !empty($request->g_gtin) ? $request->g_gtin : "";
        $g_attributes = [];
        $g_shipping_costs = [];
        if ($request->has('g_attribute_name')) {
            foreach ($request->g_attribute_name as $key => $attribute_name) {
                $g_attribute_itme = ['name' => $attribute_name, 'value' => $request->g_attribute_value[$key]];
                $g_attributes[] = $g_attribute_itme;
            }
        }
        /*
        if ($request->has('g_countries')) {
            foreach ($request->g_countries as $key => $g_country) {
                $g_country_code = Country::query()->where('name' , $g_country)->first();
                $g_country_itme = ['name' => $g_country_code->iso2, 'dhl' => $request->g_dhl[$key], 'fedex' => $request->g_fedex[$key], 'aramex' => $request->g_aramex[$key], 'ups' => $request->g_ups[$key]];
                $g_shipping_costs[] = $g_country_itme;
            }
        }
        $google_merchant['shipping_costs'] = $g_shipping_costs;
        */
        $google_merchant['attributes'] = $g_attributes;
        $title = [];
        $description = [];
        $summary_name = [];
        $title_meta = [];
        $description_meta = [];
        $faq = [];
        #region product
        $product = Product::findOrFail($id);
        $languages = get_languages();
        foreach ($languages as $language) {
            $title [$language->code] = $request->get('title_' . $language->code);
            $short_title [$language->code] = $request->get('short_title_' . $language->code);
            $description [$language->code] = $request->get('description_' . $language->code);
            $summary_name [$language->code] = $request->get('summary_name_' . $language->code);
            $title_meta [$language->code] = $request->get('meta_title_' . $language->code);
            $description_meta[$language->code] = $request->get('meta_description_' . $language->code);
            $faq[$language->code] = $request->has('faq_' . $language->code) ? $request->get('faq_' . $language->code) : $request->get('faq_' . $languages[0]->code);

        }
        $product->faq = $faq;
        $product->slug = $request->slug;
        $product->sku = $request->sku;
        $product->weight = $request->weight;
        $product->priority = $request->priority;
        $product->category_id = $request->category;
        $product->image = $request->image;
        $product->secondary_image = $request->has('secondary_image') ? $request->secondary_image : "";
        $product->twitter_image = $request->has('twitter_image') ? $request->twitter_image : "";
        $product->gallery = $request->has('gallery') ? $request->gallery : null;
        $product->pdf = $request->has('pdf') ? $request->pdf : null;
        #region videos
        $videos = [];
        if ($request->has('video_url') && !empty($request->video_url)) {
            foreach ($request->video_url as $key => $item) {
                $videos[] = ['link' => $item];
            }
        }
        $product->videos = json_encode($videos);
        #endregion

        #region competitors
        $competitors_url = [];
        if ($request->has('competitors_url') && !empty($request->competitors_url)) {
            foreach ($request->competitors_url as $key => $item) {
                $competitors_url[] = [
                    'url' => $item,
                    'selector' => $request->competitors_selector[$key],
                    'name' => $request->competitors_name[$key],
                    'html_tag' => $request->competitors_html_type[$key],
                    'price' => $request->competitors_price[$key],
                ];
            }
        }
        $product->competitors_price = json_encode($competitors_url);
        #endregion
        $product->price = $request->price;
        $product->sale_price = $request->has('sale_price') && !empty($request->sale_price) ? $request->sale_price : null;
        $product->discount_type = $request->discount_type;
        $product->color_id = $request->has('color') ? $request->color : null;
        $product->discount_value = $request->discount_type == 'none' ? 0.0 : ((empty($request->discount_value)) ? 0 : $request->discount_value);
        if ($request->date_type == 'for_ever') {
            $product->end_date_discount = null;
            $product->start_date_discount = date('Y-m-d', time());

        } else {
            $product->start_date_discount = ((empty($request->discount_range_start)) ? null : date('Y-m-d', Carbon::parse($request->discount_range_start)->getTimestamp()));
            $product->end_date_discount = ((empty($request->discount_range_end)) ? null : date('Y-m-d', Carbon::parse($request->discount_range_end)->getTimestamp()));
        }
        $product->title = $title;
        $product->short_title = $short_title;

        $product->summary_name = $summary_name;
        $product->description = $description;
        $product->meta_title = $title_meta;
        $product->meta_description = $description_meta;
        $product->meta_image = $request->meta_image;

        #region bundles
        if ($request->has('bundles') && !empty($request->bundles)) {
            $product->bundled = json_encode($request->bundles);
            $product->is_bundle = true;
        } else {
            $product->bundled = json_encode([]);
            $product->is_bundle = false;
        }

        #endregion
        #region Accessories
        $product->accessories = json_encode($request->has('accessories') && !empty($request->accessories) ? $request->accessories : []);
        $product->blocked_countries = $request->blocked_countries;
        #endregion
        $product->status = $request->has('status') && $request->status == 1;
        $product->is_best_seller = $request->has('is_best_seller') && $request->is_best_seller == 1;
        $product->is_saudi_branch = $request->has('is_saudi_branch') && $request->is_saudi_branch == 1;
        $product->is_featured = $request->has('is_featured') && $request->is_featured == 1;
        $product->is_free_shipping = $request->has('is_free_shipping') && $request->is_free_shipping == 1;
        $product->hide_price = $request->has('hide_price') && $request->hide_price == 1;
        $product->is_super_sales = $request->has('discount_value') && $request->discount_value != "";
        $product->back_in_stock = $request->has('back_in_stock') && $request->back_in_stock != "";
        $product->google_merchant = json_encode($google_merchant);
        $product->save();
        #endregion

        #region attributes
        if ($request->has('attribute') && !empty($request->attribute)) {
            $product_attributes = [];
            foreach ($request->attribute as $item) {
                if (ProductsAttribute::query()->where(['sub_attribute_id' => $item, 'product_id' => $product->id])->count() == 0) {
                    $product_attribute = new ProductsAttribute();
                    $product_attribute->sub_attribute_id = $item;
                    $product_attribute->product_id = $product->id;
                    $product_attribute->save();
                } else {
                    $product_attribute = ProductsAttribute::query()->where(['sub_attribute_id' => $item, 'product_id' => $product->id])->first();
                }


                $product_attributes [] = $product_attribute->id;
            }
            ProductsAttribute::query()->where('product_id', $product->id)->whereNotIn('id', $product_attributes)->delete();
        } else {
            ProductsAttribute::query()->where('product_id', $product->id)->delete();
        }
        #endregion

        #region brand
        ProductsBrand::query()->where('product_id', $product->id)->forceDelete();
        if ($request->brand != null) {
            foreach ($request->brand as $key => $brand) {
                if ($request->models[$key] != null) {
                    $model = $request->models[$key];
                    $model = BrandModel::find($model)->id;
                    $data = BrandModelYear::query()->where('brand_model_id', $model)->get();
                    if ($request->years_from[$key] != null) {
                        $year = $data->where('id', $request->years_from[$key])->first();
                        $year_to = $data->where('id', $request->years_to[$key])->first();
                        if (!empty($year_to)) {
                            for ($i = $year->year; $i <= $year_to->year; $i++) {
                                $year = $data->where('year', strval($i))->first();
                                if ($year != null) {
                                    ProductsBrand::firstOrCreate([
                                        'brand_id' => $brand,
                                        'brand_model_id' => $model,
                                        'brand_model_year_id' => $year->id,
                                        'product_id' => $product->id
                                    ]);
                                }
                            }
                        }

                    } else {
                        foreach ($data->groupBy('brand_model_id') as $year) {

                            ProductsBrand::firstOrCreate([
                                'brand_id' => $brand,
                                'brand_model_id' => $model,
                                'brand_model_year_id' => null,
                                'product_id' => $product->id
                            ]);
                        }

                    }
                } else {
                    ProductsBrand::firstOrCreate([
                        'brand_id' => $brand,
                        'brand_model_id' => null,
                        'product_id' => $product->id
                    ]);

                }
            }
        }


        #endregion

        #region packages
        if ($request->has('packages_price') && !empty($request->packages_price)) {
            $packages_price = [];
            foreach ($request->packages_price as $key => $item) {
                $productsPackages = ProductsPackages::query()->where([
                    'from' => $request->from[$key],
                    'to' => $request->to[$key],
                    'product_id' => $product->id,
                ])->first();
                if (empty($productsPackages)) {
                    $productsPackages = new ProductsPackages();
                    $productsPackages->from = $request->from[$key];
                    $productsPackages->to = $request->to[$key];
                    $productsPackages->price = $item;
                    $productsPackages->product_id = $product->id;
                }
                $productsPackages->save();
                $packages_price [] = $productsPackages->id;
            }
            ProductsPackages::query()->where('product_id', $product->id)->whereNotIn('id', $packages_price)->delete();
        } else {
            ProductsPackages::query()->where('product_id', $product->id)->delete();
        }
        #endregion

        #region serial numbers
        /*
        if ($request->has('serial_number') && !empty($request->serial_number)) {
            $product_serial_numbers = [];
            foreach ($request->serial_number as $item) {
                $product_serial_number = ProductsSerialNumber::query()->where([
                    'product_id' => $product->id,
                    'serial_number' => $item,
                ])->first();
                if (empty($product_serial_number)) {
                    $product_serial_number = new ProductsSerialNumber();
                    $product_serial_number->product_id = $product->id;
                    $product_serial_number->serial_number = $item;
                    $product_serial_number->save();

                }
                $product_serial_numbers[] = $product_serial_number->id;
            }
            ProductsSerialNumber::query()->whereNotIn('id', $product_serial_numbers)->delete();
        } else {
            ProductsSerialNumber::query()->where('id', $product->id)->delete();
        }*/
        #endregion

        #region manufacturer
        if ($request->manufacturer != null) {
            $manufacturer = Manufacturer::find($request->manufacturer);
            if ($manufacturer->token == 1 && $manufacturer->software == 1) {
                $product->manufacturer_type = $request->manufacturer_type;
            } else {
                $product->manufacturer_type = $manufacturer->token ? 'token' : ($manufacturer->software ? 'software' : null);
            }
            $product->save();
            $manufacturer->products()->save($product);
        } else {
            $product->manufacturer_id = null;
            $product->manufacturer_type = null;
            $product->save();
        }
        #endregion

        return redirect()->route('backend.products.edit', ['product' => $id])->with('success', trans('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region create
    function create()
    {
        $categories = Category::query()->where('status', 1)->get();
        $colors = Color::query()->where('status', 1)->get();
        $brands = Brand::query()->where('status', 1)->get();
        $manufacturers = Manufacturer::query()->where('status', 1)->get();
        $attributes = Attribute::query()->where('status', 1)->get();
        $sku = $this->generate_sku_code();
        $countries = Country::query()->where('status', 1)->get();
        return view('backend.product.create', compact('categories', 'attributes', 'colors', 'sku', 'brands', 'manufacturers', 'countries'));
    }


    function store(StoreRequest $request)
    {
        $product = new Product();
        $title = [];
        $description = [];
        $summary_name = [];
        $title_meta = [];
        $description_meta = [];
        $faq = [];
        $languages = get_languages();
        foreach ($languages as $language) {
            $title [$language->code] = $request->has('title_' . $language->code) && !empty($request->get('title_' . $language->code)) ? $request->get('title_' . $language->code) : $request->get('title_' . $languages[0]->code);
            $short_title [$language->code] = $request->has('short_title_' . $language->code) && !empty($request->get('short_title_' . $language->code)) ? $request->get('short_title_' . $language->code) : $request->get('short_title_' . $languages[0]->code);
            $description [$language->code] = $request->has('description_' . $language->code) && !empty($request->get('description_' . $language->code)) ? $request->get('description_' . $language->code) : $request->get('description_' . $languages[0]->code);
            $summary_name [$language->code] = $request->has('summary_name_' . $language->code) && !empty($request->get('summary_name_' . $language->code)) ? $request->get('summary_name_' . $language->code) : $request->get('summary_name_' . $languages[0]->code);
            $title_meta [$language->code] = $request->has('meta_title_' . $language->code) && !empty($request->get('meta_title_' . $language->code)) ? $request->get('meta_title_' . $language->code) : $request->get('meta_title_' . $languages[0]->code);
            $description_meta[$language->code] = $request->has('meta_description_' . $language->code) && !empty($request->get('meta_description_' . $language->code)) ? $request->get('meta_description_' . $language->code) : $request->get('meta_description_' . $languages[0]->code);
            $faq[$language->code] = $request->has('faq_' . $language->code) ? $request->get('faq_' . $language->code) : $request->get('faq_' . $languages[0]->code);
        }
        $product->slug = $request->slug;
        $product->sku = $request->sku;
        $product->priority = $request->priority;
        $product->category_id = $request->category;
        $product->image = $request->image;
        $product->twitter_image = $request->has('twitter_image') ? $request->twitter_image : "";
        $product->secondary_image = $request->has('secondary_image') ? $request->secondary_image : "";
        $product->gallery = $request->has('gallery') ? $request->gallery : null;
        $product->pdf = $request->has('pdf') ? $request->pdf : null;

        $product->blocked_countries = $request->blocked_countries;

        #region videos
        $videos = [];
        if ($request->has('video_url') && !empty($request->video_url)) {
            foreach ($request->video_url as $key => $item) {
                $videos[] = ['link' => $item];
            }
        }
        $product->videos = json_encode($videos);
        #endregion
        $product->weight = $request->weight;

        $product->price = $request->price;
        $product->sale_price = $request->has('sale_price') && !empty($request->sale_price) ? $request->sale_price : null;

        $product->quantity = 0;
        $product->discount_type = $request->discount_type;
        $product->discount_value = ((empty($request->discount_value)) ? 0 : $request->discount_value);
        if ($request->date_type == 'for_ever') {
            $product->end_date_discount = null;
            $product->start_date_discount = date('Y-m-d', time());

        } else {
            $product->end_date_discount = ((empty($request->discount_range_end)) ? null : date('Y-m-d', strtotime($request->discount_range_end)));
            $product->start_date_discount = ((empty($request->discount_range_start)) ? null : date('Y-m-d', strtotime($request->discount_range_start)));
        }
        $product->title = $title;
        $product->short_title = $short_title;
        $product->color_id = $request->has('color') ? $request->color : null;


        $product->summary_name = $summary_name;
        $product->description = $description;
        $product->meta_title = $title_meta;
        $product->meta_description = $description_meta;
        $product->faq = $faq;
        $product->meta_image = $request->meta_image;

        #region bundles
        if ($request->has('bundles') && !empty($request->bundles)) {
            $product->bundled = json_encode($request->bundles);
            $product->is_bundle = true;
        } else {
            $product->bundled = json_encode([]);
            $product->is_bundle = false;
        }

        #endregion

        #region Accessories
        $product->accessories = json_encode($request->has('accessories') && !empty($request->accessories) ? $request->accessories : []);
        #endregion
        $product->status = $request->has('status') && $request->status == 1;
        $product->is_best_seller = $request->has('is_best_seller') && $request->is_best_seller == 1;
        $product->hide_price = $request->has('hide_price') && $request->hide_price == 1;
        $product->is_super_sales = $request->has('discount_value') && $request->discount_value != "";
        $product->is_saudi_branch = $request->has('is_saudi_branch') && $request->is_saudi_branch == 1;
        $product->is_featured = $request->has('is_featured') && $request->is_featured == 1;
        $product->is_free_shipping = $request->has('is_free_shipping') && $request->is_free_shipping == 1;
        $product->save();


        #region attributes
        if ($request->has('attribute') && !empty($request->attribute)) {
            foreach ($request->attribute as $item) {
                $product_attribute = new ProductsAttribute();
                $product_attribute->sub_attribute_id = $item;
                $product_attribute->product_id = $product->id;
                $product_attribute->save();
            }
        }
        #endregion

        if ($request->manufacturer != null) {
            $manufacturer = Manufacturer::find($request->manufacturer);
            if ($manufacturer->token && $manufacturer->software) {
                $product->manufacturer_type = $request->manufacturer_type;
            } else {
                $product->manufacturer_type = $manufacturer->token ? 'token' : ($manufacturer->software ? 'software' : null);
                $product->save();
            }
            $manufacturer->products()->save($product);
        }


        #region brand
        ProductsBrand::query()->where('product_id', $product->id)->forceDelete();
        if ($request->brand != null) {
            foreach ($request->brand as $key => $brand) {
                if ($request->models[$key] != null) {
                    $model = $request->models[$key];
                    $model = BrandModel::find($model)->id;
                    $data = BrandModelYear::query()->where('brand_model_id', $model)->get();
                    if ($request->years_from[$key] != null) {
                        $year = $data->where('id', $request->years_from[$key])->first();
                        $year_to = $data->where('id', $request->years_to[$key])->first();
                        if (!empty($year_to)) {
                            for ($i = $year->year; $i <= $year_to->year; $i++) {
                                $year = $data->where('year', strval($i))->first();
                                if ($year != null) {
                                    ProductsBrand::firstOrCreate([
                                        'brand_id' => $brand,
                                        'brand_model_id' => $model,
                                        'brand_model_year_id' => $year->id,
                                        'product_id' => $product->id
                                    ]);
                                }
                            }
                        }

                    } else {
                        foreach ($data->groupBy('brand_model_id') as $year) {

                            ProductsBrand::firstOrCreate([
                                'brand_id' => $brand,
                                'brand_model_id' => $model,
                                'brand_model_year_id' => null,
                                'product_id' => $product->id
                            ]);
                        }

                    }
                } else {
                    ProductsBrand::firstOrCreate([
                        'brand_id' => $brand,
                        'product_id' => $product->id
                    ]);

                }
            }
        }

        #endregion

        #region packages
        if ($request->has('packages_price') && !empty($request->packages_price)) {
            foreach ($request->packages_price as $key => $item) {
                $productsPackages = new ProductsPackages();
                $productsPackages->from = $request->from[$key];
                $productsPackages->to = $request->to[$key];
                $productsPackages->price = $item;
                $productsPackages->product_id = $product->id;
                $productsPackages->save();
            }
        }
        #endregion

        #region serial numbers
        if ($request->has('serial_number') && !empty($request->serial_number)) {
            foreach ($request->serial_number as $item) {
                $product_serial_number = new ProductsSerialNumber();
                $product_serial_number->product_id = $product->id;
                $product_serial_number->serial_number = $item;
                $product_serial_number->save();
            }
        }
        #endregion

        return redirect()->route('backend.products.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }

    #endregion

    #region destroy
    public function destroy($id)
    {
        if (!permission_can('delete product', 'admin')) {
            return abort(403);
        }

        if (Product::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));
    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete product', 'admin')) {
            return abort(403);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {
            Product::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region out of stock

    public function show_out_of_stock()
    {
        $filters = [];
        $filters = ['products'];
        $datatable_route = route('backend.products.out_of_stock_datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['sku'] = 'products.sku';
        $datatable_columns['cnt'] = 'cnt';
        $datatable_columns['last_request'] = 'out_of_stocks.updated_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);
        $products = Product::query()->where('quantity', 0)->whereHas('outOfStock')->pluck('sku', 'id');

        return view('backend.product.out_of_stock', compact('datatable_script', 'products'));
    }

    public function out_of_stock_datatable(Request $request)
    {
//        $model = Product::query()->where('quantity', 0)->whereHas('outOfStock');
        $model = OutOfStock::query()
            ->select('out_of_stocks.*', DB::raw('max(out_of_stocks.updated_at) as last_request'), DB::raw('count(product_id) as cnt'), 'products.sku', 'products.title', 'products.slug')
            ->join('products', 'products.id', 'out_of_stocks.product_id')
            ->groupBy('out_of_stocks.product_id');
        if ($request->products != null) {
            $model->where('id', $request->products);
        }
        return datatables()->make($model)
            ->filterColumn('cnt', function ($query, $keyword) {
//                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('cnt', function ($query, $keyword) {
//                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->editColumn('id', function ($q) {
                return $q->id;
            })
            ->addColumn('request_count', function ($q) {
                return $q->count;
            })
            ->editColumn('last_request', function ($q) {
                return $q->last_request;
            })
            ->addColumn('actions', function ($q) {
                return $this->btn(route('backend.products.product_requests', ['product_id' => $q->product_id]), '', 'las la-eye', 'btn-warning btn-show btn-icon');

            })
            ->editColumn('sku', function ($q) {
                return '<a href="' . route('backend.products.edit', ['product' => $q->product_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->sku . '</span>
                                                    </a>';
            })
            ->rawColumns(['actions', 'last_request', 'request_count', 'sku'])
            ->toJson();
    }

    public function product_requests($id)
    {
        $filters = [];

        $datatable_route = route('backend.products.products_requests_datatable', ['product_id' => $id]);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'out_of_stocks.id';
        $datatable_columns['user_name'] = 'users.name';
        $datatable_columns['user_email'] = 'users.email';
        $datatable_columns['user_phone'] = 'users.phone';
        $datatable_columns['quantity'] = 'quantity';
        $datatable_columns['updated_at'] = 'updated_at';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);
        $title = Product::find($id)->title;

        return view('backend.product.product_request', compact('datatable_script', 'title'));
    }

    public function products_requests_datatable($product_id)
    {
//        $product = Product::find($product_id);

//        $model = $product->outOfStock();

        $model = OutOfStock::query()
            ->select('out_of_stocks.*',
                'products.sku',
                'products.title',
                'products.slug',
                'users.name as user_name',
                'users.phone as user_phone',
                'users.email as user_email'
            )
            ->join('products', 'products.id', 'out_of_stocks.product_id')
            ->join('users', 'users.id', 'out_of_stocks.user_id')
            ->where('out_of_stocks.product_id', $product_id);


        return datatables()->make($model)
            ->editColumn('id', function ($q) {
                return $q->id;
            })
            ->editColumn('user_email', function ($q) {
                return $q->user_email;
            })
            ->editColumn('user_name', function ($q) {
                return '<a href="' . route('backend.users.show', ['user' => $q->user_id]) . '"
                                                       class="symbol symbol-50px  ">
                                                       <span class="badge badge-light-primary badge-lg">
                                                       ' . $q->user_name . '</span>
                                                    </a>';
            })
            ->editColumn('user_phone', function ($q) {
                return $q->user_phone;
            })
            ->editColumn('quantity', function ($q) {
                return $q->quantity;
            })
            ->editColumn('updated_at', function ($q) {
                return $q->updated_at;
            })
            ->rawColumns(['user_name', 'user_email', 'user_phone', 'quantity', 'updated_at'])
            ->toJson();

    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status product', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $product = Product::find($id);
        if ($product->status == 1) {
            $product->status = 0;
        } else {
            $product->status = 1;
        }
        if ($product->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }

    function change_value_column(ChangeColumnStatusRequest $request)
    {

        $id = $request->id;
        $product = Product::find($id);
        if ($product[$request->column] == 1) {
            $product[$request->column] = 0;
        } else {
            $product[$request->column] = 1;
        }
        if ($product->save()) {
            return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }

    #endregion

    #region check on slug
    function check_slug(CheckSlugRequest $request)
    {
        $slug = $request->slug;
        $check_ajax = Product::withTrashed()->where('slug', $slug);
        if ($request->has('id')) {
            $check_ajax = $check_ajax->whereNot('id', $request->id);
        }
        if ($check_ajax->count() == 0) {
            return response()->data(['message' => trans('backend.product.check_slug.you_can_use_this_slug')]);
        }
        return response()->error(trans('backend.product.check_slug.you_can_not_use_this_slug'), []);


    }
    #endregion

    #region check on sku
    function check_sku(CheckSkuRequest $request)
    {
        $slug = $request->sku;
        $check_ajax = Product::withTrashed()->where('sku', $slug);
        if ($request->has('id')) {
            $check_ajax = $check_ajax->where('id', $request->id);
        }
        if ($check_ajax->count() == 0) {
            return response()->data(['message' => trans('backend.product.check_sku.you_can_use_this_sku')]);
        }
        return response()->error(trans('backend.product.check_sku.you_can_not_use_this_sku'), []);


    }
    #endregion

    #region get brands
    function brands(Request $request)
    {


        if ($request->has('model') && $request->model != -1 && !empty($request->model)) {
            $years = BrandModelYear::query()->where('status', 1);
            $years = $years->where('brand_model_id', $request->model);
            $years = $years->pluck('year', 'id');
            return response()->data(['years' => $years]);
        } else {
            $models = BrandModel::query()->where('status', 1);
            $models = $models->where('brand_id', $request->brand);
            if ($request->has('without') && !empty($request->without)) {
                $models = $models->whereNotIn('id', $request->without);
            }
            $models = $models->pluck('model', 'id');

            return response()->data(['models' => $models]);

        }

    }
    #endregion

    #region get product select2
    function getProduct(GetProducts $request)
    {

        $key = $request->key;
        $products = Product::query()->where('status', 1);
        if ($request->has('key') && !empty($key)) {
            $products = $products->where('sku', 'like', "%" . $key . "%");

        }
        $products = $products->get();
        $data = [];
        foreach ($products as $item) {
            $data[] = ['id' => $item->id, 'title' => $item->title];
        }
        return response()->data(['products' => $data]);
    }
    #endregion

    #region import
    function import_from_excel()
    {
        if (!permission_can('import product', 'admin')) {
            return abort(403);
        }
        return view('backend.product.import');
    }

    function upload_excel(ImportExcelFileRequest $request)
    {
        if (!permission_can('import product', 'admin')) {
            return abort(403);
        }
        $data = \Excel::toArray(new ProductsImport(), $request->file);
        $total_serial_number_is_duplicated = 0;
        $total_serial_number_is_added = 0;
        $total_not_found_product = 0;
        $sku_not_found = [];
        $new_sku = [];
        foreach ($data[0] as $key => $item) {
            if ($key != 0) {
                $product = Product::query()->where('sku', $item[0])->first();

                if (!empty($product)) {
                    $product->quantity = $item[2];
                    $product->save();
                    if (!empty($item[3])) {
                        $series = explode(",", $item[3]);
                        foreach ($series as $serial_number) {
                            if ($serial_number != "") {
                                $check_serile_number = ProductsSerialNumber::query()
                                    ->where('product_id', $product->id)
                                    ->where('serial_number', $serial_number)->count();
                                if ($check_serile_number == 0) {
                                    $new_serile_number = new ProductsSerialNumber();
                                    $new_serile_number->product_id = $product->id;
                                    $new_serile_number->serial_number = $serial_number;
                                    $new_serile_number->save();
                                    $total_serial_number_is_added++;
                                } else {
                                    $total_serial_number_is_duplicated++;
                                }
                            }

                        }
                    }

                } else {
                    $check_product = Product::withTrashed()->where('slug', $item[7])->first();
                    if (empty($check_product)) {
                        $category = Category::where('name', 'like', '%' . $item[8] . '%')->first() ?? Category::first();
                        $product = new Product([
                            'sku' => empty($item[0]) ? "TL" . time() . time() : $item[0],
                            'slug' => empty($item[7]) ? 'SLUG' . time() . md5(time() . rand(1, 10000)) : $item[7],
                            'category_id' => $category->id,
                            'image' => get_setting('default_images'),
                            'price' => empty($item[9]) ? 0 : $item[9],
                            'status' => 0,
                            'quantity' => empty($item[2]) ? 0 : $item[2],

                            'title' => empty($item[4]) ? ['en' => 'title'] : ['en' => $item[4]],
                            'short_title' => empty($item[5]) ? ['en' => 'short title'] : ['en' => $item[5]],
                            'description' => empty($item[1]) ? ['en' => 'description'] : ['en' => $item[1]],
                            'summary_name' => empty($item[6]) ? ['en' => 'summary title'] : ['en' => $item[6]],
                            'meta_title' => empty($item[10]) ? ['en' => 'meta title'] : ['en' => $item[10]],
                            'meta_description' => empty($item[11]) ? ['en' => 'meta description'] : ['en' => $item[11]],
                        ]);
                        $product->save();
                        if (empty($item[0])) {
                            $product->sku = 'TL' . $product->id;
                            $product->slug = 'TL' . $product->id . '_' . md5(time() . rand(1, 100));
                            $product->save();
                        }
                        $new_sku[] = $item[0];

                        if (!empty($item[3])) {
                            $series = explode(",", $item[3]);
                            foreach ($series as $serial_number) {
                                if ($serial_number != "") {
                                    $check_serile_number = ProductsSerialNumber::query()
                                        ->where('product_id', $product->id)
                                        ->where('serial_number', $serial_number)->count();
                                    if ($check_serile_number == 0) {
                                        $new_serile_number = new ProductsSerialNumber();
                                        $new_serile_number->product_id = $product->id;
                                        $new_serile_number->serial_number = $serial_number;
                                        $new_serile_number->save();
                                        $total_serial_number_is_added++;
                                    } else {
                                        $total_serial_number_is_duplicated++;
                                    }
                                }

                            }
                        }
                        $total_not_found_product++;
                        $sku_not_found[] = $item[0];
                    }
                }
            }
        }
        return response()->data([
            "total_serial_number_is_duplicated" => $total_serial_number_is_duplicated,
            "total_serial_number_is_added" => $total_serial_number_is_added,
            "total_not_found_product" => $total_not_found_product,
            "sku_not_found" => $sku_not_found,
            'added_products' => implode(', ', $new_sku)

        ]);
    }

    function upload_new_products(ImportExcelFileRequest $request)
    {
        $data = \Excel::import(new NewProductsImport, $request->file);

//        if (!empty($item[3])) {
//            $series = explode(",", $item[3]);
//            foreach ($series as $serial_number) {
//                if ($serial_number != "") {
//                    $check_serile_number = ProductsSerialNumber::query()
//                        ->where('product_id', $product->id)
//                        ->where('serial_number', $serial_number)->count();
//                    if ($check_serile_number == 0) {
//                        $new_serile_number = new ProductsSerialNumber();
//                        $new_serile_number->product_id = $product->id;
//                        $new_serile_number->serial_number = $serial_number;
//                        $new_serile_number->save();
//                        $total_serial_number_is_added++;
//                    } else {
//                        $total_serial_number_is_duplicated++;
//                    }
//                }
//
//            }
//        }
        return response()->data([$data]);
//        redirect()->route('backend.products.import')->with('success', trans('backend.global.success_message.created_successfully')) ;
    }



    #endregion

    #region series number
    function series_number($id)
    {
        if (!permission_can('show product serial number', 'admin')) {
            return abort(403);
        }
        $product = Product::query()->where('id', $id)->first();
        $datatable_route = route('backend.products.series.number.datatable', ['id' => $id]);
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['serial_number'] = 'serial_number';
        $datatable_columns['order'] = 'order';
        $datatable_columns['user'] = 'user';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);

        return view('backend.product.series_number', compact('product', 'datatable_script'));
    }

    function series_number_datatable(Request $request, $id)
    {

        if (!permission_can('show product serial number', 'admin')) {
            return abort(403);
        }
        $model = ProductsSerialNumber::query()->where('product_id', $id);
        return datatables()->make($model)
            ->addColumn('serial_number', function ($q) {
                return $q->serial_number;
            })
            ->addColumn('order', function ($q) {
                $order = null;
                if (!empty($q->order_id)) {
                    $order = Order::query()->where('id', $q->order_id)->first();
                    if (!empty($order))
                        return "<span class='badge badge-light-primary'>#" . $order->uuid . "</span>";
                }
                return "<span class='badge badge-light-warning'>" . trans('backend.global.not_found') . "</span>";
            })
            ->addColumn('user', function ($q) {
                $order = null;
                if (!empty($q->order_id)) {
                    $order = Order::query()->where('id', $q->order_id)->first();
                    if (!empty($order)) {
                        $user = $order->user;
                        if (!empty($user)) {

                            return "<span class='badge badge-light-info'> " . $user->uuid . " | " . $user->name . "</span>";
                        }

                    }
                }
                return "<span class='badge badge-light-warning'>" . trans('backend.global.not_found') . "</span>";

            })
            ->rawColumns(['serial_number', 'user', 'order'])
            ->toJson();

    }
    #endregion

    #region get category type

    public function get_category_type(Request $request)
    {
        $category_type = Category::find($request->category)?->type;

        return response()->data(['category_type' => $category_type]);
    }
    #endregion

    #region attributes
    function create_new_attribute(CreateNewRequest $request)
    {
        $languages = get_languages();
        $attribute = new Attribute();
        foreach (get_languages() as $item) {
            $name  [$item->code] = $request->has("new_attr_" . $item->code) ? $request->get("new_attr_" . $item->code) : $request->get("new_attr_en");
            $image [$item->code] = $request->has("media_" . $item->code) ? $request->get("media_" . $item->code) : "";
        }
        $attribute->name = $name;
        $attribute->image = $image;
        $attribute->status = 1;
        $attribute->slug = preg_replace('/\s+/', '_', $request->get("new_attr_en")) . '_' . time();
        $attribute->save();
        $all_attribute = Attribute::query()->where('status', 1)->with('sub_attributes')->get();
        return response()->data(['attributes' => $all_attribute]);
    }

    function create_new_sub_attribute(Request $request)
    {
        $id = $request->id;
        $view = view('backend.product.create.attribute.model_create_sub_attribute', compact('id'))->render();
        return response()->data(['request' => $request, 'view' => $view]);
    }

    function store_new_sub_attribute(StoreNewSubAttribute $request)
    {
        $languages = get_languages();
        $sub_attribute = new SubAttribute();
        $values = [];
        $image = [];
        foreach (get_languages() as $item) {
            $values  [$item->code] = $request->has("sub_attr_" . $item->code) ? $request->get("sub_attr_" . $item->code) : $request->get("sub_attr__en");

        }
        $sub_attribute->attribute_id = $request->attr_id;
        $sub_attribute->value = $values;
        $sub_attribute->image = $request->has("image") ? $request->get("image") : "";;
        $sub_attribute->status = 1;
        $sub_attribute->slug = preg_replace('/\s+/', '_', $request->get("sub_attr__en")) . '_' . time();
        $sub_attribute->save();
        $all_attribute = Attribute::query()->where('status', 1)->with('sub_attributes')->get();
        return response()->data(['attributes' => $all_attribute]);

    }

    #endregion

    #region get_quantity

    public function get_quantity(Request $request)
    {
        $validate = $request->validate(['product_sku' => 'required|exists:products,sku']);

        $product_quantity = Product::where('sku', $request->product_sku)->pluck('quantity');

        return response()->data(['quantity' => $product_quantity]);
    }
    #endregion

    #region check_manufacturer_type
    public function check_manufacturer_type(Request $request)
    {
        $validate = $request->validate(['manufacturer' => 'required|exists:manufacturers,id,deleted_at,NULL']);

        $manufacturer = Manufacturer::findOrFail($request->manufacturer);
        return response()->data(['token' => $manufacturer->token, 'software' => $manufacturer->software]);

    }
    #endregion

}
