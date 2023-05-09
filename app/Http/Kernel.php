<?php

namespace App\Http;

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\RedirectMiddleware;
use App\Http\Middleware\UserAuthenticated;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Fruitcake\Cors\HandleCors::class,
        RedirectMiddleware::class,
        \App\Http\Middleware\ApiLocalization::class,

    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\Backend\IsAdmin::class,
        'seller' => \App\Http\Middleware\Seller\IsSeller::class,
        'is.not.admin' => \App\Http\Middleware\Backend\IsNotlogined::class,
        'is.not.seller' => \App\Http\Middleware\Seller\IsNotLogined::class,
        '2fa' => \App\Http\Middleware\TwoFactorMiddleware::class,
        'api.check' => \App\Http\Middleware\ApiCheckMiddleware::class,
        'api.lang' => \App\Http\Middleware\ApiCheckLangMiddleware::class,
        'api.user-auth' => UserAuthenticated::class,
        'api.localization'=>\App\Http\Middleware\ApiLocalization::class,

        'cors' => CorsMiddleware::class,
        \App\Http\Middleware\Localization::class,
        \App\Http\Middleware\ApiLocalization::class,
        'redirect'=> RedirectMiddleware::class
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthGates::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\VerificationMiddleware::class,
            \App\Http\Middleware\Localization::class,
            CorsMiddleware::class

        ],
        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthGates::class,
            \App\Http\Middleware\ApiCheckMiddleware::class,
            \App\Http\Middleware\Api\CheckAuthApi::class,
            RedirectMiddleware::class,
            CorsMiddleware::class,
            \App\Http\Middleware\ApiLocalization::class,
        ],
    ];
}
