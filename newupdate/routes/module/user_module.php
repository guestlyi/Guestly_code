<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Module\BookingController;
use App\Http\Controllers\ReportAndFeedbackController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\Module\TaxController;
use App\Http\Controllers\User\ChattingController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\HostingController;
use App\Http\Controllers\User\Module\ServiceController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PayoutController;
use App\Http\Controllers\User\PropertyController;
use App\Http\Controllers\User\RelativesController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\SeoController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Route;

$basicControl = basicControl();

Route::group(['middleware' => ['maintenanceMode']], function () use ($basicControl) {
    Route::group(['middleware' => ['auth', 'userCheck', 'kyc'], 'prefix' => 'user', 'as' => 'user.'], function () {
        Route::controller(ServiceController::class)->group(function () {
            Route::get('service/list', 'list')->name('property.list');
            Route::get('service/create-intro', 'create')->name('service.create');
            Route::get('service/create-type', 'type')->name('service.type');
            Route::get('service/create-map', 'map')->name('service.map');
            Route::get('service/get-incomplete', 'incompleted')->name('property.incomplete.list');
            Route::delete('service/delete/{id}', 'delete')->name('property.delete');
        });
        Route::get('seo-index', [SeoController::class, 'index'])->name('seo.index');
        Route::post('seo-update', [SeoController::class, 'update'])->name('seo.update');

        Route::controller(HomeController::class)->group(function () {
            Route::get('calender', 'calender')->name('calender');
            Route::get('fetch-calender/booking/{id}', 'calenderBooking')->name('calender.booking');
            Route::get('messages', 'messages')->name('messages');
            Route::get('reservations', 'reservations')->name('reservations');
            Route::get('earnings', 'earnings')->name('earnings');

            Route::get('reservation-fetch', 'reservationDataFetch')->name('reservations.fetch');
            Route::get('booking-chart', 'chartDataFetch')->name('booking.chart.fetch');
            Route::get('host-dash-transaction', 'hostDashTransaction')->name('host.dash.transaction');
        });

        Route::controller(HostingController::class)->group(function () {
            Route::get('enter-home', 'enterHome')->name('enter.home');
            Route::get('introduction', 'introduction')->name('listing.introduction.setup');
        });

        Route::prefix('listing')
            ->name('listing.')
            ->controller(PropertyController::class)
            ->middleware('check.user.role')
            ->group(function () {
                Route::get('introduction', 'introduction')->name('introduction');
                Route::get('introduction/guide', 'introductionGuide')->name('introduction.guide');
                Route::get('about-your-place', 'aboutYourPlace')->name('about.your.place');
                Route::get('structure', 'structure')->name('structure');
                Route::post('structure/save', 'structureSave')->name('structure.save');
                Route::get('types', 'types')->name('types');
                Route::post('type/save', 'typeSave')->name('type.save');
                Route::get('styles', 'styles')->name('styles');
                Route::post('style/save', 'styleSave')->name('style.save');
                Route::get( 'maps', 'maps')->name('maps');
                Route::post('map/save', 'mapSave')->name('map.save');
                Route::get('location', 'location')->name('location');
                Route::post('location/save', 'locationSave')->name('location.save');
                Route::get('nearby', 'nearby')->name('nearby');
                Route::post('nearby/save', 'nearbySave')->name('nearby.save');
                Route::get('informations', 'informations')->name('informations');
                Route::post('information/save', 'informationSave')->name('information.save');
                Route::get('availability-and-features', 'availablityAndFeatures')->name('availablityAndFeatures');
                Route::post('availability-and-feature/save', 'availablityAndFeatureSave')->name('availablityAndFeature.save');
                Route::get('stand-out', 'standOut')->name('stand.out');
                Route::get('amenities', 'amenities')->name('amenities');
                Route::post('amenities/save', 'amenitiesSave')->name('amenities.save');
                Route::get('photos', 'photos')->name('photos');
                Route::post('photos/save', 'photosSave')->name('photos.save');
                Route::get('title', 'title')->name('title');
                Route::post('title/save', 'titleSave')->name('title.save');
                Route::get('description', 'description')->name('description');
                Route::post('description/save', 'descriptionSave')->name('description.save');
                Route::get('finishing-setup', 'finishingSetup')->name('finishing.setup');
                Route::get('pricing', 'pricing')->name('pricing');
                Route::post('pricing/save', 'pricingSave')->name('pricing.save');
                Route::get('discounts', 'discounts')->name('discounts');
                Route::post('discounts/save', 'discountsSave')->name('discounts.save');
                Route::get('safety', 'safety')->name('safety');
                Route::post('safety/save', 'safetySave')->name('safety.save');
                Route::get('rules', 'rules')->name('rules');
                Route::post('rules/save', 'rulesSave')->name('rules.save');
                Route::get('finish', 'finish')->name('finish');
                Route::any('generate-with-ai', 'generate')->name('ai.generate');
                Route::any('generate-with-ai/image', 'generateImage')->name('ai.generate.image');

                Route::get('ical-get/{id}', 'icalGet')->name('ical.get');
                Route::post('ical-update', 'icalUpdate')->name('ical.update');
            });

        Route::post('wishlist', [FrontendController::class, 'wishlist'])->name('wishlist');
        Route::get('notification-permission/list', [NotificationController::class, 'index'])->name('notification.permission.list');
        Route::post('notification-perission/update', [NotificationController::class, 'notificationSettingsChanges'])->name('notification.permission');

        Route::controller(TaxController::class)->group(function () {
            Route::get('tax/list', 'list')->name('tax.list');
            Route::post('tax/store', 'store')->name('tax.store');
            Route::put('tax/update', 'update')->name('tax.update');
            Route::delete('tax/delete', 'delete')->name('tax.delete');
        });

        Route::controller(BookingController::class)->group(function () {
            Route::post('booking-confirm', 'bookingInfoStore')->name('booking.info.store');
            Route::get('booking/{uid}/guest-info', 'bookingGuestInfo')->name('booking.guest.info');
            Route::post('booking/{uid}/update', 'bookingUpdate')->name('booking.update');
            Route::get('booking/payment/{uid}', 'bookingPaymentInfo')->name('booking.payment.info');
            Route::post('booking/user-info/update/{uid}', 'bookingUserInfoUpdate')->name('booking.userInfo.update');
            Route::post('booking/payment', 'bookingPayment')->name('booking.payment');
        });

        Route::controller(UserBookingController::class)->group(function () {
            Route::post('booking-info/store', 'confirm')->name('booking.confirm');
            Route::post('booking-info/completed', 'completed')->name('booking.completed');
            Route::post('booking-info/refunded', 'refunded')->name('booking.refunded');
        });

        Route::controller(ReportAndFeedbackController::class)->group(function () {
            Route::post('feedback-store', 'feedbackStore')->name('feedback.store');
            Route::post('report-store', 'reportStore')->name('report.submit');
        });

        Route::controller(ChattingController::class)->group(function () {
            Route::any('chat/reply', 'reply')->name('chat.reply');
            Route::get('chat/list', 'view')->name('chat.list');
            Route::get('chat/search', 'searchData')->name('chat.search');
            Route::delete('chat/{id}/delete', 'delete')->name('chat.delete');
            Route::any('chat/{id}/nickname-set', 'nickname')->name('chat.nickname');
            Route::any('chat/details', 'chatDetails')->name('chat.details');
            Route::any('new-chat', 'newChat')->name('chat.new');

            Route::any('chat/filter', 'filter')->name('chat.filter');
        });

        Route::controller(ReviewController::class)->group(function () {
            Route::any('review/store', 'store')->name('review.store');
            Route::any('review/reply', 'reply')->name('review.reply');
        });

        Route::controller(WishlistController::class)->group(function () {
            Route::any('wishlists', 'wishlists')->name('wishlists');
        });

        Route::controller(PayoutController::class)->group(function () {
            Route::get('payout-list', 'index')->name('payout.index');
            Route::get('payout', 'payout')->name('payout');
            Route::get('payout-supported-currency', 'payoutSupportedCurrency')->name('payout.supported.currency');
            Route::get('payout-check-amount', 'checkAmount')->name('payout.checkAmount');
            Route::post('request-payout', 'payoutRequest')->name('payout.request');
            Route::match(['get', 'post'],'confirm-payout/{trx_id}', 'confirmPayout')->name('payout.confirm');
            Route::post('confirm-payout/flutterwave/{trx_id}', 'flutterwavePayout')->name('payout.flutterwave');
            Route::post('confirm-payout/paystack/{trx_id}', 'paystackPayout')->name('payout.paystack');
            Route::get('payout-check-limit', 'checkLimit')->name('payout.checkLimit');
            Route::post('payout-bank-form', 'getBankForm')->name('payout.getBankForm');
            Route::post('payout-bank-list', 'getBankList')->name('payout.getBankList');
        });

        Route::controller(RelativesController::class)->group(function () {
            Route::get('relatives', 'relatives')->name('relatives');
            Route::get('relative-add', 'relativeAdd')->name('relative.add');
            Route::get('relative-edit/{type}/{serial}', 'relativeEdit')->name('relative.edit');
            Route::post('relative-store', 'relativeStore')->name('relative.store');
            Route::post('relative-update', 'relativeUpdate')->name('relative.update');
            Route::delete('relative-delete', 'relativeDelete')->name('relative.delete');
        });
    });
});
