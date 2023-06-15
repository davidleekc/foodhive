<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'shipments', 'as' => 'ecommerce.shipments.'], function () {
            Route::resource('', 'ShipmentController')
                ->parameters(['' => 'shipment'])
                ->except(['create', 'store']);

            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'ShipmentController@deletes',
                'permission' => 'ecommerce.shipments.destroy',
            ]);

            Route::post('update-status/{id}', [
                'as' => 'update-status',
                'uses' => 'ShipmentController@postUpdateStatus',
                'permission' => 'ecommerce.shipments.edit',
            ])->where('id', BaseHelper::routeIdRegex());

            Route::post('update-cod-status/{id}', [
                'as' => 'update-cod-status',
                'uses' => 'ShipmentController@postUpdateCodStatus',
                'permission' => 'ecommerce.shipments.edit',
            ])->where('id', BaseHelper::routeIdRegex());
        });
    });
});
