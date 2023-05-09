<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SellerEarning;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index()
    {
        $count_user = User::query()->where('seller_id', auth('seller')->id())->count();

//        $orders = Order::query()->where(function ($q) {
//            $q->where('seller_id', auth('seller')->user()->id)
//                ->orWhereIn('seller_id', auth('seller')->user()->sellers()->pluck('id'));
//        });
//        $query = $orders;
        $total_orders_value =  Order::query()
            ->where('seller_id', auth('seller')->user()->id)
            ->whereIn('status' ,[Order::$processing , Order::$completed])
            ->where('payment_status' , Order::$payment_status_paid)
            ->whereIn('type' ,[Order::$order , Order::$pin_code])->sum('total');

        $completed_orders_value =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$completed)->sum('total');
        $completed_orders_count =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$completed)->count();
        $on_hold_orders_value =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$on_hold)->sum('total');
        $on_hold_orders_count =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$on_hold)->count();
        $pending_payment_orders_value =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$pending_payment)->sum('total');
        $pending_payment_orders_count =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$pending_payment)->count();
        $processing_orders_value =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$processing)->sum('total');
        $processing_orders_count =  Order::query()->where('seller_id', auth('seller')->user()->id)->where('type' ,Order::$order)->where('status', Order::$processing)->count();
        $users = User::query()
            ->where('seller_id', auth('seller')->id())
            ->select('users.name', 'users.avatar', 'users.phone', 'users.email', 'users.uuid', 'users.id')
            ->limit(5)
            ->orderByDesc('id')
            ->get();
        foreach ($users as $user) {
            $user->balance = UserWallet::query()->where('status', UserWallet::$approve)->where('user_id', $user->id)->sum('amount');
//            $user->balance = $user->id;
        }
        $sellers = [];
        $seller_earnings = SellerEarning::query()
            ->select('seller_earning.*', 'sellers.name', 'sellers.avatar')
            ->join('sellers', 'sellers.id', 'seller_earning.seller_id')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->where('sellers.id', auth('seller')->id())
            ->limit(7)->get();

        $total_earnings = SellerEarning::query()
            ->where('seller_id', auth('seller')->id())
            ->where('year'  , date("Y"))
            ->where('month'  , date("m"))
            ->sum('earnings');

        foreach ($seller_earnings as $item) {
            $sellers['data'][] = intval($item->earnings);
            $sellers['colors'][] = "#" . random_color();
            $sellers['labels'][] = $item->year . '/' . $item->month;
        }
        $orders = Order::query()->select('orders.*','users.name','users.avatar','users.provider_type')
            ->join('users','users.id','orders.user_id')
            ->where('type' ,Order::$order)
            ->whereNotIn('orders.status' ,[Order::$waiting , Order::$refunded , Order::$canceled , Order::$failed])
            ->where('orders.seller_id', auth('seller')->id())
            ->orderByDesc('orders.created_at')
            ->limit(8)->get();
        return view('seller.dashboard.index', compact('count_user', 'users', 'completed_orders_value',
            'completed_orders_count', 'on_hold_orders_value', 'on_hold_orders_count', 'pending_payment_orders_value', 'pending_payment_orders_count',
            'processing_orders_value', 'processing_orders_count', 'total_orders_value', 'sellers','orders', 'total_earnings'));
    }
}
