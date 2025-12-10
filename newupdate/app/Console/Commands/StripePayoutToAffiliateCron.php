<?php

namespace App\Console\Commands;

use App\Jobs\StripePayoutDistribute;
use App\Models\Affiliate;
use App\Models\AffiliateEarning;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Console\Command;

class StripePayoutToAffiliateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stripe-payout-to-affiliate-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Affiliate payment amount distribute to connected account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basicControl = basicControl();

        if (!$basicControl->stripe_connect_status) {
            return;
        }

        $vendors = Affiliate::select(['id', 'stripe_account_id', 'stripe_onboarded', 'status', 'balance'])
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

        $totalHoldAmount = AffiliateEarning::where('affiliate_id', $vendor->id)->where('status', 1)
            ->where('created_at', '<=', now()->subDays($holdDays))->get()
            ->sum(function ($order) {
                return $order->amount;
            });


        $payoutAmount = $vendor->balance - $totalHoldAmount;

        return $payoutAmount > 0 ? $payoutAmount : 0;
    }

}
