<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Download;
use App\Models\Language;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Page;
use App\Models\Product;
use App\Models\SellerEarning;
use App\Models\User;
use App\Models\UserWallet;
use App\Traits\DatatableTrait;
use App\Traits\OrderTrait;
use App\Traits\PaypalTrait;
use App\Traits\StripeTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\AnalyticsClientFactory;
use Spatie\Analytics\Period;


class DashboardController extends Controller
{
    use PaypalTrait;
    use StripeTrait;
    use DatatableTrait;
    use OrderTrait;

    function index()
    {

        $admin_id = Auth::guard('admin')->id();
        $admin = Admin::find($admin_id);
        $carbon = new Carbon();

        #region statistics
        $statistics = [];
        //users
        $statistics[] = [
            'class' => 'bg-body',
            'route' => route('backend.users.index'),
            'svg' => asset('backend/media/icons/duotune/layouts/lay008.svg'),
            'number' => User::count(),
            'name' => __('backend.menu.users'),
            'text-color' => 'text-dark'
        ];
        //products
        $statistics[] = [
            'class' => 'bg-info',
            'route' => route('backend.products.index'),
            'svg' => asset('backend/media/icons/duotune/ecommerce/ecm001.svg'),
            'number' => Product::count(),
            'name' => __('backend.menu.products'),
            'text-color' => 'text-white'
        ];
        //pages
        $statistics[] = [
            'class' => 'bg-success',
            'route' => route('backend.pages.index'),
            'svg' => asset('backend/media/icons/duotune/coding/cod002.svg'),
            'number' => Page::count(),
            'name' => __('backend.menu.pages'),
            'text-color' => 'text-white'
        ];
        //downloads
        $statistics[] = [
            'class' => 'bg-primary',
            'route' => route('backend.downloads.index'),
            'svg' => asset('backend/media/icons/duotune/files/fil021.svg'),
            'number' => Download::count(),
            'name' => __('backend.menu.downloads'),
            'text-color' => 'text-white'
        ];
        //brands
        $statistics[] = [
            'class' => 'bg-danger',
            'route' => route('backend.brands.index'),
            'svg' => asset('backend/media/icons/duotune/general/gen026.svg'),
            'number' => Brand::count(),
            'name' => __('backend.menu.brands'),
            'text-color' => 'text-white'
        ];
        //languages
        $statistics[] = [
            'class' => 'bg-info',
            'route' => route('backend.languages.index'),
            'svg' => asset('backend/media/icons/duotune/social/soc009.svg'),
            'number' => Language::count(),
            'name' => __('backend.menu.languages'),
            'text-color' => 'text-white'
        ];
        #endregion

        #region analytics
        $analyticsConfig = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analytic = new Analytics($client, $analyticsConfig['view_id']);
        $analyticsData = $analytic->fetchTotalVisitorsAndPageViews(Period::days(7));

        $total_order = Order::query()->whereIn('type', [Order::$order, Order::$pin_code])->whereIn('status', [Order::$completed, Order::$processing])->where('payment_status', Order::$payment_status_paid)->sum('total');
        $count_order = Order::query()->whereIn('type', [Order::$order, Order::$pin_code])->whereIn('status', [Order::$completed, Order::$processing])->where('payment_status', Order::$payment_status_paid)->count();

        $total_order_completed = Order::query()->where('type', Order::$order)->where('status', Order::$completed)->sum('total');
        $count_order_completed = Order::query()->where('type', Order::$order)->where('status', Order::$completed)->count();

        $total_order_on_hold = Order::query()->where('type', Order::$order)->where('status', Order::$on_hold)->sum('total');
        $count_order_on_hold = Order::query()->where('type', Order::$order)->where('status', Order::$on_hold)->count();

        $total_order_pending_payment = Order::query()->where('type', Order::$order)->where('status', Order::$pending_payment)->sum('total');
        $count_order_pending_payment = Order::query()->where('type', Order::$order)->where('status', Order::$pending_payment)->count();

        $total_order_processing = Order::query()->where('type', Order::$order)->where('status', Order::$processing)->sum('total');
        $count_order_processing = Order::query()->where('type', Order::$order)->where('status', Order::$processing)->count();
        #endregion

        #region sellers
        $year = $carbon->year;
        $month = $carbon->month;
        $sellers = [];
        $sellers['data'] = [];
        $sellers['colors'] = [];
        $sellers['labels'] = [];

        $seller_earnings = SellerEarning::query()
            ->select('seller_earning.*', 'sellers.name', 'sellers.avatar')
            ->join('sellers', 'sellers.id', 'seller_earning.seller_id')
            ->where('year', $year)->where('month', $month)->orderByDesc('earnings')->limit(7)->get();
        foreach ($seller_earnings as $item) {
            $sellers['data'][] = intval($item->earnings);
            $sellers['colors'][] = "#" . random_color();
            $sellers['labels'][] = $item->name;
        }
        #endregion

        #region orders
        $orders = [
            'total' => Order::query()->count(),
            'order' => Order::query()->where('type', Order::$order)->count(),
            'proforma' => Order::query()->where('type', Order::$proforma)->count(),
            'pin_code' => Order::query()->where('type', Order::$pin_code)->count(),
        ];
        #endregion

        #region order pending payment
        $datatable_route = route('backend.pending.payment.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['user_id'] = 'user_id';
        $datatable_columns['seller_name'] = 'seller_name';
        $datatable_columns['amount'] = 'amount';
        $datatable_columns['status'] = 'status';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';

        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null, [], null, null, null, false, false, false);
        #endregion

        return view('backend.dashboard.index', compact('admin', 'datatable_script',
            'orders', 'statistics', 'analyticsData', 'total_order', 'count_order', 'total_order_completed',
            'count_order_completed', 'count_order_on_hold', 'total_order_on_hold', 'total_order_pending_payment',
            'count_order_pending_payment', 'total_order_processing', 'count_order_processing', 'sellers'));
    }

    function logout()
    {
        auth('admin')->logout();
        return redirect('/');
    }

    public function change_locale($locale)
    {
        \app()->setLocale($locale);
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    }

    function save_token(Request $request)
    {

        $token = $request->device_token;
        $admin = Admin::find(auth('admin')->id());
        $admin->device_token = $token;
        $admin->save();
        return response()->data(trans('backend.global.success_message.updated_successfully'));

    }

    function fixSlug()
    {
        $items = [];
        $tables = ['brands', 'brand_models', 'attributes','sub_attributes'];
        foreach ($tables as $table) {
            $data = DB::table($table)->orderByDesc('id')->get();
            foreach ($data as $item) {
                $count_item = DB::table($table)->where('slug', $item->slug)->count();
                if ($count_item > 1) {
                    DB::table($table)
                        ->where('id', $item->id)
                        ->update(['slug' =>  $item->slug.'-'.$item->id ]);
                    $items[$table][] = $item->id . '-' . $item->slug;
                }
            }
        }
        return redirect()->to('/');
    }
}
