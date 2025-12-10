<?php

use App\Http\Controllers\Admin\Affiliate\AffiliateController;
use App\Http\Controllers\Admin\Affiliate\PayoutLogController;
use App\Http\Controllers\Admin\Affiliate\ProfileManagement;
use Illuminate\Support\Facades\Route;

$adminPrefix = basicControl()->admin_prefix ?? 'admin';

Route::prefix($adminPrefix)->as('admin.')->middleware(['auth:admin'])->group(function () {
        Route::prefix('affiliate')->as('affiliate.')->group(function () {
            Route::controller(AffiliateController::class)->group(function () {
                Route::get('/dashboard/{id}', 'dashboard')->name('dashboard');
                Route::get('list', 'index')->name('list');
                Route::get('search', 'search')->name('search');
                Route::get('search-count-data', 'searchCountData')->name('search.countData');

                Route::get('login-as-affiliate/{id}', 'loginAs')->name('login.as');
            });

            Route::controller(ProfileManagement::class)->as('profile.')->group(function () {
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('image-update/{id}', 'imageUpdate')->name('image.update');
                Route::post('basic-update/{id}', 'basicUpdate')->name('basic.update');
                Route::post('email-update/{id}', 'emailUpdate')->name('email.update');
                Route::post('password-update/{id}', 'passwordUpdate')->name('password.update');
                Route::post('preferences-update/{id}', 'preferencesUpdate')->name('preferences.update');
                Route::delete('delete/{id}', 'delete')->name('delete');

                Route::post('status/{id}', 'status')->name('status');
                Route::get('profile/{id}', 'profile')->name('view');
                Route::post('profile/block/{id}', 'block')->name('block');
                Route::post('profile/balance-update/{id}', 'balanceUpdate')->name('update.balance');

                Route::get('send-mail/{id}', 'sendMail')->name('send.mail');
                Route::post('send/mail', 'sendMailConfirm')->name('email.send');

                Route::get('profile/transaction/{id}', 'transaction')->name('transaction');
                Route::get('profile/transaction-search/{id}', 'transactionSearch')->name('transaction.search');

                Route::get('profile/withdraw/{id}', 'withdraw')->name('withdraw');
                Route::get('profile/withdraw-search/{id}', 'withdrawSearch')->name('withdraw.search');

                Route::get('profile/earnings/{id}', 'earnings')->name('earnings');
            });

            Route::controller(PayoutLogController::class)->as('payout.')->group(function () {
                Route::get('payouts', 'payouts')->name('log');
                Route::get('payout-search', 'search')->name('search');
                Route::put('payout-action/{id}', 'action')->name('action');
            });
        });
    });
