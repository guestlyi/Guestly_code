<?php

use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Frontend\Module\ServiceController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\IcalController;
use App\Http\Controllers\KoraPayPaymentController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\ManualRecaptchaController;
use App\Http\Controllers\khaltiPaymentController;
use App\Http\Controllers\User\SocialiteController;
use App\Http\Controllers\User\SumsubController;
use App\Http\Controllers\StripeConnectController;
use App\Models\ContentDetails;
use App\Models\User;
use App\Services\ContentTranslationService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\VerificationController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\User\KycVerificationController;
use App\Http\Controllers\TwoFaSecurityController;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Faker\Factory as Faker;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/translate-content', function (ContentTranslationService $service) {
    $service->translateContent(1, 2, 'en', 'es');

    return 'Content translation completed!';
});

$basicControl = basicControl();
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('lang', $locale);
    return redirect()->back();
})->name('language');


Route::get('maintenance-mode', function () {
    if (!basicControl()->is_maintenance_mode) {
        return redirect(route('page'));
    }

    $data['maintenanceMode'] = \App\Models\MaintenanceMode::first();
    return view(template() . 'maintenance', $data);
})->name('maintenance');

Route::get('clear', function () {
    Illuminate\Support\Facades\Artisan::call('optimize:clear');
    $previousUrl = url()->previous();
    $keywords = ['push-notification', 'ajax'];
    if (array_filter($keywords, fn($keyword) => str_contains($previousUrl, $keyword))) {
        return redirect('/')->with('success', 'Cache Cleared Successfully');
    }
    return redirect()->back(fallback: '/')->with('success', 'Cache Cleared Successfully');

})->name('clear');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPassword'])->name('user.password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.update');

Route::get('instruction/page', function () {
    return view('instruction-page');
})->name('instructionPage');

Route::group(['middleware' => ['maintenanceMode']], function () use ($basicControl) {
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->name('login.submit');
    });

    Route::get('/stripe/connect', [StripeConnectController::class, 'connect'])->name('stripe.connect');
    Route::get('/stripe/onboard', [StripeConnectController::class, 'startOnboarding'])->name('stripe.onboard');
    Route::get('/stripe/dashboard', [StripeConnectController::class, 'redirectToDashboard'])->name('stripe.dashboard');
    Route::get('/stripe/callback', [StripeConnectController::class, 'onboardCallback'])->name('stripe.connect.callback');

    Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {

        Route::get('check', [VerificationController::class, 'check'])->name('check');
        Route::get('resend_code', [VerificationController::class, 'resendCode'])->name('resend.code');
        Route::post('mail-verify', [VerificationController::class, 'mailVerify'])->name('mail.verify');
        Route::post('sms-verify', [VerificationController::class, 'smsVerify'])->name('sms.verify');
        Route::post('twoFA-Verify', [VerificationController::class, 'twoFAverify'])->name('twoFA-Verify');

        Route::controller(SumsubController::class)->group(function () {
            Route::get('kyc/verify/check', 'kycCheck')->name('sumsub.kyc.check');
            Route::get('get/sumsub/token', 'getToken')->name('get.sumsub.token');
        });

        Route::middleware('userCheck')->group(function () {
            Route::middleware('kyc')->group(function () {

                Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
                Route::post('save-token', [HomeController::class, 'saveToken'])->name('save.token');
                Route::get('payment-history', [HomeController::class, 'paymentHistory'])->name('payment.history');

                Route::get('transaction-list', [HomeController::class, 'transaction'])->name('transaction');

                Route::get('two-step-security', [TwoFaSecurityController::class, 'twoStepSecurity'])->name('twostep.security');
                Route::post('twoStep-enable', [TwoFaSecurityController::class, 'twoStepEnable'])->name('twoStepEnable');
                Route::post('twoStep-disable', [TwoFaSecurityController::class, 'twoStepDisable'])->name('twoStepDisable');
                Route::post('twoStep/re-generate', [TwoFaSecurityController::class, 'twoStepRegenerate'])->name('twoStepRegenerate');

                Route::get('push-notification-show', [InAppNotificationController::class, 'show'])->name('push.notification.show');
                Route::get('push-notification-readAll', [InAppNotificationController::class, 'readAll'])->name('push.notification.readAll');
                Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

                Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
                    Route::get('/', [SupportTicketController::class, 'index'])->name('list');
                    Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
                    Route::post('/create', [SupportTicketController::class, 'store'])->name('store');
                    Route::get('/view/{ticket}', [SupportTicketController::class, 'ticketView'])->name('view');
                    Route::put('/reply/{ticket}', [SupportTicketController::class, 'reply'])->name('reply');
                    Route::get('/closed/{id}', [SupportTicketController::class, 'ticketClosed'])->name('closed');
                    Route::get('/download/{ticket}', [SupportTicketController::class, 'download'])->name('download');
                });

                Route::get('profile', [HomeController::class, 'profile'])->name('profile');
                Route::get('profile-details', [HomeController::class, 'profileDetails'])->name('profile.details');
                Route::get('profile/create', [HomeController::class, 'personalCreate'])->name('personalCreate');
                Route::get('profile/login-security', [HomeController::class, 'loginSecurity'])->name('loginSecurity');
                Route::get('profile/personal-info', [HomeController::class, 'personalInfo'])->name('personalInfo');
                Route::post('profile/personal-info/update', [HomeController::class, 'personalInfoUpdate'])->name('personalInfo.update');
                Route::post('profile-update', [HomeController::class, 'profileUpdate'])->name('profile.update');
                Route::post('profile-phone-update', [HomeController::class, 'profilePhoneUpdate'])->name('basic.phone.update');
                Route::post('basic-profile-update', [HomeController::class, 'basicProfileUpdate'])->name('basic.profile.update');
                Route::post('profile-update/image', [HomeController::class, 'profileUpdateImage'])->name('profile.update.image');
                Route::post('update/password', [HomeController::class, 'updatePassword'])->name('updatePassword');
                Route::post('/account/toggle-status', [HomeController::class, 'toggleStatus'])->name('account.toggleStatus');

                Route::get('/earnings/data', [HomeController::class, 'getEarningsData'])->name('earnings.data');
                Route::get('/earnings/summery', [HomeController::class, 'getYearToDateSummary'])->name('earnings.summary');
            });

            Route::get('verification/kyc', [KycVerificationController::class, 'kyc'])->name('verification.kyc');
            Route::get('verification/kyc-form/{id}', [KycVerificationController::class, 'kycForm'])->name('verification.kyc.form');
            Route::post('verification/kyc/submit', [KycVerificationController::class, 'verificationSubmit'])->name('kyc.verification.submit');
            Route::get('verification/kyc/history', [KycVerificationController::class, 'history'])->name('verification.kyc.history');
        });
    });

    Route::get('captcha', [ManualRecaptchaController::class, 'reCaptCha'])->name('captcha');

    Route::get('supported-currency', [DepositController::class, 'supportedCurrency'])->name('supported.currency');
    Route::post('payment-request', [DepositController::class, 'paymentRequest'])->name('payment.request');
    Route::get('deposit-check-amount', [DepositController::class, 'checkAmount'])->name('deposit.checkAmount');

    Route::get('payment-process/{trx_id}', [PaymentController::class, 'depositConfirm'])->name('payment.process');
    Route::post('addFundConfirm/{trx_id}', [PaymentController::class, 'fromSubmit'])->name('addFund.fromSubmit');
    Route::match(['get', 'post'], 'success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], 'failed', [PaymentController::class, 'failed'])->name('failed');

    Route::post('khalti/payment/verify/{trx}', [khaltiPaymentController::class, 'verifyPayment'])->name('khalti.verifyPayment');
    Route::post('khalti/payment/store', [khaltiPaymentController::class, 'storePayment'])->name('khalti.storePayment');

    Route::get('blog', [BlogController::class, 'blog'])->name('blog');
    Route::get('blog-details/{slug}', [BlogController::class, 'blogDetails'])->name('blog.details')->middleware('visitor');

    Route::get('stays', [ServiceController::class, 'services'])->name('services');
    Route::get('stays-images/{slug}', [ServiceController::class, 'serviceImages'])->name('service.images');
    Route::get('stays-host/{username}', [ServiceController::class, 'serviceHosts'])->name('service.hosts');
    Route::get('/host/reviews/load', [ServiceController::class, 'loadMoreReviews'])->name('host.reviews.load');
    Route::get('/host/properties/load', [ServiceController::class, 'loadMoreProperties'])->name('host.properties.load');

    Route::get('become-an-affiliate', [FrontendController::class, 'becomeAnAffiliate'])->name('become-an-affiliate');

    Route::post('contact/send', [FrontendController::class, 'contact'])->name('contact.send');

    Route::get('/get-cities', [HomeController::class, 'getCities'])->name('getCities');
    Route::get('/get-locations', [HomeController::class, 'getLocations'])->name('getLocations');
    Route::get('/get-language', [HomeController::class, 'getLanguage'])->name('getLanguage');

    Route::get('auth/{socialite}', [SocialiteController::class, 'socialiteLogin'])->name('socialiteLogin');
    Route::get('auth/callback/{socialite}', [SocialiteController::class, 'socialiteCallback'])->name('socialiteCallback');

    Route::get('properties/{property}/calendar.ics', [IcalController::class, 'export'])->name('properties.calendar.export');
    Route::post('/property/{property}/ical-refresh', [IcalController::class, 'refresh'])->name('property.ical.refresh');

    Route::post('setting-change', [FrontendController::class, 'settingChange'])->name('settingChange');
    Route::get('fetch-search', [FrontendController::class, 'fetchSearch'])->name('fetch.search');
    Route::get('fetch-category-data', [FrontendController::class, 'fetchCategory'])->name('fetch.category');
    Route::get('fetch-destination', [FrontendController::class, 'fetchDestination'])->name('fetch.destination');
    Route::get('fetch-response-rate/{host_id}', [FrontendController::class, 'fetchResponseRate'])->name('fetch.response.rate');
    Route::get('fetch-properties', [FrontendController::class, 'fetchProperties'])->name('get.properties');
    Route::post('subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');

//    Route::get('kora-payment/verify', [KoraPayPaymentController::class, 'verifyPayment'])->name('kora.payment.verify');

    Route::post('mpesa/stkPush/{trx_id}', [MpesaController::class, 'stkPush'])->name('mpesa.stkPush');
    Route::post('mpesa/webhook', [MpesaController::class, 'webhook'])->name('mpesa.webhook');


    Auth::routes();

    Route::get("/{slug?}", [FrontendController::class, 'page'])->name('page');

    Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', [PaymentController::class, 'gatewayIpn'])->name('ipn');
    Route::any('webhook/sumsub', [SumsubController::class, 'webhookRes'])->name('sumsub.webhook');
});


