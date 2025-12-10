<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Stripe\StripeClient;
use App\Models\Gateway;

class StripePayoutDistribute implements ShouldQueue
{
    use Queueable;

    protected $stripe;
    protected $vendor;
    protected $amount;

    /**
     * Create a new job instance.
     */
    public function __construct($vendor, $amount)
    {
        $stripeGateway = Gateway::select(['id', 'code', 'status', 'parameters'])
            ->where('code', 'stripe')->where('status', 1)->first();
        $this->stripe = new StripeClient($stripeGateway->parameters->secret_key);
        $this->vendor = $vendor;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $transfer = $this->stripe->transfers->create([
                'amount' => (int)(round($this->amount) * 100),
                'currency' => strtolower(basicControl()->base_currency),
                'destination' => $this->vendor->stripe_account_id,
                'description' => "Payout vendor destination {$this->vendor->stripe_account_id}",
            ]);

            if (!empty($transfer->destination_payment)) {
                info('Transfer succeeded — Stripe sent money to connected account');
            } else {
                // ❌ Fallback — Add amount manually
                $this->vendor->balance += $this->amount;
                $this->vendor->save();
            }
        } catch (\Exception $exception) {
            $this->vendor->balance += $this->amount;
            $this->vendor->save();
        }
    }
}
