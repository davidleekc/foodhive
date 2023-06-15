<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('wishlist', [
            'as' => 'public.wishlist',
            'uses' => 'WishlistController@index',
        ]);

        Route::post('wishlist/{productId}', [
            'as' => 'public.wishlist.add',
            'uses' => 'WishlistController@store',
        ])->where('productId', BaseHelper::routeIdRegex());

        Route::delete('wishlist/{productId}', [
            'as' => 'public.wishlist.remove',
            'uses' => 'WishlistController@destroy',
        ])->where('productId', BaseHelper::routeIdRegex());
    });
});
