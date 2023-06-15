<?php

Route::group(['namespace' => 'Botble\Marketplace\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'marketplaces', 'as' => 'marketplace.'], function () {
            Route::group(['prefix' => 'stores', 'as' => 'store.'], function () {
                Route::resource('', 'StoreController')->parameters(['' => 'store']);
                Route::delete('items/destroy', [
                    'as' => 'deletes',
                    'uses' => 'StoreController@deletes',
                    'permission' => 'marketplace.store.destroy',
                ]);

                Route::get('view/{id}', [
                    'as' => 'view',
                    'uses' => 'StoreRevenueController@view',
                ])->where('id', BaseHelper::routeIdRegex());

                Route::group(['prefix' => 'revenues', 'as' => 'revenue.'], function () {
                    Route::match(['GET', 'POST'], 'list/{id}', [
                        'as' => 'index',
                        'uses' => 'StoreRevenueController@index',
                        'permission' => 'marketplace.store.view',
                    ])->where('id', BaseHelper::routeIdRegex());

                    Route::post('create/{id}', [
                        'as' => 'create',
                        'uses' => 'StoreRevenueController@store',
                    ])->where('id', BaseHelper::routeIdRegex());
                });
            });

            Route::group(['prefix' => 'withdrawals', 'as' => 'withdrawal.'], function () {
                Route::resource('', 'WithdrawalController')
                    ->parameters(['' => 'withdrawal'])
                    ->except([
                        'create',
                        'store',
                        'destroy',
                    ]);
            });

            Route::get('settings', [
                'as' => 'settings',
                'uses' => 'MarketplaceController@getSettings',
            ]);

            Route::post('settings', [
                'as' => 'settings.post',
                'uses' => 'MarketplaceController@postSettings',
                'permission' => 'marketplace.settings',
            ]);

            Route::group(['prefix' => 'unverified-vendors', 'as' => 'unverified-vendors.'], function () {
                Route::match(['GET', 'POST'], '/', [
                    'as' => 'index',
                    'uses' => 'UnverifiedVendorController@index',
                ]);

                Route::get('view/{id}', [
                    'as' => 'view',
                    'uses' => 'UnverifiedVendorController@view',
                    'permission' => 'marketplace.unverified-vendors.edit',
                ])->where('id', BaseHelper::routeIdRegex());

                Route::post('approve/{id}', [
                    'as' => 'approve-vendor',
                    'uses' => 'UnverifiedVendorController@approveVendor',
                    'permission' => 'marketplace.unverified-vendors.edit',
                ])->where('id', BaseHelper::routeIdRegex());
            });

            Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/marketplaces'], function () {
                Route::group(['prefix' => 'vendors', 'as' => 'vendors.'], function () {
                    Route::match(['GET', 'POST'], '/', [
                        'as' => 'index',
                        'uses' => 'VendorController@index',
                    ]);
                });
            });
        });

        Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/ecommerce'], function () {
            Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
                Route::post('approve-product/{id}', [
                    'as' => 'approve-product',
                    'uses' => 'ProductController@approveProduct',
                    'permission' => 'products.edit',
                ])->where('id', BaseHelper::routeIdRegex());
            });
        });
    });
});
