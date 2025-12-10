<?php

namespace App\Providers;

use App\Models\ContentDetails;
use App\Models\Currency;
use App\Models\Language;
use App\Models\ManageMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Mailchimp\Transport\MandrillTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            DB::connection()->getPdo();

            $data = [
                'basicControl' => basicControl(),
                'theme' => template(),
                'themeTrue' => template(true),
            ];

            View::share($data);

            if (basicControl()->force_ssl == 1) {
                if ($this->app->environment('production') || $this->app->environment('local')) {
                    \URL::forceScheme('https');
                }
            }

            $this->registerMailTransport('sendinblue', new SendinblueTransportFactory);
            $this->registerMailTransport('sendgrid', new SendgridTransportFactory);
            $this->registerMailTransport('mandrill', new MandrillTransportFactory);


        } catch (\Exception $e) {
        }

    }
    private function registerMailTransport(string $name, $factory)
    {
        Mail::extend($name, fn() => $factory->create(
            new Dsn("{$name}+api", 'default', config("services.{$name}.key"))
        ));
    }
}
