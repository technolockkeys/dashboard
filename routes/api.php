<?php
#region auth
use App\Http\Controllers\Api\Frontend\WishlistController;

Route::group(['prefix' => 'user/auth', 'as' => 'user.auth.'], function () {
    Route::post('login', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'login'])->name('login');
    Route::post('register', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'register'])->name('register');
    Route::post('login-with-facebook', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'login_with_facebook'])->name('facebook-login');
    Route::post('login-with-google', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'login_with_google'])->name('google');
    Route::post('verfiy-mail', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'verfiyMail'])->name('verfiy-mail');
    Route::post('verfiy-mail/test/success', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'testVerfiyMail'])->name('verfiy-mail');
    Route::post('verfiy-mail/test/fail', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'testVerfiyMailfail'])->name('verfiy-mail');
    Route::group(['middleware' => ['api.user-auth', 'api']], function () {
        Route::post('logout', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'logout'])->name('logout');
        Route::post('change-password', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'change_password'])->name('change-password');
    });

});

Route::post('/redirect', [\App\Http\Controllers\Api\Frontend\RedirectController::class, 'redirect']);
#endregion
Route::post('/forget-password', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'forgot_password'])->name('login');
Route::post('/reset-password', [\App\Http\Controllers\Api\Frontend\User\Auth\AuthController::class, 'reset_password'])->name('reset-password');
#region me
Route::post('me', [\App\Http\Controllers\Api\Frontend\MeController::class, 'me'])->name('me');
#endregion

#region user

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['api.user-auth', 'api']], function () {
    Route::get('/profile', [\App\Http\Controllers\Api\Frontend\UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Api\Frontend\UserController::class, 'update'])->name('update');
    Route::get('/wallet', [\App\Http\Controllers\Api\Frontend\UserController::class, 'wallet'])->name('wallet');
    Route::get('/my-reviews', [\App\Http\Controllers\Api\Frontend\ReviewController::class, 'get_my_reviews'])->name('my-reviews');
    Route::group(['prefix' => 'addresses', 'as' => 'addresses.'], function () {
        Route::get('/', [\App\Http\Controllers\Api\Frontend\User\AddressController::class, 'addresses'])->name('all');
        Route::post('/create', [\App\Http\Controllers\Api\Frontend\User\AddressController::class, 'create'])->name('create');
        Route::post('/{address}/update', [\App\Http\Controllers\Api\Frontend\User\AddressController::class, 'update'])->name('update');
        Route::post('/{address}/set_default', [\App\Http\Controllers\Api\Frontend\User\AddressController::class, 'set_default_address'])->name('set_default_address');
        Route::delete('/{address}', [\App\Http\Controllers\Api\Frontend\User\AddressController::class, 'destroy'])->name('destroy');

    });

    Route::group(['prefix' => 'whats-new'], function () {
        Route::get('', [\App\Http\Controllers\Api\Frontend\WhatsNewController::class, 'get']);
        Route::post('read', [\App\Http\Controllers\Api\Frontend\WhatsNewController::class, 'read']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Frontend\WhatsNewController::class, 'get_news']);
    });


#region order
    Route::group(['as' => 'orders.', 'prefix' => 'orders'], function () {
        Route::post('/test-create', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'create_order']);
        Route::get('/', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'orders'])->name('orders');
        Route::post('create', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'create'])->name('create');
        Route::post('repay', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'repay'])->name('repay');
        Route::post('send_invoice/{uuid}', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'sendInvoice'])->name('repay');
        Route::post('paypal/response', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'paypal_response'])->name('paypal.response');
        Route::get('calculate_shipping_cost', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'api_calculate_shipping_cost'])->name('calculate-shipping-cost');
        Route::get('checkout', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'checkout'])->name('checkout');
        Route::get('/{uuid}', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'order'])->name('orders.show');
        Route::get('print/{uuid}', [\App\Http\Controllers\Api\Frontend\OrderController::class, 'donwloadPdf'])->name('orders.print');
    });

#endregion

    Route::get('/coupons', [\App\Http\Controllers\Api\Frontend\UserController::class, 'coupons'])->name('coupons');

});

#endregion

#region tickets
Route::group(['prefix' => 'tickets', 'as' => 'tickets.'], function () {
    Route::get('/', [\App\Http\Controllers\Api\Frontend\TicketController::class, 'tickets'])->name('tickets');
    Route::post('/create', [\App\Http\Controllers\Api\Frontend\TicketController::class, 'create'])->name('create');
    Route::post('/reply', [\App\Http\Controllers\Api\Frontend\TicketReplyController::class, 'reply'])->name('reply');
    Route::get('/{system_id}', [\App\Http\Controllers\Api\Frontend\TicketController::class, 'ticket'])->name('ticket');

});
#endregion

#region add to cart
Route::group(['prefix' => 'cart', 'as' => 'cart.', 'middleware' => ['api.user-auth', 'api']], function () {
    Route::get('/', [\App\Http\Controllers\Api\Frontend\CartController::class, 'get'])->name('get');
    Route::post('/', [\App\Http\Controllers\Api\Frontend\CartController::class, 'add'])->name('add');
    Route::delete('/{cart_id}', [\App\Http\Controllers\Api\Frontend\CartController::class, 'delete'])->name('delete');
    Route::post('change-quantity', [\App\Http\Controllers\Api\Frontend\CartController::class, 'change_quantity'])->name('change.quantity');
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::post('/', [\App\Http\Controllers\Api\Frontend\CartController::class, 'add_coupon'])->name('add');
        Route::post('/remove', [\App\Http\Controllers\Api\Frontend\CartController::class, 'delete_coupon'])->name('delete');
    });
});
#endregion


#region stripe
Route::group(['as' => 'card.', 'prefix' => 'card', 'middleware' => ['api.user-auth', 'api']], function () {
    Route::get('/', [\App\Http\Controllers\Api\Frontend\User\Payment\CardController::class, 'get'])->name('get');
    Route::post('/create', [\App\Http\Controllers\Api\Frontend\User\Payment\CardController::class, 'create'])->name('create');
    Route::post('delete', [\App\Http\Controllers\Api\Frontend\User\Payment\CardController::class, 'delete'])->name('delete');
});

#endregion

#region downloads

Route::group(['as' => 'downloads.', 'prefix' => 'downloads'], function () {
    Route::get('/', [\App\Http\Controllers\Api\Frontend\DownloadController::class, 'all'])->name('all');
    Route::get('/{slug}', [\App\Http\Controllers\Api\Frontend\DownloadController::class, 'get'])->name('get');
});

#endregion

#region cities
Route::group(['as' => 'cities.', 'prefix' => 'cities'], function () {
    Route::get('{country}', [\App\Http\Controllers\Api\Frontend\CityController::class, 'cities'])->name('cities');
});
#endregion

#region countries
Route::group(['as' => 'countries.', 'prefix' => 'countries'], function () {
    Route::post('', [\App\Http\Controllers\Api\Frontend\CountryController::class, 'all'])->name('all');
});
#endregion

#region pages
Route::group(['as' => 'pages.', 'prefix' => 'pages'], function () {
    Route::get('', [\App\Http\Controllers\Api\Frontend\PageController::class, 'all'])->name('all');
    Route::get('{slug}', [\App\Http\Controllers\Api\Frontend\PageController::class, 'show'])->name('all');
});
#endregion

#region wishlist
\Route::group(['as' => 'wishlist.', 'prefix' => 'wishlist', 'middleware' => ['api.user-auth', 'api']], function () {
    \Route::get('/', [WishlistController::class, 'all'])->name('all');
    \Route::post('/', [WishlistController::class, 'create'])->name('create');
    \Route::delete('/{product_id}', [WishlistController::class, 'delete'])->name('delete');
});
#endregion

#region frontend

#region statuses
Route::get('status', [\App\Http\Controllers\Api\Frontend\StatusController::class, 'get'])->name('get');
#endregion

#region slider
Route::get('sliders', [\App\Http\Controllers\Api\Frontend\SliderController::class, 'get'])->name('sliders.get');

#endregion

#region setting
Route::get('setting', [\App\Http\Controllers\Api\Frontend\SettingController::class, 'get'])->name('setting.get');
#endregion

#region product
Route::get('/shop', [\App\Http\Controllers\Api\Frontend\ProductController::class, 'all'])->name('products.all');
Route::group(['as' => 'products.', 'prefix' => 'products'], function () {
    Route::post('/filters', [\App\Http\Controllers\Api\Frontend\ProductController::class, 'filters'])->name('filters');
    Route::get('/top-selling-products', [\App\Http\Controllers\Api\Frontend\ProductController::class, 'top_selling_products'])->name('top_selling_products');
    Route::get('/last-visited-products', [\App\Http\Controllers\Api\Frontend\ProductController::class, 'last_visited_products'])->name('last_visited_products')->middleware('api.user-auth');
    Route::get('/suggested-products', [\App\Http\Controllers\Api\Frontend\ProductController::class, 'suggested_product'])->name('suggested_product')->middleware('api.user-auth');
    Route::get('/random-products', [\App\Http\Controllers\Api\Frontend\ProductController::class, 'random_products'])->name('random-products');
    #region reviews
    Route::group(['as' => 'review.', 'prefix' => 'review'], function () {
        Route::get('', [\App\Http\Controllers\Api\Frontend\ReviewController::class, 'get_all'])->name('get_all');
        Route::post('/create', [\App\Http\Controllers\Api\Frontend\ReviewController::class, 'create'])->name('create');
    });
    Route::group(['as' => 'compares.', 'prefix' => '/compares', 'middleware' => 'api.user-auth'], function () {
        Route::post('', [\App\Http\Controllers\Api\Frontend\UserController::class, 'add_to_compare']);
        Route::get('', [\App\Http\Controllers\Api\Frontend\UserController::class, 'get_compares']);
        Route::post('remove', [\App\Http\Controllers\Api\Frontend\UserController::class, 'delete_from_compares']);
    });
    #endregion
    Route::get('/{slug}', [\App\Http\Controllers\Api\Frontend\ProductController::class, 'show'])->name('show');
});
#endregion

Route::group(['prefix' => '/brands', 'as' => 'brands'], function () {
    Route::post('/get-models', [\App\Http\Controllers\Api\Frontend\BrandController::class, 'get_models']);
    Route::post('/get-years', [\App\Http\Controllers\Api\Frontend\BrandController::class, 'get_years']);
});

#endregion

#region frontend language

Route::get('/translations', [\App\Http\Controllers\Api\Frontend\TranslateController::class, 'get_translations']);
#endregion


#region pin code
Route::group(['prefix' => 'pin-code', 'as' => 'pin-code.'], function () {
    Route::get('/price', [\App\Http\Controllers\Api\Frontend\User\PinCodeController::class, 'get_prices'])->name('get_prices');
    Route::post('/price/{brand}', [\App\Http\Controllers\Api\Frontend\User\PinCodeController::class, 'get_brand_price'])->name('create');
    Route::post('/submit/{type}', [\App\Http\Controllers\Api\Frontend\User\PinCodeController::class, 'create'])->name('create');
});

#endregion

#region contact us
Route::post('contact-us', [\App\Http\Controllers\Api\Frontend\ContactUsController::class, 'create']);

#endregion

#region search
Route::group(['prefix' => 'search'], function () {
    Route::get('product', [\App\Http\Controllers\Api\Frontend\SearchController::class, 'products']);
    Route::any('filter', [\App\Http\Controllers\Api\Frontend\SearchController::class, 'filter']);
});
#endregion
