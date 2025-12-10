<?php

namespace App\Console\Commands;

use App\Jobs\StripePayoutDistribute;
use App\Models\AffiliateEarning;
use App\Models\AffiliateStatistics;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Facades\App\Services\BasicService;

class DistributeAffiliateChargeCron extends Command
{
    use Notify;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:distribute-affiliate-charge-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Affiliate charge distribution cron job.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basic = basicControl();
        $chargeLists = AffiliateEarning::with(['affiliate', 'property'])
            ->where('status', 0)
            ->where('payment_release_date', '<', Carbon::now()->subDay())
            ->get();

        foreach ($chargeLists as $chargeList) {
            $affiliate = $chargeList->affiliate;

            if ($basic->stripe_connect_status && $affiliate->stripe_account_id && $affiliate->stripe_onboarded && !$basic->stripe_connect_hold_payout) {
                dispatch(new StripePayoutDistribute($affiliate, $chargeList->amount));
            } else {
                $affiliate->balance += $chargeList->amount;
                $affiliate->save();
            }

            $remark = 'Affiliate commission for property booking';
            BasicService::makeTransaction(null, $affiliate->balance, $chargeList->amount, '+', $remark, $chargeList->id, AffiliateEarning::class, 0, null, $chargeList->affiliate_id);

            $chargeList->status = 1;
            $chargeList->save();

            $params = [
                'username' => $affiliate->username ?? '',
                'title' => $chargeList->property?->title ?? '',
                'amount' => currencyPosition($chargeList->amount),
            ];

            $guestAction = [
                "link" => route('affiliate.transactions'),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($affiliate, 'AFFILIATE_PAYMENT_RELEASED', $params);
            $this->userPushNotification($affiliate, 'AFFILIATE_PAYMENT_RELEASED', $params, $guestAction);
            $this->userFirebasePushNotification($affiliate, 'AFFILIATE_PAYMENT_RELEASED', $params);
        }
    }
}
