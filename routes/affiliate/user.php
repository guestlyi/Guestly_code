<?php

use App\Http\Controllers\Affiliate\Auth\ForgotPasswordController;
use App\Http\Controllers\Affiliate\Auth\LoginController;
use App\Http\Controllers\Affiliate\Auth\RegisterController;
use App\Http\Controllers\Affiliate\Auth\ResetPasswordController;
use App\Http\Controllers\Affiliate\HomeController;
use App\Http\Controllers\Affiliate\InAppNotificationController;
use App\Http\Controllers\Affiliate\KycVerificationController;
use App\Http\Controllers\Affiliate\PayoutController;
use App\Http\Controllers\Affiliate\ProfileController;
use App\Http\Controllers\Affiliate\SupportTicketController;
use App\Http\Controllers\Affiliate\SumsubController;
use Illuminate\Support\Facades\Route;

$basicControl = basicControl();

Route::prefix('affiliate')->name('affiliate.')->group(function () {
    Route::middleware('guest:affiliate')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');

        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [RegisterController::class, 'register'])->name('register.submit');

        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPassword'])->name('password.email');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
        Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.reset.update');
    });

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::controller(SumsubController::class)->group(function () {
        Route::get('kyc/verify/check', 'kycCheck')->name('sumsub.kyc.check');
        Route::get('get/sumsub/token', 'getToken')->name('get.sumsub.token');
    });

    Route::get('verification/kyc', [KycVerificationController::class, 'kyc'])->name('verification.kyc');
    Route::post('verification/kyc/submit', [KycVerificationController::class, 'verificationSubmit'])->name('kyc.verification.submit');

    Route::middleware(['auth:affiliate', 'affiliateCheck', 'affiliate.kyc'])->group(function () {

        Route::get('push-notification-show', [InAppNotificationController::class, 'show'])->name('push.notification.show');
        Route::get('push-notification-readAll', [InAppNotificationController::class, 'readAll'])->name('push.notification.readAll');
        Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

        Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::get('item-list', [HomeController::class, 'itemList'])->name('item.list');
        Route::get('affiliate-chart', [HomeController::class, 'affiliateChart'])->name('chart');
        Route::get('transactions', [HomeController::class, 'transaction'])->name('transactions');
        Route::get('payments', [HomeController::class, 'payments'])->name('payments');
        Route::get('download-payment/{id}', [HomeController::class, 'downloadInvoice'])->name('download.payment');
        Route::get('information', [HomeController::class, 'information'])->name('information');
        Route::get('payouts', [HomeController::class, 'payouts'])->name('payouts');
        Route::get('analytics', [HomeController::class, 'analytics'])->name('analytics');
        Route::get('pending-earning', [HomeController::class, 'pendingEarning'])->name('pending.earning');
        Route::get('analytics/fetch-refer-data', [HomeController::class, 'fetchReferData'])->name('fetchReferData');
        Route::get('analytics/fetch-country-data', [HomeController::class, 'fetchCountryData'])->name('fetchCountryData');

        Route::prefix('ticket')->as('ticket.')->controller(SupportTicketController::class)->group(function () {
            Route::get('/', 'index')->name('list');
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::get('/view/{ticket}', 'ticketView')->name('view');
            Route::put('/reply/{ticket}', 'reply')->name('reply');
            Route::get('/download/{ticket}', 'download')->name('download');
            Route::get('/closed/{id}', 'ticketClosed')->name('closed');
        });

        Route::get('profile', [HomeController::class, 'profile'])->name('profile');

        Route::prefix('profile')->as('profile.')->controller(ProfileController::class)->group(function () {
            Route::post('/image-update', 'imageUpdate')->name('image.update');
            Route::post('/basic-profile-update', 'basicProfileUpdate')->name('basic.update');
            Route::post('/basic-phone-update', 'basicPhoneUpdate')->name('phone.update');
            Route::get('/password-change', 'passwordChange')->name('change.password');
            Route::post('/password-update', 'passwordUpdate')->name('update.password');
        });

        Route::controller(PayoutController::class)->group(function () {
            Route::get('payout', 'payout')->name('payout.now');
            Route::get('payout-supported-currency', 'payoutSupportedCurrency')->name('payout.supported.currency');
            Route::get('payout-check-amount', 'checkAmount')->name('payout.checkAmount');
            Route::post('request-payout', 'payoutRequest')->name('payout.request');
            Route::match(['get', 'post'], 'confirm-payout/{trx_id}', 'confirmPayout')->name('payout.confirm');
            Route::post('confirm-payout/flutterwave/{trx_id}', 'flutterwavePayout')->name('payout.flutterwave');
            Route::post('confirm-payout/paystack/{trx_id}', 'paystackPayout')->name('payout.paystack');
            Route::get('payout-check-limit', 'checkLimit')->name('payout.checkLimit');
            Route::post('payout-bank-form', 'getBankForm')->name('payout.getBankForm');
            Route::post('payout-bank-list', 'getBankList')->name('payout.getBankList');
        });
    });
});

Route::get('{username?}/{vanity_link?}', [HomeController::class, 'affiliateClick'])->name('affiliateClick');
Route::any('webhook/sumsub', [SumsubController::class, 'webhookRes'])->name('sumsub.webhook');
