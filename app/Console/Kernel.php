<?php

namespace App\Console;

use App\Console\Commands\PayoutCryptoCurrencyUpdateCron;
use App\Console\Commands\PayoutCurrencyUpdateCron;
use App\Console\Commands\PermanentlyDeleteOldAffiliates;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Payout;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{


    protected $commands = [
        PermanentlyDeleteOldAffiliates::class
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $basicControl = basicControl();
        if ($basicControl->currency_layer_auto_update == 1) {
            $schedule->command('app:gateway-currency-update')->{$basicControl->currency_layer_auto_update_at}();
        }
        $schedule->command('blockIo:ipn')->hourly();
        $schedule->command('affiliates:purge-old-deleted')->daily();
        $schedule->command('app:distribute-affiliate-charge-cron')->daily();
        $schedule->command('app:stripe-payout-to-affiliate-cron')->daily();
        $schedule->command('app:stripe-payout-to-vendor-cron')->daily();
        $schedule->command('ical:sync')->hourly();

        $schedule->command('model:prune', [
            '--model' => [Deposit::class, Payout::class],
        ])->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
