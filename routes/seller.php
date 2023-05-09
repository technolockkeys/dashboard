<?php
#region home page
Route::get('/', [\App\Http\Controllers\Seller\HomeController::class, 'index'])->name('home');
#endregion

#region profile
Route::get('/profile', [\App\Http\Controllers\Seller\ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [\App\Http\Controllers\Seller\ProfileController::class, 'update'])->name('profile.update');
Route::get('/logout', [\App\Http\Controllers\Seller\DashboardController::class, 'logout'])->name('logout');
#endregion

#region order
Route::resource('orders', \App\Http\Controllers\Seller\OrderController::class)->except(['update', 'edit']);
Route::get('locale/{locale}', [\App\Http\Controllers\Seller\DashboardController::class, 'change_locale'])->name('set_locale');

Route::group(['as' => 'orders.', 'prefix' => 'orders'], function () {
    Route::post('datatable', [\App\Http\Controllers\Seller\OrderController::class, 'datatable'])->name('datatable');
    Route::post('attribute', [\App\Http\Controllers\Seller\OrderController::class, 'attribute'])->name('attribute');
    Route::post('get_price', [\App\Http\Controllers\Seller\OrderController::class, 'get_price'])->name('price.get');
    Route::get('{id}/download', [\App\Http\Controllers\Seller\OrderController::class, 'download'])->name('download');
    Route::post('{order}/change-status', [\App\Http\Controllers\Seller\OrderController::class, 'change_status'])->name('change.status');

    Route::group(['as' => 'address.', 'prefix' => 'address'], function () {
        Route::post('get', [\App\Http\Controllers\Seller\OrderController::class, 'get_address'])->name('get');
        Route::post('get/city', [\App\Http\Controllers\Seller\OrderController::class, 'get_city'])->name('city.get');
        Route::post('set', [\App\Http\Controllers\Seller\OrderController::class, 'set_address'])->name('set');
    });
    Route::post('apply_coupon', [\App\Http\Controllers\Seller\OrderController::class, 'apply_coupon'])->name('apply.coupon');
    Route::get('edit/{uuid}', [\App\Http\Controllers\Seller\OrderController::class, 'edit'])->name('edit');
    Route::post('{uuid}/update', [\App\Http\Controllers\Seller\OrderController::class, 'update'])->name('update');
    Route::get('{uuid}/send/pdf', [\App\Http\Controllers\Seller\OrderController::class, 'send_pdf_to_user'])->name('send.pdf');
    Route::post('get_shipping_cost', [\App\Http\Controllers\Seller\OrderController::class, 'get_shipping_cost'])->name('get.shipping.cost');
    Route::get('refund/{id}', [\App\Http\Controllers\Seller\OrderController::class, 'order_refund'])->name('refund');
    Route::get('cancel/{id}', [\App\Http\Controllers\Seller\OrderController::class, 'cancel'])->name('cancel');
    Route::post('{order}/make-order', [\App\Http\Controllers\Seller\OrderController::class, 'change_to_order'])->name('make-order');

    Route::post('order/payment/show', [\App\Http\Controllers\Seller\OrderController::class, 'order_payment_show'])->name('order.payment.show');
    Route::post('order/payment/update', [\App\Http\Controllers\Seller\OrderController::class, 'order_payment_update'])->name('order.payment.update');


});
#endregion

#region wallet
//Route::resource('wallet', \App\Http\Controllers\Seller\WalletController::class);
//Route::post('wallet/datatable', [\App\Http\Controllers\Seller\WalletController::class, 'datatable'])->name('wallet.datatable');

#endregion

#region user
Route::resource('users', \App\Http\Controllers\Seller\UserController::class)->except(['create', 'edit', 'update', 'destroy']);
Route::group(['as' => 'users.', 'prefix' => 'users'], function () {
    Route::post('update/{uuid}', [\App\Http\Controllers\Seller\UserController::class, 'update'])->name('update');
    Route::post('datatable', [\App\Http\Controllers\Seller\UserController::class, 'datatable'])->name('datatable');
    Route::group(['as' => 'address.', 'prefix' => 'address'], function () {
        Route::post('datatable/{uuid}', [\App\Http\Controllers\Seller\UserController::class, 'address_datatable'])->name('datatable');
        Route::post('delete', [\App\Http\Controllers\Seller\UserController::class, 'delete_address'])->name('delete');
        Route::post('create', [\App\Http\Controllers\Seller\UserController::class, 'create_new_address'])->name('create');
        Route::post('edit', [\App\Http\Controllers\Seller\UserController::class, 'edit_address'])->name('edit');
    });
    Route::group(['as' => 'payment_recodes.', 'prefix' => 'payment/recode'], function () {
        Route::post('/', [\App\Http\Controllers\Seller\UserController::class, 'payment_recode_datatable'])->name('index');
        Route::post('get/balance', [\App\Http\Controllers\Seller\UserController::class, 'get_balance'])->name('get.balanec');
        Route::post('set/balance', [\App\Http\Controllers\Seller\UserController::class, 'set_balance'])->name('set.balanec');
        Route::post('send/reminder', [\App\Http\Controllers\Seller\UserController::class, 'send_reminder'])->name('send.reminder');
        Route::post('send/account-statement', [\App\Http\Controllers\Seller\UserController::class, 'send_account_statement'])->name('send.account-statement');
    });
    Route::post('wallet/payment/info', [\App\Http\Controllers\Seller\UserController::class, 'wallet_payment_info'])->name('wallet.payment.info');
    Route::post('wallet/payment/change', [\App\Http\Controllers\Seller\UserController::class, 'wallet_payment_change_status'])->name('wallet.payment.change.status');
    Route::post('wallet/payment/get', [\App\Http\Controllers\Seller\UserController::class, 'wallet_payment_get'])->name('wallet.payment.get');
    Route::post('wallet/payment/set', [\App\Http\Controllers\Seller\UserController::class, 'wallet_payment_set'])->name('wallet.payment.set');
    Route::post('order/payment/show', [\App\Http\Controllers\Backend\OrderController::class, 'order_payment_show'])->name('payment.show');

});
#endregion

#region sellers
Route::get('/sellers', [\App\Http\Controllers\Seller\SellerController::class, 'index'])->name('sellers.index');
Route::post('/sellers/datatable', [\App\Http\Controllers\Seller\SellerController::class, 'datatable'])->name('sellers.datatable');
#endregion

#region commission
Route::get('/commission', [\App\Http\Controllers\Seller\SellerCommissionController::class, 'index'])->name('commission.index');
Route::post('/commission/datatable', [\App\Http\Controllers\Seller\SellerCommissionController::class, 'datatable'])->name('commission.datatable');

#endregion

#region product
Route::post('product/get-quantity', [\App\Http\Controllers\Backend\ProductController::class, 'get_quantity'])->name('products.get-quantity');
Route::post('product/calculate-shipping-cost', [\App\Http\Controllers\Backend\ProductController::class, 'calculate_shipping_cost'])->name('products.calculate-shipping-cost');
#endregion

#region after sales
//Route::resource('after-sale', \App\Http\Controllers\Seller\AfterSalesController::class)->except('edit', 'create', 'show');
Route::group(['as' => 'after-sale.', 'prefix' => 'after-sale'], function () {
    Route::get('/', [\App\Http\Controllers\Seller\AfterSalesController::class, 'index'])->name('index');
    Route::post('update', [\App\Http\Controllers\Seller\AfterSalesController::class, 'update'])->name('update');
    Route::post('datatable', [\App\Http\Controllers\Seller\AfterSalesController::class, 'datatable'])->name('datatable');
    Route::post('ger/order', [\App\Http\Controllers\Seller\AfterSalesController::class, 'get_order'])->name('ger_order');
    Route::post('send/email', [\App\Http\Controllers\Seller\AfterSalesController::class, 'send_email'])->name('send.email');
    Route::post("save/black/list", [\App\Http\Controllers\Seller\AfterSalesController::class, 'save_black_list'])->name('save.black.list');
});
#endregion


#region notification
Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/', [\App\Http\Controllers\Seller\NotificationController::class, 'index'])->name('index');
    Route::post('/datatable', [\App\Http\Controllers\Seller\NotificationController::class, 'datatable'])->name('datatable');
    Route::post('/read', [\App\Http\Controllers\Seller\NotificationController::class, 'read'])->name('read');
    Route::post('/read_all', [\App\Http\Controllers\Seller\NotificationController::class, 'read_all'])->name('read_all');
});
#endregion

