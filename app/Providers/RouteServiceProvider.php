<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/admin';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */

    protected function mapFrontend()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapApi()
    {
        Route::group([
            'middleware' => ['api.check','api.lang','cors','api.localization'],
            'prefix' => 'api',
            'as' => 'api.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    protected function mapBackend()
    {

        Route::group([
            'middleware' => ['web', 'admin'],
            'prefix' => 'admin',
            'as' => 'backend.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/backend.php');
        });


    }

    protected function mapSeller()
    {

        Route::group([
            'middleware' => ['web', 'seller'],
//            'prefix' => 'seller',
            'as' => 'seller.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/seller.php');
        });


    }

    public function map()
    {

        if (session()->has('lang')) {
            \App::setLocale(session()->get('lang'));
        } else {
            session()->put('lang',config('app.locale'));
            \App::setLocale(config('app.locale'));
        }
        $this->mapApi();
        $this->mapFrontend();
        $this->mapBackend();
        $this->mapSeller();

    }

    /**
     * Configure the rate limiters for the application.
     */

}
