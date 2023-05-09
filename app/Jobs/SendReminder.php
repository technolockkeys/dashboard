<?php

namespace App\Jobs;

use App\Mail\ReminderEmail;
use App\Mail\SendCouponNotification;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\SetMailConfigurations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,SetMailConfigurations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setMailConfigurations();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::query()->whereHas('wishlists')->get();
        $this->setMailConfigurations();
        $remind_after = get_setting('send_reminder_after') ?? 5;

        $this->setMailConfigurations();
        foreach ($users->chunk(30) as $chunk) {
            foreach ($chunk as $user) {
                $wishlist = $user->wishlists()->orderBy('wishlists.updated_at')->first();
                if ($wishlist->updated_at <= Carbon::now()->subDays($remind_after) && $wishlist->updated_at >= Carbon::now()->subDays($remind_after + 1)) {
                    $data = [
                        'text' => trans('email.you_have_wishlists'),
                        'user' => $user,
                        'products' => $user->wishlists
                    ];

                    try {
                        if (!empty($user->email)){
                            \Mail::to($user->email)->queue(new ReminderEmail(trans('email.wishlists_reminder'), $data ,'wishlists'));
                        }

                    }catch (\Exception $exception){
                        Log::error("mail wishlists: ". $exception->getMessage());
                    }


                }
            }
        }
        $this->setMailConfigurations();
        $users = User::query()->whereHas('carts')->get();

        foreach ($users->chunk(30) as $chunk) {
            foreach ($chunk as $user) {
                $cart = $user->carts()->orderBy('carts.updated_at')->first();

                if ($cart->updated_at <= Carbon::now()->subDays($remind_after) && $cart->updated_at >= Carbon::now()->subDays($remind_after + 1))
                {
                    $data = [
                        'text' => trans('email.you_have_carts'),
                        'user' => $user,
                        'products' => $user->carts
                    ];

                    try {
                        if (!empty($user->email)){
                            Log::error($user->email);
                            \Mail::to($user->email)->queue(new ReminderEmail(trans('email.carts_reminder'), $data ,'carts'));
                        }

                    }catch (\Exception $exception){
                        Log::error("mail carts: ". $exception->getMessage());
                    }

                }
            }
        }
        $users = User::query()->WhereHas('compared_products')->get();
        $this->setMailConfigurations();
        foreach ($users->chunk(30) as $chunk) {
            foreach ($chunk as $user) {
                $compared_products = $user->compared_products()->orderBy('updated_at')->first();
                if ($compared_products->pivot->updated_at <= Carbon::now()->subDays($remind_after)
                    && $compared_products->pivot->updated_at >= Carbon::now()->subDays($remind_after + 1)) {

                    $subject = trans('email.compares_reminder');
                    $data = [
                        'text' => trans('email.you_have_compares'),
                        'user' => $user,
                        'products' => $user->compared_products
                    ];
                    try {
                        if (!empty($user->email)){
                            \Mail::to($user->email)->queue(new ReminderEmail($subject, $data ,'compared_products'));
                        }

                    }catch (\Exception $exception){
                        Log::error("mail compared_products: ". $exception->getMessage());
                    }
                }
            }
        }


    }
}
