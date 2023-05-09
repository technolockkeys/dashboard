<?php

use Spatie\Analytics\Period;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\AnalyticsClientFactory;

#region test
Route::get('/', function () {

    $analyticsConfig = config('analytics');
    $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
    $analytic = new Analytics($client, $analyticsConfig['view_id']);
    $analyticsData = $analytic->fetchTotalVisitorsAndPageViews(Period::days(7));
//    $analyticsData = $analytic->fetchVisitorsAndPageViews(Period::days(7));
//    $analyticsData[] = $analytic->fetchTopReferrers(Period::days(7));
//    $analyticsData[] = $analytic->fetchUserTypes(Period::days(7));
//    $analyticsData[] = $analytic->fetchTopBrowsers(Period::days(7));
//    $analyticsData[]= $analytic->getAnalyticsService();

    return view('welcome', ['analyticsData' => $analyticsData]);
});
//Route::get('/',[\App\Http\Controllers\HomeController::class , 'index'])->name('home');
Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
//Auth::routes();
#endregion

#region admin
Route::group(['middleware' => ['web', 'is.not.admin'], 'prefix' => 'admin', 'as' => 'backend.',], function () {
    Route::get('login', [App\Http\Controllers\Backend\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Backend\Auth\LoginController::class, 'login'])->name('login');
    Route::get('password/rest', [App\Http\Controllers\Backend\Auth\ForgotPasswordController::class, 'getEmail'])->name('password.reset');
    Route::post('password/rest', [App\Http\Controllers\Backend\Auth\ForgotPasswordController::class, 'postEmail'])->name('password.email');
    Route::post('password/resend', [App\Http\Controllers\Backend\Auth\ForgotPasswordController::class, 'postEmail'])->name('password.resend');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Backend\Auth\ResetPasswordController::class, 'getPassword'])->name('password.request');
    Route::post('/reset-password', [App\Http\Controllers\Backend\Auth\ResetPasswordController::class, 'updatePassword'])->name('resetPassword');


});
#endregion

#region seller
Route::group(['middleware' => ['web', "is.not.seller"], 'prefix' => 'seller', 'as' => 'seller.'], function () {
    Route::get('login', [App\Http\Controllers\Seller\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Seller\Auth\LoginController::class, 'login'])->name('login');
    Route::get('password/rest', [App\Http\Controllers\Seller\Auth\ForgotPasswordController::class, 'getEmail'])->name('password.reset');
    Route::post('password/rest', [App\Http\Controllers\Seller\Auth\ForgotPasswordController::class, 'postEmail'])->name('password.email');
    Route::post('password/resend', [App\Http\Controllers\Seller\Auth\ForgotPasswordController::class, 'postEmail'])->name('password.resend');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Seller\Auth\ResetPasswordController::class, 'getPassword'])->name('password.request');
    Route::post('/reset-password', [App\Http\Controllers\Seller\Auth\ResetPasswordController::class, 'updatePassword'])->name('resetPassword');
});

#endregion
Route::get('verification/verify', [\App\Http\Controllers\Frontend\AuthController::class, 'email_verify'])->name('verification.verify');
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('auth/google', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'redirectToGoogle'])->name('google-login');
Route::get('auth/google/callback', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'handleGoogleCallback'])->name('google');

Route::post('datatable/get', [\App\Http\Controllers\DatatableSaveStateController::class, 'get'])->name('datatable.get');
Route::post('datatable/set', [\App\Http\Controllers\DatatableSaveStateController::class, 'set'])->name('datatable.set');
Route::get('order/print/{uuid}', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'donwloadPdf'])->name('orders.print');
