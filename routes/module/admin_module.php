<?php

use App\Http\Controllers\Admin\BadgeController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\Module\AmenitiesController;
use App\Http\Controllers\Admin\Module\DestinationController;
use App\Http\Controllers\Admin\Module\PropertyCategoryController;
use App\Http\Controllers\Admin\Module\PropertyStyleController;
use App\Http\Controllers\Admin\Module\PropertyTypeController;
use App\Http\Controllers\Admin\ReportAndFeedbackController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\Module\PropertyController;
use Illuminate\Support\Facades\Route;

$adminPrefix = basicControl()->admin_prefix ?? 'admin';
Route::group(['prefix' => $adminPrefix, 'as' => 'admin.'], function () {
    Route::middleware(['auth:admin','demo'])->group(function () {
        Route::controller(PropertyCategoryController::class)->group(function () {
            Route::group(['prefix' => 'property/category'], function () {
                Route::get('list', 'list')->name('property.categoryList');
                Route::get('list-search', 'listSearch')->name('property.categoryList.search');
                Route::get('create', 'add')->name('property.categoryAdd');
                Route::post('store', 'store')->name('property.categoryStore');
                Route::post('status-update/{id}', 'status')->name('property.categoryStatus');
                Route::any('status-update-multiple', 'statusMultiple')->name('category.status.multiple');
                Route::get('edit/{id}', 'edit')->name('property.categoryEdit');
                Route::post('update', 'update')->name('property.categoryUpdate');
                Route::any('delete/{id}', 'delete')->name('property.categoryDelete');
                Route::post('delete', 'deleteMultiple')->name('property.categoryDelete.multiple');
            });
        });

        Route::controller(CurrencyController::class)->group(function () {
            Route::group(['prefix' => 'currency'], function () {
                Route::get('list', 'currencyList')->name('currencyList');
                Route::get('list/search', 'currencyListSearch')->name('currencyListSearch');
                Route::post('create', 'currencyCreate')->name('currencyCreate');
                Route::post('edit/{id}', 'currencyEdit')->name('currencyEdit');
                Route::post('sort', 'currencySort')->name('currencySort');
                Route::get('status-change', 'currencyStatusChange')->name('currencyStatusChange');
                Route::delete('delete/{id}', 'currencyDelete')->name('currencyDelete');
                Route::post('multiple-delete', 'multipleDelete')->name('currencyMultipleDelete');
                Route::post('multiple/status-change', 'multipleStatusChange')->name('currencyMultipleStatusChange');
                Route::post('multiple/rate-update', 'multipleRateUpdate')->name('currencyMultipleRateUpdate');
            });
        });

        Route::controller(CountryController::class)->group(function () {
            Route::group(['prefix' => 'country'], function () {
                Route::get('list', 'list')->name('all.country');
                Route::get('list-search', 'countryList')->name('country.list');
                Route::get('add', 'countryAdd')->name('country.add');
                Route::post('store', 'countryStore')->name('country.store');
                Route::get('edit/{id}', 'countryEdit')->name('country.edit');
                Route::post('update/{id}', 'countryUpdate')->name('country.update');
                Route::post('delete-multiple', 'deleteMultiple')->name('country.delete.multiple');
                Route::delete('delete/{id}', 'countryDelete')->name('country.delete');
                Route::get('status-change/{id}', 'status')->name('country.status');
                Route::any('status-multiple', 'inactiveMultiple')->name('country.inactiveMultiple');
                Route::match(['get', 'post'],'fetch-country', 'fetchCountry')->name('fetch.country');
            });
        });

        Route::controller(StateController::class)->group(function () {
            Route::get('country/{id}/all-states', 'statelist')->name('country.all.state');
            Route::get('country/{id}/state-list', 'countryStateList')->name('country.state.list');
            Route::get('country/{country}/state/{state}/edit', 'countryStateEdit')->name('country.state.edit');
            Route::post('country/{country}/state/{state}/update', 'countryStateUpdate')->name('country.state.update');
            Route::delete('country/{country}/state/{state}/delete', 'countryStateDelete')->name('country.state.delete');
            Route::post('country-state-delete-multiple', 'deleteMultipleState')->name('country.delete.multiple.state');
            Route::get('country/{country}/add-state', 'countryAddState')->name('country.add.state');
            Route::post('country/store-state', 'countryStateStore')->name('country.state.store');
            Route::get('state/{state}/status', 'status')->name('country.state.status');
            Route::any('state/inactive-multiple', 'inactiveMultiple')->name('state.inactiveMultiple');
            Route::match(['get', 'post'],'fetch-state-list', 'fetchStateList')->name('fetch.state.list');
        });

        Route::controller(CityController::class)->group(function () {
            Route::get('country/{country}/state/{state}/all-cities', 'citylist')->name('country.state.all.city');
            Route::get('country/{country}/state/{state}/city-list', 'countryStateCityList')->name('country.state.city.list');
            Route::get('country/{country}/state/{state}/city/{city}/edit', 'countryStateCityEdit')->name('country.state.city.edit');
            Route::post('country/{country}/state/{state}/city/{city}/update', 'countryStateCityUpdate')->name('country.state.city.update');
            Route::get('country/{country}/state/{state}/city/{city}/delete', 'countryStateCityDelete')->name('country.state.city.delete');
            Route::post('country-state-delete-city-multiple', 'deleteMultipleStateCity')->name('country.delete.multiple.state.city');
            Route::get('country/{country}/state/{state}/add-city', 'countryStateAddCity')->name('country.state.add.city');
            Route::post('country/state/store-city', 'countryStateStoreCity')->name('country.state.store.city');
            Route::get('city/{city}/status', 'status')->name('country.state.city.status');
            Route::any('city/inactive-multiple', 'inactiveMultiple')->name('city.inactiveMultiple');
            Route::match(['get', 'post'],'fetch-city-list', 'fetchCityList')->name('fetch.city.list');
        });

        Route::controller(SubscriberController::class)->group(function () {
            Route::group(['prefix' => 'subscriber'], function () {
                Route::get('send-email-form/{id?}', 'sendEmailForm')->name('send.subscriber.email');
                Route::post('send-email/{id?}', 'sendMailUser')->name('subscriber.email.send');
                Route::get('list', 'list')->name('subscriber.list');
                Route::get('search-list', 'searchList')->name('subscriber.search.list');
                Route::post('delete-multiple', 'deleteMultiple')->name('subscriber.delete.multiple');
                Route::get('delete/{id}', 'delete')->name('subscriber.delete');
            });
        });

        Route::controller(PropertyController::class)->group(function () {
            Route::group(['prefix' => 'property'], function () {
                Route::get('list', 'list')->name('all.property');
                Route::get('list-search', 'listSearch')->name('all.property.search');
                Route::get('edit/{id}', 'edit')->name('property.edit');
                Route::post('update', 'update')->name('property.update');
                Route::post('status-update/{id}', 'status')->name('property.status');
                Route::delete('delete/{id}', 'delete')->name('property.delete');
                Route::post('action', 'action')->name('property.action');
                Route::post('delete-multiple', 'deleteMultiple')->name('property.delete.multiple');
                Route::get('seo/{id}', 'propertySeo')->name('property.seo');
                Route::post('seo-update', 'propertySeoUpdate')->name('property.seo.update');
            });
        });

        Route::controller(AmenitiesController::class)->group(function () {
            Route::group(['prefix' => 'amenity'], function () {
                Route::get('list', 'list')->name('all.amenity');
                Route::get('list-search', 'listSearch')->name('all.amenity.search');
                Route::get('create', 'add')->name('amenity.create');
                Route::post('store', 'store')->name('amenity.store');
                Route::get('edit/{id}', 'edit')->name('amenity.edit');
                Route::post('update', 'update')->name('amenity.update');
                Route::post('status/{id}', 'status')->name('amenity.status');
                Route::delete('delete/{id}', 'delete')->name('amenity.delete');
                Route::any('status-multiple', 'statusMultiple')->name('amenity.statusMultiple');
                Route::any('delete-multiple', 'deleteMultiple')->name('amenity.deleteMultiple');
            });
        });

        Route::controller(PropertyTypeController::class)->group(function () {
            Route::group(['prefix' => 'property-type'], function () {
                Route::get('list', 'list')->name('all.propertyType');
                Route::get('list-search', 'listSearch')->name('all.propertyType.search');
                Route::get('create', 'add')->name('propertyType.create');
                Route::post('store', 'store')->name('propertyType.store');
                Route::get('edit/{id}', 'edit')->name('propertyType.edit');
                Route::post('update', 'update')->name('propertyType.update');
                Route::post('status/{id}', 'status')->name('propertyType.status');
                Route::delete('delete/{id}', 'delete')->name('propertyType.delete');
                Route::any('status-multiple', 'statusMultiple')->name('propertyType.statusMultiple');
                Route::any('delete-multiple', 'deleteMultiple')->name('propertyType.deleteMultiple');
            });
        });

        Route::controller(PropertyStyleController::class)->group(function () {
            Route::group(['prefix' => 'property-style'], function () {
                Route::get('list', 'list')->name('all.propertyStyle');
                Route::get('list-search', 'listSearch')->name('all.propertyStyle.search');
                Route::get('create', 'add')->name('propertyStyle.create');
                Route::post('store', 'store')->name('propertyStyle.store');
                Route::get('edit/{id}', 'edit')->name('propertyStyle.edit');
                Route::post('update', 'update')->name('propertyStyle.update');
                Route::post('status/{id}', 'status')->name('propertyStyle.status');
                Route::delete('delete/{id}', 'delete')->name('propertyStyle.delete');
                Route::any('status-multiple', 'statusMultiple')->name('propertyStyle.statusMultiple');
                Route::any('delete-multiple', 'deleteMultiple')->name('propertyStyle.deleteMultiple');
            });
        });

        Route::controller(DestinationController::class)->group(function () {
            Route::group(['prefix' => 'destination'], function () {
                Route::get('list', 'list')->name('all.destination');
                Route::get('list-search', 'search')->name('all.destination.search');
                Route::get('create', 'add')->name('destination.add');
                Route::post('store', 'store')->name('destination.store');
                Route::get('edit/{id}', 'edit')->name('destination.edit');
                Route::post('update/{id}', 'update')->name('destination.update');
                Route::get('status/{id}', 'status')->name('destination.status');
                Route::delete('delete/{id}', 'delete')->name('destination.delete');
                Route::any('status-multiple', 'statusMultiple')->name('destination.statusMultiple');
                Route::any('delete-multiple', 'deleteMultiple')->name('destination.delete.multiple');
                Route::post('states', 'fetchState')->name('fetch.state');
                Route::post('cities', 'fetchCity')->name('fetch.city');
            });
        });

        Route::controller(BadgeController::class)->group(function () {
            Route::get('badge/list', 'list')->name('badge.list');
            Route::get('badge/list-search', 'search')->name('badge.search');
            Route::get('badge/create', 'create')->name('badge.create');
            Route::post('badge/store', 'store')->name('badge.store');
            Route::get('badge/edit/{id}', 'edit')->name('badge.edit');
            Route::post('badge/update', 'update')->name('badge.update');
            Route::delete('badge/delete/{id}', 'delete')->name('badge.delete');
            Route::post('badge/delete-multiple', 'deleteMultiple')->name('badge.delete.multiple');
            Route::post('badge/status-multiple', 'statusMultiple')->name('badge.status.multiple');
            Route::get('badge/sort', 'badgeSort')->name('badge.short');
        });

        Route::controller(BookingController::class)->group(function () {
            Route::get('all-booking/{status?}', 'all_booking')->name('all.booking');
            Route::get('all-booking-search', 'all_booking_search')->name('all.booking.search');
            Route::post('booking/completed/{id}', 'complete')->name('booking.action');
            Route::any('booking/approve/{id}', 'approve')->name('booking.approve');
            Route::get('booking-edit/{id}', 'bookingEdit')->name('booking.edit');
            Route::post('booking-edit/traveller-update', 'travellerUpdate')->name('traveller.update');
            Route::get('booking-refund/{id}', 'bookingRefund')->name('booking.refund');
            Route::post('booking-update', 'bookingUpdate')->name('booking.update');
            Route::post('booking/user-info-update', 'bookingUserInfoUpdate')->name('booking.user.info.update');
            Route::any('booking/refund-multiple', 'refundMultiple')->name('booking.refund.multiple');
            Route::any('booking/completed-multiple', 'completedMultiple')->name('booking.completed.multiple');
        });

        Route::controller(ReportAndFeedbackController::class)->group(function () {
            Route::get('reports', 'report')->name('report');
            Route::get('report-search', 'reportSearch')->name('report.search');
            Route::delete('report/delete/{id}', 'reportDelete')->name('report.delete');
            Route::any('report/delete-multiple', 'reportDeleteMultiple')->name('report.delete.multiple');

            Route::get('feedbacks', 'feedback')->name('feedback');
            Route::get('feedback-search', 'feedbackSearch')->name('feedback.search');
            Route::delete('feedback/delete/{id}', 'feedbackDelete')->name('feedback.delete');
            Route::any('feedback/delete-multiple', 'feedbackDeleteMultiple')->name('feedback.delete.multiple');
        });

        Route::controller(ReviewController::class)->group(function () {
            Route::group(['prefix' => 'review', 'as' => 'review.'], function () {
                Route::get('list', 'list')->name('list');
                Route::get('list/search', 'search')->name('search');
                Route::post('multiple-delete', 'multipleDelete')->name('multipleDelete');
                Route::post('multiple/status-change', 'multipleStatusChange')->name('multipleStatusChange');
                Route::post('ajax/toggle-change/{id}', 'toggleStatus')->name('toggle.status');
            });
        });
    });
});
