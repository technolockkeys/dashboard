<?php

#region home page
Route::get('/', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('home');
Route::post('pending/payment/datatable', [App\Http\Controllers\Backend\UserWalletController::class, 'datatable'])->name('pending.payment.datatable');
Route::post('save-token', [App\Http\Controllers\Backend\DashboardController::class, 'save_token'])->name('save.token');
#endregion

#region logout
Route::get('logout', [\App\Http\Controllers\Backend\DashboardController::class, 'logout'])->name('logout');
Route::get('locale/{locale}', [\App\Http\Controllers\Backend\DashboardController::class, 'change_locale'])->name('set_locale');
#endregion

#region admins
Route::resource('admins', \App\Http\Controllers\Backend\ManagerAccess\AdminsController::class, ['except' => 'show']);
Route::post('admins/datatable', [\App\Http\Controllers\Backend\ManagerAccess\AdminsController::class, 'datatable'])->name('admins.datatable');
Route::post('admins/change/status', [\App\Http\Controllers\Backend\ManagerAccess\AdminsController::class, 'change_status'])->name('admins.change.status');
Route::post('admins/delete-selected', [\App\Http\Controllers\Backend\ManagerAccess\AdminsController::class, 'delete_selected_items'])->name('admins.delete-selected');

#endregion

#region profile
Route::get('profile', [\App\Http\Controllers\Backend\ProfileController::class, 'index'])->name('profile');
Route::post('profile', [\App\Http\Controllers\Backend\ProfileController::class, 'update'])->name('profile.update');

#endregion

#region media
Route::get('media', [\App\Http\Controllers\Backend\MediaController::class, "index"])->name('media.index');
Route::group(['prefix' => 'media', 'as' => 'media.'], function () {
    Route::post('get/files', [\App\Http\Controllers\Backend\MediaModelController::class, 'get'])->name('get.files');
    Route::post('delete/files', [\App\Http\Controllers\Backend\MediaController::class, 'delete_files'])->name('delete.files');
    Route::post('details/files', [\App\Http\Controllers\Backend\MediaController::class, 'file_details'])->name('details.files');
    Route::post('upload/files', [\App\Http\Controllers\Backend\MediaController::class, 'upload_files'])->name('upload.files');
    Route::post('check/files', [\App\Http\Controllers\Backend\MediaController::class, 'check'])->name('check.files');
    Route::post('update/files', [\App\Http\Controllers\Backend\MediaController::class, 'update'])->name('update.files');
    Route::post('create/folder', [\App\Http\Controllers\Backend\MediaController::class, 'create_folder'])->name('create.folder');
    Route::post('delete/folder', [\App\Http\Controllers\Backend\MediaController::class, 'delete_folder'])->name('delete.folder');
    Route::post('model/get', [\App\Http\Controllers\Backend\MediaModelController::class, 'get'])->name('model.get');
    Route::group(['prefix' => 'cut', 'as' => 'cut.'], function () {
        Route::post('get/folder', [\App\Http\Controllers\Backend\MediaController::class, 'cut_get_folder'])->name('get.folder');
        Route::post('set/folder', [\App\Http\Controllers\Backend\MediaController::class, 'cut_set_folder'])->name('set.folder');
    });
});
#endregion

#region role
Route::resource('roles', \App\Http\Controllers\Backend\ManagerAccess\RoleController::class, ['except' => 'show']);
Route::post('roles/datatable', [\App\Http\Controllers\Backend\ManagerAccess\RoleController::class, 'datatable'])->name('roles.datatable');
Route::post('roles/get/permission', [\App\Http\Controllers\Backend\ManagerAccess\RoleController::class, 'getPermission'])->name('roles.get.permission');
Route::post('roles/delete-selected', [\App\Http\Controllers\Backend\ManagerAccess\RoleController::class, 'delete_selected_items'])->name('roles.delete-selected');

#endregion

#region color
Route::resource('colors', \App\Http\Controllers\Backend\ColorController::class, ['except' => 'show']);
Route::post('colors/datatable', [\App\Http\Controllers\Backend\ColorController::class, 'datatable'])->name('colors.datatable');
Route::post('colors/change/status', [\App\Http\Controllers\Backend\ColorController::class, 'change_status'])->name('colors.change.status');
Route::post('colors/delete-selected', [\App\Http\Controllers\Backend\ColorController::class, 'delete_selected_items'])->name('colors.delete-selected');

#endregion

#region country
Route::get('countries', [\App\Http\Controllers\Backend\CountryController::class, 'index'])->name('countries.index');
Route::post('countries/datatable', [\App\Http\Controllers\Backend\CountryController::class, 'datatable'])->name('countries.datatable');
Route::post('countries/change/status', [\App\Http\Controllers\Backend\CountryController::class, 'change_status'])->name('countries.change.status');
Route::post('countries/{country}/change/zone', [\App\Http\Controllers\Backend\CountryController::class, 'change_timezone'])->name('countries.change.zone');
Route::post('countries/{country}/zone', [\App\Http\Controllers\Backend\CountryController::class, 'zone'])->name('countries.zone');
Route::post('countries/delete-selected', [\App\Http\Controllers\Backend\CountryController::class, 'delete_selected_items'])->name('countries.delete-selected');
Route::post('countries/{country}/edit_name', [\App\Http\Controllers\Backend\CountryController::class, 'edit_name'])->name('countries.edit_name');
Route::post('countries/{country}/update_name', [\App\Http\Controllers\Backend\CountryController::class, 'update_name'])->name('countries.update_name');

#endregion

#region city
//Route::resource('cities', \App\Http\Controllers\Backend\CityController::class, ['except' => 'show']);
//Route::post('cities/datatable', [\App\Http\Controllers\Backend\CityController::class, 'datatable'])->name('cities.datatable');
//Route::post('cities/change/status', [\App\Http\Controllers\Backend\CityController::class, 'change_status'])->name('cities.change.status');
//Route::post('cities/delete-selected', [\App\Http\Controllers\Backend\CityController::class, 'delete_selected_items'])->name('cities.delete-selected');

#endregion

#region setting
Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    #region website
    Route::get('/', [\App\Http\Controllers\Backend\SettingController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\Backend\SettingController::class, 'update'])->name('update');
    #endregion

    #region stmp
    Route::get('smtp', [\App\Http\Controllers\Backend\SettingController::class, 'smtp'])->name('smtp');
    Route::post('smtp', [\App\Http\Controllers\Backend\SettingController::class, 'smtp_update'])->name('smtp.update');
    #endregion

    #region global
    Route::get('global', [\App\Http\Controllers\Backend\SettingController::class, 'global_seo'])->name('global');
    Route::post('global', [\App\Http\Controllers\Backend\SettingController::class, 'global_seo_update'])->name('global.update');
    #endregion

    #region social
    Route::get('social', [\App\Http\Controllers\Backend\SettingController::class, 'social_media'])->name('social');
    Route::post('social', [\App\Http\Controllers\Backend\SettingController::class, 'social_media_update'])->name('social.update');
    #endregion

    #region contact
    Route::get('contact', [\App\Http\Controllers\Backend\SettingController::class, 'contact'])->name('contact');
    Route::post('contact', [\App\Http\Controllers\Backend\SettingController::class, 'contact_update'])->name('contact.update');
    #endregion

    #region translate
    Route::get('translate', [\App\Http\Controllers\Backend\SettingController::class, 'translate'])->name('translate');
    #endregion

    #region default images
//    Route::get('default_images',        [\App\Http\Controllers\Backend\SettingController::class, 'default_images'])->name('default_images');
//    Route::post('default_images',       [\App\Http\Controllers\Backend\SettingController::class, 'product_update'])->name('default_images.update');
    #endregion

    #region payment
    Route::get('payment', [\App\Http\Controllers\Backend\SettingController::class, 'payment_methods'])->name('payment');
    Route::post('payment', [\App\Http\Controllers\Backend\SettingController::class, 'payment_update'])->name('payment.update');
    #endregion

    #region shipping
    Route::get('shipping', [\App\Http\Controllers\Backend\SettingController::class, 'shipping'])->name('shipping');
    Route::post('shipping', [\App\Http\Controllers\Backend\SettingController::class, 'store_shipping'])->name('shipping.update');
    #endregion
    #region frontend
    Route::get('frontend', [\App\Http\Controllers\Backend\SettingController::class, 'frontend'])->name('frontend');
    Route::post('frontend', [\App\Http\Controllers\Backend\SettingController::class, 'frontend_update'])->name('frontend.update');
    #endregion

    #region notifications

    Route::get('notifications', [\App\Http\Controllers\Backend\SettingController::class, 'notifications'])->name('notifications');
    Route::post('notifications', [\App\Http\Controllers\Backend\SettingController::class, 'update_notifications'])->name('notifications.update');
    #endregion
});
#endregion

#region ticket replies
//Route::get('replies', [\App\Http\Controllers\Backend\TicketReplyController::class,'store'] );
Route::post('replies/{ticket_id}', [\App\Http\Controllers\Backend\TicketReplyController::class, 'store'])->name('replies.store');
Route::get('replies/edit', [\App\Http\Controllers\Backend\TicketReplyController::class, 'edit'])->name('replies.edit');
Route::post('replies/update/{id}', [\App\Http\Controllers\Backend\TicketReplyController::class, 'update'])->name('replies.update');

#endregion

#region category
Route::resource('categories', \App\Http\Controllers\Backend\CategoyController::class, ['except' => 'show']);
Route::group(['as' => 'categories.', 'prefix' => 'categories'], function () {
    Route::post('datatable', [\App\Http\Controllers\Backend\CategoyController::class, 'datatable'])->name('datatable');
    Route::post('change/status', [\App\Http\Controllers\Backend\CategoyController::class, 'change_status'])->name('change.status');
    Route::post('check/slug', [\App\Http\Controllers\Backend\CategoyController::class, 'check_slug'])->name('check.slug');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\CategoyController::class, 'delete_selected_items'])->name('delete-selected');
    Route::post('load/parents', [\App\Http\Controllers\Backend\CategoyController::class, 'load_parents'])->name('load.parents');

});
#endregion

#region Language
Route::resource('languages', \App\Http\Controllers\Backend\LanguageController::class, ['except' => 'show']);
Route::post('languages/datatable', [\App\Http\Controllers\Backend\LanguageController::class, 'datatable'])->name('languages.datatable');
Route::post('languages/change/status', [\App\Http\Controllers\Backend\LanguageController::class, 'change_status'])->name('languages.change.status');
Route::post('languages/change/is_default', [\App\Http\Controllers\Backend\LanguageController::class, 'change_default'])->name('languages.change.default');
Route::post('languages/delete-selected', [\App\Http\Controllers\Backend\LanguageController::class, 'delete_selected_items'])->name('languages.delete-selected');

#endregion

#region Attributes
Route::resource('attributes', \App\Http\Controllers\Backend\AttributeController::class, ['except' => 'show']);
Route::post('attributes/datatable', [\App\Http\Controllers\Backend\AttributeController::class, 'datatable'])->name('attributes.datatable');
Route::post('attributes/change/status', [\App\Http\Controllers\Backend\AttributeController::class, 'change_status'])->name('attributes.change.status');
Route::post('attributes/delete-selected', [\App\Http\Controllers\Backend\AttributeController::class, 'delete_selected_items'])->name('attributes.delete-selected');

#region sub-attrbuite
//Route::resource('attributes' , \App\Http\Controllers\Backend\AttributeController::class ,['except'=>'show']);
Route::get('attributes/{attribute_id}', [\App\Http\Controllers\Backend\SubAttributeController::class, 'index'])->name('attributes.sub-attributes.index');
Route::get('attributes/{attribute_id}/sub-attributes/create', [\App\Http\Controllers\Backend\SubAttributeController::class, 'create'])->name('attributes.sub-attributes.create');
Route::get('attributes/{attribute_id}/sub-attributes/edit/{id}', [\App\Http\Controllers\Backend\SubAttributeController::class, 'edit'])->name('attributes.sub-attributes.edit');
Route::post('attributes/{attribute_id}/sub-attributes/store', [\App\Http\Controllers\Backend\SubAttributeController::class, 'store'])->name('attributes.sub-attributes.store');
Route::patch('attributes/{attribute_id}/sub-attributes/update', [\App\Http\Controllers\Backend\SubAttributeController::class, 'update'])->name('attributes.sub-attributes.update');
Route::delete('attributes/sub-attributes/destroy/{sub_attribute}', [\App\Http\Controllers\Backend\SubAttributeController::class, 'destroy'])->name('attributes.sub-attributes.destroy');
Route::post('attributes/sub-attributes/delete-selected', [\App\Http\Controllers\Backend\SubAttributeController::class, 'delete_selected_items'])->name('attributes.sub-attributes.delete-selected');

Route::post('attributes/sub-attribute/change/status', [\App\Http\Controllers\Backend\SubAttributeController::class, 'change_status'])->name('attributes.sub-attributes.change.status');
Route::post('attributes/sub-attribute/{attribute_id}/datatable/', [\App\Http\Controllers\Backend\SubAttributeController::class, 'datatable'])->name('attributes.sub-attribute.datatable');

#endregion
#endregion

#region brand
Route::resource('brands', \App\Http\Controllers\Backend\BrandController::class, ['except' => 'show']);
Route::post('brands/datatable', [\App\Http\Controllers\Backend\BrandController::class, 'datatable'])->name('brands.datatable');
Route::post('brands/change/status', [\App\Http\Controllers\Backend\BrandController::class, 'change_status'])->name('brands.change.status');
Route::post('brands/load/models/', [\App\Http\Controllers\Backend\BrandController::class, 'load_models'])->name('brands.load.models');
Route::post('brands/delete-selected', [\App\Http\Controllers\Backend\BrandController::class, 'delete_selected_items'])->name('brands.delete-selected');


Route::get('brands/{brand_id}/models', [\App\Http\Controllers\Backend\BrandModelController::class, 'index'])->name('brands.models.index');
Route::get('brands/{brand_id}/models/create', [\App\Http\Controllers\Backend\BrandModelController::class, 'create'])->name('brands.models.create');

Route::post('brands/{brand_id}/models/store', [\App\Http\Controllers\Backend\BrandModelController::class, 'store'])->name('brands.models.store');
Route::post('brands/{brand_id}/models/datatable', [\App\Http\Controllers\Backend\BrandModelController::class, 'datatable'])->name('brands.models.datatable');
Route::post('brands/models/change/status', [\App\Http\Controllers\Backend\BrandModelController::class, 'change_status'])->name('brands.models.change.status');
Route::delete('brands/models/destroy/{model}', [\App\Http\Controllers\Backend\BrandModelController::class, 'destroy'])->name('brands.models.destroy');
Route::get('brands/models/edit/{model}', [\App\Http\Controllers\Backend\BrandModelController::class, 'edit'])->name('brands.models.edit');
Route::patch('brands/models/update/{model}', [\App\Http\Controllers\Backend\BrandModelController::class, 'update'])->name('brands.models.update');
Route::post('brands/models/delete-selected', [\App\Http\Controllers\Backend\BrandModelController::class, 'delete_selected_items'])->name('brands.models.delete-selected');

Route::get('brands/models/{model_id}', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'index'])->name('brands.models.years.index');
Route::get('brands/models/{model_id}/years/create', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'create'])->name('brands.models.years.create');
Route::post('brands/models/{model_id}/years/store', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'store'])->name('brands.models.years.store');
Route::post('brands/models/{model_id}/years/datatable', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'datatable'])->name('brands.models.years.datatable');
Route::post('brands/models/years/change/status', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'change_status'])->name('brands.models.years.change.status');
Route::delete('brands/models/years/destroy/{year}', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'destroy'])->name('brands.models.years.destroy');
Route::get('brands/models/years/edit/{year}', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'edit'])->name('brands.models.years.edit');
Route::patch('brands/models/years/update/{year}', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'update'])->name('brands.models.years.update');
Route::post('brands/models/years/delete-selected', [\App\Http\Controllers\Backend\BrandModelYearController::class, 'delete_selected_items'])->name('brands.models.years.delete-selected');

#endregion

#region Language
Route::resource('pages', \App\Http\Controllers\Backend\PageController::class, ['except' => 'show']);
Route::group(['as' => 'pages.', 'prefix' => 'pages'], function () {
    Route::post('datatable', [\App\Http\Controllers\Backend\PageController::class, 'datatable'])->name('datatable');
    Route::post('change/status', [\App\Http\Controllers\Backend\PageController::class, 'change_status'])->name('change.status');
    Route::post('check/slug', [\App\Http\Controllers\Backend\PageController::class, 'check_slug'])->name('check.slug');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\PageController::class, 'delete_selected_items'])->name('delete-selected');

});
#endregion

#region Language
Route::resource('coupons', \App\Http\Controllers\Backend\CouponController::class, ['except' => 'show']);
Route::post('coupons/datatable', [\App\Http\Controllers\Backend\CouponController::class, 'datatable'])->name('coupons.datatable');
Route::post('coupons/change/status', [\App\Http\Controllers\Backend\CouponController::class, 'change_status'])->name('coupons.change.status');
Route::post('coupons/delete-selected', [\App\Http\Controllers\Backend\CouponController::class, 'delete_selected_items'])->name('coupons.delete-selected');

#endregion

#region user
Route::resource('users', \App\Http\Controllers\Backend\UserController::class);
Route::group(['as' => 'users.', 'prefix' => 'users'], function () {

    Route::post('datatable', [\App\Http\Controllers\Backend\UserController::class, 'datatable'])->name('datatable');
    Route::post('change/status', [\App\Http\Controllers\Backend\UserController::class, 'change_status'])->name('change.status');
    Route::post('/delete-selected', [\App\Http\Controllers\Backend\UserController::class, 'delete_selected_items'])->name('delete-selected');
    Route::post('load/cities', [\App\Http\Controllers\Backend\UserController::class, 'load_cities'])->name('load.cities');
    Route::group(['prefix' => '{user_id}'], function () {
        Route::post('addresses', [\App\Http\Controllers\Backend\UserController::class, 'addresses'])->name('addresses');
        Route::post('overview', [\App\Http\Controllers\Backend\UserController::class, 'overview'])->name('overview');
        Route::post('update', [\App\Http\Controllers\Backend\UserController::class, 'update'])->name('update');
        Route::post('tickets', [\App\Http\Controllers\Backend\UserController::class, 'tickets'])->name('tickets');
        Route::post('tickets/datatable', [\App\Http\Controllers\Backend\UserController::class, 'ticket_datatable'])->name('tickets.data_table');
        Route::post('wishlists', [\App\Http\Controllers\Backend\UserController::class, 'wishlist'])->name('wishlists');
        Route::post('wishlists/datatable', [\App\Http\Controllers\Backend\UserController::class, 'wishlist_datatable'])->name('wishlists.data_table');
        Route::post('reviews', [\App\Http\Controllers\Backend\UserController::class, 'reviews'])->name('reviews');
        Route::post('reviews/datatable', [\App\Http\Controllers\Backend\UserController::class, 'reviews_datatable'])->name('reviews.datatable');
        Route::post('carts', [\App\Http\Controllers\Backend\UserController::class, 'carts'])->name('carts');
        Route::post('carts/datatable', [\App\Http\Controllers\Backend\UserController::class, 'cart_datatable'])->name('carts.datatable');
        Route::post('orders', [\App\Http\Controllers\Backend\UserController::class, 'orders'])->name('orders');
        Route::post('orders/datatable', [\App\Http\Controllers\Backend\UserController::class, 'orders_datatable'])->name('orders.datatable');
        Route::group(['prefix' => 'wallet'], function () {
            Route::post('/', [\App\Http\Controllers\Backend\UserController::class, 'wallet'])->name('wallet');
            Route::post('datatable', [\App\Http\Controllers\Backend\UserController::class, 'wallet_datatable'])->name('wallet.datatable');
            Route::post('send/statement/account', [\App\Http\Controllers\Backend\UserController::class, 'send_account_statement'])->name('wallet.send.account.statement');
            Route::post('send/reminder', [\App\Http\Controllers\Backend\UserController::class, 'send_reminder'])->name('wallet.send.reminder');
        });
        Route::post('coupon', [\App\Http\Controllers\Backend\UserController::class, 'coupon'])->name('coupon');
        Route::post('coupon/datatable', [\App\Http\Controllers\Backend\UserController::class, 'coupon_datatable'])->name('coupon.datatable');
        Route::post('coupon/orders', [\App\Http\Controllers\Backend\UserController::class, 'coupons_orders'])->name('coupon.order');
        Route::post('cards', [\App\Http\Controllers\Backend\UserController::class, 'cards'])->name('cards');
        Route::post('cards/datatable', [\App\Http\Controllers\Backend\UserController::class, 'cards_datatable'])->name('cards.datatable');
        Route::post('compares', [\App\Http\Controllers\Backend\UserController::class, 'compares'])->name('compares');
        Route::post('compares/datatable', [\App\Http\Controllers\Backend\UserController::class, 'compares_datatable'])->name('compares.datatable');
    });
    Route::post('wallet/payment/info', [\App\Http\Controllers\Backend\UserController::class, 'wallet_payment_info'])->name('wallet.payment.info');
    Route::post('wallet/payment/change', [\App\Http\Controllers\Backend\UserController::class, 'wallet_payment_change_status'])->name('wallet.payment.change.status');
    Route::post('wallet/payment/get', [\App\Http\Controllers\Backend\UserController::class, 'wallet_payment_get'])->name('wallet.payment.get');
    Route::post('wallet/payment/set', [\App\Http\Controllers\Backend\UserController::class, 'wallet_payment_set'])->name('wallet.payment.set');


});
#endregion

#region wishlist

Route::get('wishlists', [\App\Http\Controllers\Backend\WishlistController::class, 'index'])->name('wishlists.index');
Route::post('wishlists/datatable', [\App\Http\Controllers\Backend\WishlistController::class, 'datatable'])->name('wishlists.datatable');
Route::delete('wishlists/destroy/{wishlist}', [\App\Http\Controllers\Backend\WishlistController::class, 'destroy'])->name('wishlists.destroy');
Route::delete('wishlists/delete-selected', [\App\Http\Controllers\Backend\WishlistController::class, 'delete_selected_items'])->name('wishlists.delete-selected');

#endregion

#region review
Route::group(['prefix' => 'reviews' ,'as'=>'reviews.'] , function (){
    Route::get('/', [\App\Http\Controllers\Backend\ReviewController::class, 'index'])->name('index');
    Route::get('{review}', [\App\Http\Controllers\Backend\ReviewController::class, 'show'])->name('show');
    Route::post('update/{review}', [\App\Http\Controllers\Backend\ReviewController::class, 'update'])->name('update');
    Route::post('datatable', [\App\Http\Controllers\Backend\ReviewController::class, 'datatable'])->name('datatable');
    Route::delete('destroy/{review}', [\App\Http\Controllers\Backend\ReviewController::class, 'destroy'])->name('destroy');
    Route::post('change/status', [\App\Http\Controllers\Backend\ReviewController::class, 'change_status'])->name('change.status');
    Route::post('replay', [\App\Http\Controllers\Backend\ReviewController::class, 'storeReply'])->name('store.replay');

});

#endregion

#region cms
Route::group(['prefix' => 'cms', 'as' => 'cms.'], function () {
    #region status
    Route::resource('statuses', \App\Http\Controllers\Backend\Cms\StatusController::class);
    Route::post('statuses/datatable', [\App\Http\Controllers\Backend\Cms\StatusController::class, 'datatable'])->name('statuses.datatable');
    Route::post('statuses/change/status', [\App\Http\Controllers\Backend\Cms\StatusController::class, 'change_status'])->name('statuses.change.status');
    Route::post('statuses/delete-selected', [\App\Http\Controllers\Backend\Cms\StatusController::class, 'delete_selected_items'])->name('statuses.delete-selected');
#endregion
    #region menus
    Route::resource('menus', \App\Http\Controllers\Backend\Cms\MenuController::class);
    Route::post('menus/datatable', [\App\Http\Controllers\Backend\Cms\MenuController::class, 'datatable'])->name('menus.datatable');
    Route::post('menus/change/status', [\App\Http\Controllers\Backend\Cms\MenuController::class, 'change_status'])->name('menus.change.status');
    Route::post('menus/delete-selected', [\App\Http\Controllers\Backend\Cms\MenuController::class, 'delete_selected_items'])->name('menus.delete-selected');
    #endregion
    #region slider
    Route::resource('sliders', \App\Http\Controllers\Backend\SliderController::class)->except(['show']);
    Route::post('sliders/datatable', [\App\Http\Controllers\Backend\SliderController::class, 'datatable'])->name('sliders.datatable');
    Route::post('sliders/change/status', [\App\Http\Controllers\Backend\SliderController::class, 'change_status'])->name('sliders.change.status');
    Route::post('sliders/delete-selected', [\App\Http\Controllers\Backend\SliderController::class, 'delete_selected_items'])->name('sliders.delete-selected');

    #endregion


});
#endregion

#region carts
Route::resource('carts', \App\Http\Controllers\Backend\CartController::class)->except(['show']);
Route::post('carts/datatable', [\App\Http\Controllers\Backend\CartController::class, 'datatable'])->name('carts.datatable');

#endregion

#region orders
Route::resource('orders', \App\Http\Controllers\Backend\OrderController::class)->except('update');
Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
    Route::post('update/{id}', [\App\Http\Controllers\Backend\OrderController::class, 'update'])->name('update');
    Route::get('{id}/print', [\App\Http\Controllers\Backend\OrderController::class, 'print_pdf'])->name('print');
    Route::get('{id}/download', [\App\Http\Controllers\Backend\OrderController::class, 'download'])->name('download');
    Route::get('{id}/refund', [\App\Http\Controllers\Backend\OrderController::class, 'order_refund'])->name('refund');
    Route::get('{uuid}/cancel', [\App\Http\Controllers\Backend\OrderController::class, 'cancel'])->name('cancel');
    Route::post('datatable', [\App\Http\Controllers\Backend\OrderController::class, 'datatable'])->name('datatable');
    Route::post('{order}/change-status', [\App\Http\Controllers\Backend\OrderController::class, 'change_status'])->name('change.status');
    Route::post('{order}/make-order', [\App\Http\Controllers\Backend\OrderController::class, 'change_to_order'])->name('make-order');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\OrderController::class, 'delete_selected_items'])->name('delete-selected');
    Route::post('get/user/by/seller', [\App\Http\Controllers\Backend\OrderController::class, 'get_user_by_seller'])->name('get.user.by.seller');
    Route::post('get/address/by/user', [\App\Http\Controllers\Backend\OrderController::class, 'get_address_by_user'])->name('get.address.by.user');
    Route::post('get/price', [\App\Http\Controllers\Backend\OrderController::class, 'get_price'])->name('get.price');
    Route::post('apply/coupon', [\App\Http\Controllers\Backend\OrderController::class, 'apply_coupon'])->name('apply.coupon');
    Route::get('{id}/send/pdf', [\App\Http\Controllers\Backend\OrderController::class, 'send_pdf_to_user'])->name('send.pdf.user');
    Route::post('order/payment/approve', [\App\Http\Controllers\Backend\OrderController::class, 'order_payment_approve'])->name('order.payment.approve');
    Route::post('order/payment/show', [\App\Http\Controllers\Backend\OrderController::class, 'order_payment_show'])->name('order.payment.show');
    Route::post('order/payment/update', [\App\Http\Controllers\Backend\OrderController::class, 'order_payment_update'])->name('order.payment.update');
    Route::post('order/update/serial_numbers', [\App\Http\Controllers\Backend\OrderController::class, 'update_serial_numbers'])->name('order.update.serial.numbers');
    Route::post('get/shipping/cost' , [\App\Http\Controllers\Backend\OrderController::class , 'get_shipping_cost'])->name('get.shipping.cost');
});

#endregion

#region address
Route::resource('addresses', \App\Http\Controllers\Backend\AddressController::class)->except(['edit']);
Route::post('addresses/edit/{address}', [\App\Http\Controllers\Backend\AddressController::class, 'edit'])->name('addresses.edit');
Route::post('addresses/set-default/{address}', [\App\Http\Controllers\Backend\AddressController::class, 'set_default'])->name('addresses.set-default');
Route::post('addresses/edit/{address}/update', [\App\Http\Controllers\Backend\AddressController::class, 'update'])->name('addresses.update');
Route::post('addresses/create/', [\App\Http\Controllers\Backend\AddressController::class, 'create'])->name('addresses.create');

#endregion

#region ticket
Route::resource('tickets', \App\Http\Controllers\Backend\TicketController::class);
Route::post('tickets/datatable', [\App\Http\Controllers\Backend\TicketController::class, 'datatable'])->name('tickets.datatable');
Route::post('tickets/delete-selected', [\App\Http\Controllers\Backend\TicketController::class, 'delete_selected_items'])->name('tickets.delete-selected');

#region ticket replies
//Route::get('replies', [\App\Http\Controllers\Backend\TicketReplyController::class,'store'] );
Route::post('replies/{ticket_id}', [\App\Http\Controllers\Backend\TicketReplyController::class, 'store'])->name('replies.store');
Route::delete('replies/destroy/{ticket_id}', [\App\Http\Controllers\Backend\TicketReplyController::class, 'destroy'])->name('replies.destroy');

#endregion
#endregion

#region menu

#endregion

#region clear cash
Route::get('/esg-clear', function () {
    Artisan::call('cache:clear');
    echo '**ESG** cache:clear complete';
    echo "<br>";
    Artisan::call('config:clear');
    echo '**ESG** config:clear complete';
    echo "<br>";
    Artisan::call('route:clear');
    echo '**ESG** route:clear complete';
    echo "<br>";

    Artisan::call('view:clear');
    echo '**ESG** view:clear complete';
    echo "<br>";
    Artisan::call('config:cache');
    echo '**ESG** config:cache complete';
    echo "<br>";

});
#endregion

#region product
Route::resource('products', \App\Http\Controllers\Backend\ProductController::class, ['except' => 'show']);
Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
    Route::post('datatable', [\App\Http\Controllers\Backend\ProductController::class, 'datatable'])->name('datatable');
    Route::post('check/slug', [\App\Http\Controllers\Backend\ProductController::class, 'check_slug'])->name('check.slug');
    Route::post('check/sku', [\App\Http\Controllers\Backend\ProductController::class, 'check_sku'])->name('check.sku');
    Route::post('brand/get/models', [\App\Http\Controllers\Backend\ProductController::class, 'brands'])->name('brands');
    Route::post('select/get/product', [\App\Http\Controllers\Backend\ProductController::class, 'getProduct'])->name('get.product');
    Route::post('change/status', [\App\Http\Controllers\Backend\ProductController::class, 'change_status'])->name('change.status');
    Route::post('change/column', [\App\Http\Controllers\Backend\ProductController::class, 'change_value_column'])->name('change.column');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\ProductController::class, 'delete_selected_items'])->name('delete-selected');
    Route::get('out-of-stock', [\App\Http\Controllers\Backend\ProductController::class, 'show_out_of_stock'])->name('show_out_of_stock');
    Route::post('out-of-stock-datatable', [\App\Http\Controllers\Backend\ProductController::class, 'out_of_stock_datatable'])->name('out_of_stock_datatable');
    Route::get('out-of-stock/{product_id}', [\App\Http\Controllers\Backend\ProductController::class, 'product_requests'])->name('product_requests');
    Route::post('out-of-stock/{product_id}/requests_datatable', [\App\Http\Controllers\Backend\ProductController::class, 'products_requests_datatable'])->name('products_requests_datatable');
    Route::post('check_manufacturer_type', [\App\Http\Controllers\Backend\ProductController::class, 'check_manufacturer_type'])->name('check_manufacturer_type');

    Route::get('{id}/series/number', [\App\Http\Controllers\Backend\ProductController::class, 'series_number'])->name('series.number');
    Route::post('{id}/series/number/datatable', [\App\Http\Controllers\Backend\ProductController::class, 'series_number_datatable'])->name('series.number.datatable');
    Route::get('import', [\App\Http\Controllers\Backend\ProductController::class, 'import_from_excel'])->name('import');
    Route::post('import/upload', [\App\Http\Controllers\Backend\ProductController::class, 'upload_excel'])->name('import.upload');
    Route::post('get-category-type', [\App\Http\Controllers\Backend\ProductController::class, 'get_category_type'])->name('get-category-type');
    Route::group(['prefix' => 'attribute', 'as' => 'attribute.'], function () {
        Route::post('create', [\App\Http\Controllers\Backend\ProductController::class, 'create_new_attribute'])->name('create');
        Route::post('sub/create', [\App\Http\Controllers\Backend\ProductController::class, 'create_new_sub_attribute'])->name('sub.create');
        Route::post('sub/store', [\App\Http\Controllers\Backend\ProductController::class, 'store_new_sub_attribute'])->name('sub.store');
    });
    #region product
    Route::post('get-quantity', [\App\Http\Controllers\Backend\ProductController::class, 'get_quantity'])->name('get-quantity');
    Route::post('calculate-shipping-cost', [\App\Http\Controllers\Backend\ProductController::class, 'calculate_shipping_cost'])->name('calculate-shipping-cost');
#endregion

});
#endregion

#region downloads
Route::resource('downloads', App\Http\Controllers\Backend\DownloadController::class, ['except' => 'show']);
Route::post('downloads/check_slug', [App\Http\Controllers\Backend\DownloadController::class, 'check_slug'])->name('downloads.check_slug');
Route::post('downloads/datatable', [App\Http\Controllers\Backend\DownloadController::class, 'datatable'])->name('downloads.datatable');
Route::post('downloads/change/status', [App\Http\Controllers\Backend\DownloadController::class, 'change_status'])->name('downloads.change.status');
Route::post('downloads/delete-selected', [\App\Http\Controllers\Backend\DownloadController::class, 'delete_selected_items'])->name('downloads.delete-selected');

#endregion

#region zones
Route::resource('zones', \App\Http\Controllers\Backend\ZonePriceController::class, ['except' => 'show']);

Route::post('zones/datatable', [\App\Http\Controllers\Backend\ZonePriceController::class, 'datatable'])->name('zones.datatable');
Route::post('zones/{zone}/edit', [\App\Http\Controllers\Backend\ZonePriceController::class, 'edit'])->name('zones.edit');
#endregion

#region manufacturers
Route::resource('manufacturers', \App\Http\Controllers\Backend\ManufacturerController::class, ['except' => 'show']);
Route::group(['prefix' => 'manufacturers', 'as' => 'manufacturers.'], function () {
    Route::post('datatable', [\App\Http\Controllers\Backend\ManufacturerController::class, 'datatable'])->name('datatable');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\ManufacturerController::class, 'delete_selected_items'])->name('delete-selected');
    Route::post('change/status', [App\Http\Controllers\Backend\ManufacturerController::class, 'change_status'])->name('change.status');


});
#endregion

#region sellers
Route::resource('sellers', \App\Http\Controllers\Backend\SellerController::class, ['except' => 'show']);
Route::group(['as' => 'sellers.', 'prefix' => 'sellers'], function () {
    Route::post('datatable', [App\Http\Controllers\Backend\SellerController::class, 'datatable'])->name('datatable');
    Route::get('{seller}/orders', [App\Http\Controllers\Backend\SellerController::class, 'orders'])->name('orders');
    Route::post('{seller}/orders-datatable', [App\Http\Controllers\Backend\SellerController::class, 'orders_datatable'])->name('orders.datatable');
    Route::post('{seller}/edit', [App\Http\Controllers\Backend\SellerController::class, 'edit'])->name('edit');
    Route::get('{seller}/wallet', [App\Http\Controllers\Backend\SellerController::class, 'wallet'])->name('wallet');
    Route::post('{seller}/wallet_datatable', [App\Http\Controllers\Backend\SellerController::class, 'wallet_datatable'])->name('wallet_datatable');
    Route::post('change/status', [App\Http\Controllers\Backend\SellerController::class, 'change_status'])->name('change.status');
    Route::post('change/column', [App\Http\Controllers\Backend\SellerController::class, 'change_value_column'])->name('change.column');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\SellerController::class, 'delete_selected_items'])->name('delete-selected');

    Route::get('earning', [\App\Http\Controllers\Backend\SellerEarningController::class, 'index'])->name('earning');
    Route::post('earning', [\App\Http\Controllers\Backend\SellerEarningController::class, 'datatable'])->name('sellers-earning.datatable');
    Route::group(['as' => 'commission.', 'prefix' => 'commission'], function () {
        Route::get('{id}', [\App\Http\Controllers\Backend\SellerCommissionController::class, 'index'])->name('get');
        Route::post('datatable/{id}', [App\Http\Controllers\Backend\SellerCommissionController::class, 'datatable'])->name('datatable');
        Route::post('store', [\App\Http\Controllers\Backend\SellerCommissionController::class, 'store'])->name('store');
        Route::delete('destroy/{id}', [\App\Http\Controllers\Backend\SellerCommissionController::class, 'destroy'])->name('destroy');
    });
});
#endregion

#region currency
Route::group(['prefix' => 'currencies', 'as' => 'currencies.'], function () {
    Route::resource('/', \App\Http\Controllers\Backend\CurrencyController::class)->except('edit');
    Route::get('/{currency}/edit', [\App\Http\Controllers\Backend\CurrencyController::class, 'edit'])->name('edit_cur');
    Route::patch('/{currency}/update', [\App\Http\Controllers\Backend\CurrencyController::class, 'update'])->name('update_cur');
    Route::delete('/{currency}/destroy', [\App\Http\Controllers\Backend\CurrencyController::class, 'destroy'])->name('destroy_cur');
    Route::post('/datatable', [\App\Http\Controllers\Backend\CurrencyController::class, 'datatable'])->name('datatable');
    Route::post('/change/status', [\App\Http\Controllers\Backend\CurrencyController::class, 'change_status'])->name('change.status');
    Route::post('/change-default', [\App\Http\Controllers\Backend\CurrencyController::class, 'change_default'])->name('change-default');
    Route::post('/delete-selected', [\App\Http\Controllers\Backend\CurrencyController::class, 'delete_selected_items'])->name('delete-selected');
});
#endregion

#region statistics

Route::group(['as' => 'statistics.', 'prefix' => 'statistics'], function () {
    Route::get('', [\App\Http\Controllers\StatisticsController::class, 'index'])->name('index');
    Route::get('/total-sales', [\App\Http\Controllers\StatisticsController::class, 'total_sales'])->name('total-sales');
    Route::get('/order_count', [\App\Http\Controllers\StatisticsController::class, 'order_count'])->name('order_count');
    Route::get('/shipping', [\App\Http\Controllers\StatisticsController::class, 'shipping'])->name('shipping');
    Route::get('/coupons', [\App\Http\Controllers\StatisticsController::class, 'coupons'])->name('coupons');
    Route::get('/users', [\App\Http\Controllers\StatisticsController::class, 'users'])->name('users');
    Route::get('/user_countries', [\App\Http\Controllers\StatisticsController::class, 'user_countries'])->name('user_countries');
    Route::get('/traffic_source', [\App\Http\Controllers\StatisticsController::class, 'traffic_source'])->name('traffic_source');
    Route::get('/device_category', [\App\Http\Controllers\StatisticsController::class, 'device_category'])->name('device_category');
    Route::get('/operating_system', [\App\Http\Controllers\StatisticsController::class, 'operating_system'])->name('operating_system');
    Route::get('/visits', [\App\Http\Controllers\StatisticsController::class, 'website_visits'])->name('website_visits');
    Route::get('/most-visited-pages', [\App\Http\Controllers\StatisticsController::class, 'pages_view'])->name('most-visited-pages');
    Route::get('/top-selling-products', [\App\Http\Controllers\StatisticsController::class, 'top_selling_products'])->name('top-selling-products');
    Route::get('/top-selling-categories', [\App\Http\Controllers\StatisticsController::class, 'top_selling_categories'])->name('top-selling-categories');
    Route::get('/users_orders', [\App\Http\Controllers\StatisticsController::class, 'users_orders'])->name('users_orders');
    Route::post('/users_orders_datatable', [\App\Http\Controllers\StatisticsController::class, 'users_orders_datatable'])->name('users_orders_datatable');
    Route::get('/google-analytics', [\App\Http\Controllers\StatisticsController::class, 'googleAnalytics'])->name('google_analytics');
    Route::get('/net-revenue', [\App\Http\Controllers\StatisticsController::class, 'net_revenue'])->name('net_revenue');
    Route::get('/show-categories-chart', [\App\Http\Controllers\StatisticsController::class, 'show_categories_chart'])->name('show_categories_chart');
    Route::get('/show-products-chart', [\App\Http\Controllers\StatisticsController::class, 'get_product_chart'])->name('show_products_chart');
    Route::get('/show-stock-chart', [\App\Http\Controllers\StatisticsController::class, 'stock_status'])->name('show_stock_chart');

});

#endregion

#region redirects

Route::group(['as' => 'redirects.', 'prefix' => 'redirects'], function () {
    Route::resource('/', \App\Http\Controllers\Backend\UrlRedirectController::class)->except(['edit', 'update']);
    Route::post('{redirect}/edit', [App\Http\Controllers\Backend\UrlRedirectController::class, 'edit'])->name('edit');
    Route::patch('{redirect}/update', [App\Http\Controllers\Backend\UrlRedirectController::class, 'update'])->name('update');
    Route::post('datatable', [App\Http\Controllers\Backend\UrlRedirectController::class, 'datatable'])->name('datatable');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\UrlRedirectController::class, 'delete_selected_items'])->name('delete-selected');
    Route::delete('destroy/{id}', [\App\Http\Controllers\Backend\UrlRedirectController::class, 'destroy'])->name('destroy');

});
#endregion

#region offers
Route::group(['as' => 'offers.', 'prefix' => 'offers'], function () {
    Route::resource('/', \App\Http\Controllers\Backend\OfferController::class)->except(['show', 'edit']);
    Route::post('datatable', [App\Http\Controllers\Backend\OfferController::class, 'datatable'])->name('datatable');
    Route::post('delete-selected', [\App\Http\Controllers\Backend\OfferController::class, 'delete_selected_items'])->name('delete-selected');
    Route::post('/change/status', [\App\Http\Controllers\Backend\OfferController::class, 'change_status'])->name('change.status');
    Route::post('/check_values', [\App\Http\Controllers\Backend\OfferController::class, 'check_values'])->name('check_values');
    Route::get('/{offer}/edit', [App\Http\Controllers\Backend\OfferController::class, 'edit'])->name('edit');
    Route::patch('/{offer}/update', [App\Http\Controllers\Backend\OfferController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [\App\Http\Controllers\Backend\OfferController::class, 'destroy'])->name('destroy');

});
#endregion

#region what new
Route::group(['as' => 'whatsnew.', 'prefix' => 'whatnew'], function () {
    Route::get('/', [\App\Http\Controllers\Backend\WhatNewController::class, 'index'])->name('index');
    Route::post('/datatable', [\App\Http\Controllers\Backend\WhatNewController::class, 'datatable'])->name('datatable');
    Route::get('/create', [\App\Http\Controllers\Backend\WhatNewController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\Backend\WhatNewController::class, 'store'])->name('store');
    Route::post('/get-users', [\App\Http\Controllers\Backend\WhatNewController::class, 'get_users'])->name('get-users');
    Route::get('/{message_id}/show-users', [\App\Http\Controllers\Backend\WhatNewController::class, 'show_users'])->name('show-users');
    Route::post('/users-datatable', [\App\Http\Controllers\Backend\WhatNewController::class, 'user_datatable'])->name('user_datatable');
});
#endregion

#region notification
Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/', [\App\Http\Controllers\Backend\NotificationController::class, 'index'])->name('index');
    Route::post('/datatable', [\App\Http\Controllers\Backend\NotificationController::class, 'datatable'])->name('datatable');
    Route::post('/read', [\App\Http\Controllers\Backend\NotificationController::class, 'read'])->name('read');
    Route::post('/read_all', [\App\Http\Controllers\Backend\NotificationController::class, 'read_all'])->name('read_all');
});
#endregion

#region user wallet
Route::group(['as' => 'user_wallet.', 'prefix' => 'user_wallet'], function () {
    Route::get('/', [\App\Http\Controllers\Backend\UserWalletController::class, 'index'])->name('index');
    Route::post('datatable', [\App\Http\Controllers\Backend\UserWalletController::class, 'datatable'])->name('datatable');
});
#endregion

#region contact us
Route::group(['as' => 'contact_us.', 'prefix' => 'contact_us'], function () {
    Route::get('/', [\App\Http\Controllers\Backend\ContactUsController::class, 'index'])->name('index');
    Route::post('datatable', [\App\Http\Controllers\Backend\ContactUsController::class, 'datatable'])->name('datatable');
});
#endregion
Route::get('fix/slug',[\App\Http\Controllers\Backend\DashboardController::class, 'fixSlug']);
