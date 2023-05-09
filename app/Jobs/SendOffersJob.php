<?php

namespace App\Jobs;

use App\Mail\SendOfferMail;
use App\Models\Coupon;
use App\Models\CouponOfferUser;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOffersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo get_setting('offers_status');
        if (get_setting('offers_status') == 1) {

            $orders = Order::query()
                ->whereDate('updated_at', '>=', Carbon::now()->subDay())
                ->where('status', Order::$completed)
                ->get();
             $offers = Offer::query()->where('status', 1)->get();
            foreach ($offers as $offer) {
                $code = 'TLOC' . Order::RandomString(9);
                while (!empty(Coupon::where('code', $code)->first())) {
                    $code = 'TLOC' . Order::RandomString(9);
                }
                $coupon = Coupon::create([
                    'code' => $code,
                    'discount' => $offer->discount,
                    'discount_type' => $offer->discount_type,
                    'type' => $offer->type,
                    'minimum_shopping' => $offer->minimum_shopping,
                    'products_ids' => $offer->products_ids,
                    'free_shipping' => $offer->free_shipping,
                    'starts_at' => Carbon::now(),
                    'ends_at' => Carbon::now()->addDays($offer->days)->format('Y-m-d H:i:s'),
                    'max_use' => 1,
                    'status' => 1,
                ]);
                $coupon->offer()->associate($offer);
                $coupon->save();
                $users = [];
                foreach ($orders as $order) {

                    if ($offer->from <= $order->total && $offer->to >= $order->total) {
                        array_push($users, $order->user);
                        break;
                    }
                }
                foreach ($users as $user){
                    CouponOfferUser::create([
                        'user_id'=>$user->id,
                        'coupon_id'=>$coupon->id,
                        'offer_id'=>$offer->id,
                    ]);
                }


                $coupon->update([
                    'max_use' => sizeof($users)
                ]);
                $data = [
                    'coupon' => $coupon,
                ];
                if (!empty($offer->products_ids)){
                    $data['products'] = Product::whereIn('id', $offer->products_ids)->get();
                }else{
                    $data['products'] =[];
                }

                foreach (collect($users)->chunk(30) as $index => $chunk) {
                    echo json_encode($chunk->toArray());
                    Mail::to($chunk->toArray())->later(Carbon::now()->addMinutes(($index+1)*2),new SendOfferMail(trans('email.new_coupon_for_you'), $data));
                }

            }
        }


    }
}
