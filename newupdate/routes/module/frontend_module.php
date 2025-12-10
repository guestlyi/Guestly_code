<?php

use App\Http\Controllers\Frontend\Module\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

$basicControl = basicControl();

Route::group(['middleware' => ['maintenanceMode']], function () use ($basicControl) {
    Route::controller(ServiceController::class)->group(function () {
        Route::group(['prefix' => 'service'], function () {
            Route::get('details/{slug}', 'details')->name('service.details');
        });
    });


});
