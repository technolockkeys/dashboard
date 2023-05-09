<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrdersProducts;
use App\Models\Product;
use App\Models\ProductStockStatus;
use App\Models\User;
use App\Traits\DatatableTrait;
use DB;
use Google\Service\Dfareporting\Resource\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\AnalyticsClientFactory;
use Spatie\Analytics\Period;

class StatisticsController extends Controller
{
    use DatatableTrait;

    public function index()
    {
        $reports = [
            route('backend.statistics.total-sales') => trans('backend.statistic.total_sales'),
            route('backend.statistics.order_count') => trans('backend.statistic.order_count'),
            route('backend.statistics.shipping') => trans('backend.statistic.shipping'),
            route('backend.statistics.coupons') => trans('backend.statistic.coupons'),
            route('backend.statistics.users') => trans('backend.statistic.users'),
            route('backend.statistics.user_countries') => trans('backend.statistic.user_countries'),
            route('backend.statistics.traffic_source') => trans('backend.statistic.traffic_source'),
            route('backend.statistics.device_category') => trans('backend.statistic.device_category'),
            route('backend.statistics.operating_system') => trans('backend.statistic.operating_system'),
            route('backend.statistics.website_visits') => trans('backend.statistic.website_visits'),
            route('backend.statistics.most-visited-pages') => trans('backend.statistic.most_visited_pages'),
            route('backend.statistics.top-selling-products') => trans('backend.menu.products'),
            route('backend.statistics.top-selling-categories') => trans('backend.statistic.top_selling_categories'),
            route('backend.statistics.users_orders') => trans('backend.statistic.users_orders'),
            route('backend.statistics.google_analytics') => trans('backend.statistic.google_analytics'),
            route('backend.statistics.net_revenue') => trans('backend.statistic.net_revenue'),
        ];
        return view('backend.statistics.index', compact('reports',));
    }

    public function total_sales(Request $request)
    {

        $sales = Order::query()
            ->whereIn('type', [Order::$order, Order::$pin_code])
            ->where('status', [Order::$processing, Order::$completed])
            ->where('payment_status', 'paid');
        if ($request->start_date != null) {
            $sales = $sales->whereBetween('orders.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }
        $sales = $sales->select(
            DB::raw('sum(total) as sum'),
            DB::raw('avg(total) as average'),
            DB::raw("DATE_FORMAT(created_at,'%d-%m-%Y') as days")
        )->groupBy('days')->get();
        $total_revenue = $sales->sum('sum');
        $avg_daily_revenue = $sales->average('sum');

        $view = view('backend.statistics.total_sales', compact('sales', 'total_revenue', 'avg_daily_revenue'))->render();
        return response()->data(['view' => $view]);
    }

    public function order_count(Request $request)
    {
        $sales = Order::query();
        if ($request->start_date != null) {
            $sales = $sales->whereBetween('orders.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }
        $sales = $sales->select(
            DB::raw('count(case status WHEN "completed" THEN 1 ELSE NULL END) as completed'),
            DB::raw('count(case status WHEN "failed" THEN 1 ELSE NULL END) as failed'),
            DB::raw('count(case status WHEN "on_hold" THEN 1 ELSE NULL END) as on_hold'),
            DB::raw('count(case status WHEN "pending_payment" THEN 1 ELSE NULL END) as pending_payment'),
            DB::raw('count(case status WHEN "processing" THEN 1 ELSE NULL END) as processing'),
            DB::raw('count(case status WHEN "refunded" THEN 1 ELSE NULL END) as refunded'),
            DB::raw("DATE_FORMAT(updated_at,'%d-%m-%Y') as days")
        )->groupBy('days')->get();

        $view = view('backend.statistics.sales_count', compact('sales'))->render();
        return response()->data(['view' => $view]);
    }

    public function website_visits(Request $request)
    {
        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);
        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->fetchTotalVisitorsAndPageViews($period);

        $view = view('backend.statistics.visits', compact('analyticsData'))->render();
        return response()->data(['view' => $view]);

    }

    public function pages_view(Request $request)
    {
        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);
        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->fetchMostVisitedPages($period);

        $view = view('backend.statistics.most_visited_pages', compact('analyticsData'))->render();
        return response()->data(['view' => $view]);
    }

    public function top_selling_products(Request $request)
    {
        $products = Product::all();
        $routes = [
            route('backend.statistics.show_products_chart') => trans('backend.statistic.sales_chart'),
            route('backend.statistics.show_stock_chart') => trans('backend.statistic.stock_chart'),
        ];
        $view = view('backend.statistics.select_product', compact('products', 'routes'))->render();
        return response()->data(['view' => $view]);
    }

    public function get_product_chart(Request $request)
    {
        $products_quantity = OrdersProducts::query()
            ->join('orders', 'orders.id', 'orders_products.order_id')
            ->whereIn('orders.status' , [Order::$completed , Order::$processing])
            ->where('orders.payment_status' , Order::$payment_status_paid)
            ->where('orders_products.product_id', $request->product);

        if ($request->start_date != null) {
            $products_quantity = $products_quantity->whereBetween('orders_products.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }
        $products_quantity = $products_quantity
            ->join('products', 'orders_products.product_id', 'products.id')
            ->select(
                DB::raw('sum(orders_products.quantity) as quantity'), 'orders_products.product_id',
                DB::raw("DATE_FORMAT(orders_products.created_at,'%Y-%m-%d') as days"),
                )
            ->whereNull('products.deleted_at')
            ->groupBy('days')
            ->with('product')
            ->limit(10)
            ->get();

        $view = view('backend.statistics.most_sold_products', compact('products_quantity'))
            ->render();
        return response()->data(['view' => $view]);
    }

    public function stock_status(Request $request)
    {

        $base = ProductStockStatus::query()
            ->where('product_id', $request->product)->select(
                DB::raw('max(id) as max_id'),
                DB::raw("DATE_FORMAT(created_at, ' %Y -%m -%d') AS days"),
                'quantity')->groupBy('days');

        $products_quantity = DB::table('product_stock_statuses')
            ->select(
                'id',
                DB::raw("product_stock_statuses.created_at  AS days"),
                'product_stock_statuses.quantity as quantity')
            ->joinSub($base, 'base', function ($join) {
                $join->on('product_stock_statuses.id', 'base.max_id');
            })->get();

        $view = view('backend.statistics.stock_chart', compact('products_quantity'))
            ->render();
        return response()->data(['view' => $view]);
    }

    public function top_selling_categories(Request $request)
    {
        $categories = Category::all();

        $view = view('backend.statistics.select_category', compact('categories'))->render();
        return response()->data(['view' => $view]);
    }

    public function show_categories_chart(Request $request)
    {
        $products_quantity = OrdersProducts::query();

        if ($request->start_date != null) {
            $products_quantity = $products_quantity
                ->whereBetween('orders_products.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }

        $categories = $products_quantity
            ->select(
                DB::raw('COUNT(orders_products.quantity) as sold_quantity'), 'categories.name as name',
                DB::raw('categories.id'), DB::raw("DATE_FORMAT(orders_products.created_at,'%Y-%m-%d') as days"))
            ->join('products', 'orders_products.product_id', 'products.id')
            ->join('categories', 'categories.id', 'products.category_id')
            ->where('categories.id', $request->category)
            ->with(['product.category', 'product'])
            ->orderBy('days')
            ->groupBy(['days'])
            ->get();

        $view = view('backend.statistics.most_sold_categories', compact('categories'))
            ->render();

        return response()->data(['view' => $view]);

    }

    public function shipping(Request $request)
    {
        $orders = Order::query();
        if ($request->start_date != null) {
            $orders = $orders->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }

        $shipping = $orders->whereIn('status', [Order::$completed, Order::$refunded])->select(
            DB::raw('sum(case shipping_method WHEN "dhl" THEN shipping ELSE 0 END) as dhl'),
            DB::raw('sum(case shipping_method WHEN "fedex" THEN shipping ELSE 0 END) as fedex'),
            DB::raw('sum(case shipping_method WHEN "ups" THEN shipping ELSE 0 END) as ups'),
            DB::raw('sum(case shipping_method WHEN "aramex" THEN shipping ELSE 0 END) as aramex'),
            DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as days")
        )->orderBy('days')->groupBy('days')->get();
        $total_dhl = $shipping->sum('dhl');

        $total_fedex = $shipping->sum('fedex');

        $total_ups = $shipping->sum('ups');

        $total_aramex = $shipping->sum('aramex');

        $view = view('backend.statistics.shipping', compact('shipping', 'total_dhl', 'total_fedex', 'total_ups', 'total_aramex'))->render();
        return response()->data(['view' => $view]);
    }

    public function coupons(Request $request)
    {
        $orders = Order::query();
        if ($request->start_date != null) {
            $orders = $orders->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }

        $coupons = $orders->select(
            DB::raw('sum(coupon_value) as discount'),
            DB::raw("DATE_FORMAT(created_at,'%d-%m-%Y') as days")
        )->groupBy('days')->get();

        $view = view('backend.statistics.coupons', compact('coupons'))->render();
        return response()->data(['view' => $view]);

    }

    public function users(Request $request)
    {

        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);
        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->performQuery($period, 'ga:', [
            'metrics' => 'ga:users,ga:newUsers',
            'dimensions' => 'ga:month,ga:day',
        ]);

        $data_user = ['users', 'new_users'];
        $result = [];
        foreach ($analyticsData['rows'] as $item) {
            foreach ($item as $key => $item2) {
                if ($key > 1) {
                    $result[$data_user[$key - 2]] [] = $item2;
                } else if ($key == 1) {
                    $result['date'] [] = $item[0] . "-" . $item[1];
                }
            }
        }
        $view = view('backend.statistics.google_console', compact('result'))->render();
        return response()->data(['view' => $view]);
    }

    public function users_orders(Request $request)
    {

//        $filters = [];
        $filters[] = 'start_date';
        $filters[] = 'end_date';

        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['orders_count'] = 'orders_count';
        $datatable_columns['purchase_value'] = 'orders.total';
        $datatable_columns['avg_purchase_value'] = 'orders.total';

        $datatable_route = route('backend.statistics.users_orders_datatable', $request->all());

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, $filters);
        $view = view('backend.statistics.user_orders', compact('datatable_script'))->render();
        return response()->data(['view' => $view]);

    }

    public function users_orders_datatable(Request $request)
    {
        $users = User::query()
            ->where('orders.type',Order::$order)
            ->whereIn('orders.status',[Order::$processing , Order::$completed])
            ->where('orders.payment_status' , Order::$payment_status_paid)
            ->leftJoin('orders', 'users.id', 'orders.user_id');

        if ($request->start_date != null) {
            $users = $users->whereBetween('orders.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }

        $users->select('users.name', 'users.id', 'orders.created_at',
            DB::raw('count(orders.id) as orders_count'),
            DB::raw('sum(orders.total) as purchase_value'),
            DB::raw('avg(orders.total) as avg_purchase_value'),
            )->groupBy('users.id');

        return datatables()->make($users)
            ->editColumn('orders_count', function ($q) {
                return '<span class="badge badge-light-primary badge-lg fw-bold  ">' . $q->orders_count . '</span>';
            })
            ->editColumn('purchase_value', function ($q) {
                return currency($q->purchase_value);
            })
            ->editColumn('avg_purchase_value', function ($q) {
                return currency($q->avg_purchase_value);
            })
            ->rawColumns(['orders_count', 'purchase_value', 'avg_purchase_value'])
            ->toJson();
    }

    public function user_countries(Request $request)
    {

        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);
        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->performQuery($period, 'ga:', [
            'metrics' => 'ga:users,ga:newUsers,ga:sessions,ga:pageviews,ga:avgSessionDuration,ga:bounceRate',
            'dimensions' => 'ga:country',
        ]);


        $data_user = ['countries', 'users', 'new_users', 'sessions', 'pageviews', 'avgSessionDuration', 'bounceRate'];
        $data = [];
        foreach ($analyticsData['rows'] ?? [] as $item) {
            foreach ($item ?? [] as $key => $item2) {
                $data[$data_user[$key]] [] = $item2;

            }
        }
        $view = view('backend.statistics.countries', compact('data'))->render();
        return response()->data(['view' => $view]);

    }

    public function traffic_source(Request $request)
    {
        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);
        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->performQuery($period, 'ga:', [
            'metrics' => 'ga:users',
            'dimensions' => 'ga:source'
        ]);


        $results = [];
        foreach ($analyticsData['rows'] ?? [] as $item) {
            $results[$item[0]] = $item[1];
        }
        $view = view('backend.statistics.traffic_source', compact('results'))->render();

        return response()->data(['view' => $view]);
    }

    public function device_category(Request $request)
    {
        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);
        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->performQuery($period, 'ga:', [
            'metrics' => 'ga:users',
            'dimensions' => 'ga:deviceCategory'
        ]);

        $results = [];
        foreach ($analyticsData['rows'] ?? [] as $item) {
            $results[$item[0]] = $item[1];
        }
        $view = view('backend.statistics.device_type', compact('results'))->render();

        return response()->data(['view' => $view]);

    }

    public function operating_system(Request $request)
    {
        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);
        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->performQuery($period, 'ga:', [
            'metrics' => 'ga:users',
            'dimensions' => 'ga:operatingSystem'
        ]);

        $results = [];
        foreach ($analyticsData['rows'] ?? [] as $item) {
            $results[$item[0]] = $item[1];
        }
        $view = view('backend.statistics.operating_system', compact('results'))->render();

        return response()->data(['view' => $view]);
    }

    public function googleAnalytics(Request $request)
    {
        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $period = Period::days(7);

        if ($request->start_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $period = Period::create($start, $end);
        }
        $analyticsData = $analytic->performQuery($period, 'ga:', [
            'metrics' => 'ga:users,ga:newUsers,ga:sessions,ga:sessionsPerUser,ga:pageviews,ga:pageviewsPerSession,ga:avgSessionDuration,ga:bounceRate',
        ]);
        $statistics = [];
        if (!empty($analyticsData['rows'])) {

            $statistics = [
                [
                    'svg' => asset('backend/media/icons/duotune/communication/com013.svg'),
                    'name' => __('backend.statistic.users'),
                    'number' => $analyticsData['rows'][0][0],
                    'color' => ''
                ], [
                    'svg' => asset('backend/media/icons/duotune/communication/com013.svg'),
                    'name' => __('backend.statistic.new_users'),
                    'number' => $analyticsData['rows'][0][1],
                    'color' => ''
                ], [
                    'svg' => asset('backend/media/icons/duotune/electronics/elc001.svg'),
                    'name' => __('backend.statistic.session'),
                    'number' => $analyticsData['rows'][0][2],
                    'color' => 'success'
                ], [
                    'svg' => asset('backend/media/icons/duotune/electronics/elc003.svg'),
                    'name' => __('backend.statistic.session_per_user'),
                    'number' => number_format($analyticsData['rows'][0][3], 2),
                    'color' => 'secondary'
                ], [
                    'svg' => asset('backend/media/icons/duotune/coding/cod005.svg'),
                    'name' => __('backend.statistic.page_views'),
                    'number' => number_format($analyticsData['rows'][0][4], 2, '.'),
                    'color' => 'secondary'
                ], [
                    'svg' => asset('backend/media/icons/duotune/arrows/arr011.svg'),
                    'name' => __('backend.statistic.page_views_per_session'),
                    'number' => number_format($analyticsData['rows'][0][5], 2, '.'),
                    'color' => 'danger'
                ], [
                    'svg' => asset('backend/media/icons/duotune/general/gen013.svg'),
                    'name' => __('backend.statistic.avg_session_duration'),
                    'number' => number_format(($analyticsData['rows'][0][6]), 2, '.', '') . ' sec',
                    'color' => 'danger'
                ], [
                    'svg' => asset('backend/media/icons/duotune/arrows/arr011.svg'),
                    'name' => __('backend.statistic.bounce_rate'),
                    'number' => number_format($analyticsData['rows'][0][7], 2, '.') . '%',
                    'color' => 'danger'
                ],];

        }

        $view = view('backend.statistics.google_analytics', compact('statistics'))->render();

        return response()->data(['view' => $view]);
    }

    public function net_revenue(Request $request)
    {
        $sales = Order::query()->where('payment_status', 'paid')
            ->where('status', Order::$completed);
        if ($request->start_date != null) {
            $sales = $sales->whereBetween('orders.created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }
        $sales = $sales->select(
            DB::raw('sum( total - shipping - COALESCE(seller_commission,0)) as sum'),
            DB::raw('avg(total - shipping - COALESCE(seller_commission,0) ) as average'),
            DB::raw('count(*) as count'),
            DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as days")
        )->groupBy('days')->get();

        $total_revenue = $sales->sum('sum');
        $avg_daily_revenue = $sales->average('sum');
        $view = view('backend.statistics.net_revenue', compact('sales', 'total_revenue', 'avg_daily_revenue'))->render();
        return response()->data(['view' => $view]);
    }


}
