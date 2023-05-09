<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['seller.layout.app'],function($view){

            $notifications = auth('seller')->user()->notifications()->where('read', 0)->latest()->limit(10)->get() ;
            return $view->with(compact('notifications'));

        });
        view()->composer(['backend.layout.app'],function($view){

            $notifications = auth('admin')->user()->notifications()->where('read', 0)->latest()->limit(10)->get() ;
            return $view->with(compact('notifications'));

        });


    }
}
