<?php

namespace App\Console\Commands;

use App\Jobs\StripePayoutDistribute;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Console\Command;

class StripePayoutToVendorCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stripe-payout-to-vendor-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vendor payment amount distribute to connected account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basicControl = basicControl();

        if (!$basicControl->stripe_connect_status) {
            return;
        }

        $vendors = User::select(['id','stripe_account_id','stripe_onboarded','status','balance'])
            ->whereNotNull('stripe_account_id')
            ->where('stripe_onboarded', 1)
            ->where('status', 1)
            ->get();

        foreach ($vendors as $vendor) {
            $payoutAmount = $this->calculatePayoutAmount($vendor, $basicControl);

            if ($payoutAmount > 0 && $vendor->balance >= $payoutAmount) {
                $vendor->balance -= $payoutAmount;
                $vendor->save();
                dispatch(new StripePayoutDistribute($vendor, $payoutAmount));
            }
        }
    }

    private function calculatePayoutAmount($vendor, $basicControl)
    {
        if (!$basicControl->stripe_connect_hold_payout) {
            return $vendor->balance;
        }

        // Get held orders older than X days
        $holdDays = (int)$basicControl->stripe_connect_hold_days;

        $totalHoldAmount = Booking::whereHas('property.host', function ($q) use ($vendor) {
            $q->where('id', $vendor->id);
        })
            ->where('created_at', '<=', now()->subDays($holdDays))
            ->sum('host_received');


        $payoutAmount = $vendor->balance - $totalHoldAmount;

        return $payoutAmount > 0 ? $payoutAmount : 0;
    }

}
