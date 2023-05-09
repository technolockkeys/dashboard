<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\Seller;
use App\Models\SellerCommission;
use App\Models\SellerEarning;
use Carbon\Carbon;

trait EraningsTrait
{


    function calculate_eranings($year, $month, $seller_id = null)
    {

        $sellers = Seller::query();
        if (!empty($seller_id)) {
            $sellers->where('id', $seller_id);
        }
        $sellers = $sellers->get();

        foreach ($sellers as $key => $seller) {
            $carbon_start_month = new Carbon();
            $carbon_start_month->setYear($year);
            $carbon_start_month->setMonth($month);
            $carbon_start_month->startOfMonth();
            $carbon_end_month = $carbon_start_month->copy()->endOfMonth();
            $orderQuery = Order::query()
                ->where('seller_id', $seller->id)
                ->whereIn('status', [Order::$completed, Order::$processing])
                ->whereIn('type', [Order::$order, Order::$pin_code])
                ->where('payment_status', Order::$payment_status_paid)
                ->whereBetween('updated_at', [$carbon_start_month->format('Y-m-d H:i:s'), $carbon_end_month->format('Y-m-d H:i:s')]);
            $sum_order = $orderQuery->sum('total');
            $sum_shipping_order = $orderQuery->sum('shipping');

//            if ($sum_order != 0) {
            $seller_commissions = SellerCommission::query()->where('seller_id', $seller->id)
                ->where('from', '<=', ($sum_order - $sum_shipping_order))
                ->where('to', '>=', ($sum_order - $sum_shipping_order))
                ->first();
            if (empty($seller_commissions)) {
                $seller_commissions = 0;
            } else {
                $seller_commissions = $seller_commissions->commission;
            }
            $orders = Order::query()
                ->where('seller_id', $seller->id)
                ->whereIn('status', [Order::$completed, Order::$processing])
                ->whereIn('type', [Order::$order, Order::$pin_code])
                ->where('payment_status', Order::$payment_status_paid)
                ->whereBetween('updated_at', [$carbon_start_month->format('Y-m-d H:i:s'), $carbon_end_month->format('Y-m-d H:i:s')])
                ->get();
            foreach ($orders as $order) {
                $order->seller_commission = ($order->total - $order->shipping) * $seller_commissions / 100;
                $order->save();
            }

            $seller_earning = SellerEarning::query()->where('seller_id', $seller->id)->where('year', $year)->where('month', $month)->first();
            $seller_earning2 = $seller_earning;
            if (empty($seller_earning)) {
                $seller_earning = new SellerEarning();
            }
            $seller_earning->seller_id = $seller->id;
            $seller_earning->year = $year;
            $seller_earning->month = $month;
            $seller_earning->date = Carbon::now()->format('Y-m-d');
            $seller_earning->commissions = $seller_commissions;
            $seller_earning->total_orders =  ($sum_order - $sum_shipping_order);
            $seller_earning->earnings =  ($sum_order - $sum_shipping_order) * $seller_commissions / 100;
            if (count($orders) != 0 || (!empty($seller_earning2))) {
                $seller_earning->save();

            }
        }

    }
}
