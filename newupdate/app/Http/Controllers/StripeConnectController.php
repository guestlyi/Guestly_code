<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gateway;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;
use Twilio\Rest\Api\V2010\Account\Usage\Record\YearlyInstance;

class StripeConnectController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $stripeGateway = Gateway::select(['id', 'code', 'status', 'parameters'])
            ->where('code', 'stripe')->where('status', 1)->firstOrFail();
        $this->stripe = new StripeClient($stripeGateway->parameters->secret_key);
    }

    public function connect(Request $request)
    {
        $request->validate([
            'country' => 'required'
        ]);
        if (config('demo.IS_DEMO')) {
            return back()->with('warning', 'Demo mode you are not allowed to access this feature');
        }

        try {
            $vendor = auth()->user() ?? auth()->guard('affiliate')->user();
            $accountType = basicControl()->stripe_connect_account_type; // 'express' or 'standard'

            // Already onboarded
            if ($vendor->stripe_account_id && $vendor->stripe_onboarded) {
                return redirect()->route('stripe.dashboard');
            }

            // Already has account but not onboarded
            if ($vendor->stripe_account_id && !$vendor->stripe_onboarded) {
                return redirect()->route('stripe.onboard');
            }

            // Create new Stripe account
            $account = $this->stripe->accounts->create([
                'country' => $request->country ?? 'US',
                'type' => $accountType,
                'email' => $vendor->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
            ]);

            $vendor->stripe_account_id = $account->id;
            $vendor->stripe_account_type = $accountType;
            $vendor->stripe_onboarded = false;
            $vendor->save();

            return redirect()->route('stripe.onboard');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function startOnboarding()
    {
        try {
            $vendor = auth()->user() ?? auth()->guard('affiliate')->user();

            if (!$vendor->stripe_account_id) {
                return redirect()->route('stripe.connect');
            }

            $accountLink = $this->stripe->accountLinks->create([
                'account' => $vendor->stripe_account_id,
                'refresh_url' => route('stripe.connect'),
                'return_url' => route('stripe.connect.callback'),
                'type' => 'account_onboarding',
            ]);

            return redirect($accountLink->url);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function onboardCallback()
    {
        try {
            $vendor = auth()->user() ?? auth()->guard('affiliate')->user();

            if (!$vendor->stripe_account_id) {
                return redirect()->{$this->getRedirectRoute()}->with('error', 'Stripe account not found.');
            }

            $account = $this->stripe->accounts->retrieve($vendor->stripe_account_id);

            if ($account->details_submitted) {
                $vendor->stripe_onboarded = true;
                $vendor->save();
                return redirect()->{$this->getRedirectRoute()}->with('success', 'Stripe account connected successfully!');
            }

            return redirect()->{$this->getRedirectRoute()}->with('warning', 'Stripe onboarding not completed.');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function redirectToDashboard()
    {
        if (config('demo.IS_DEMO') && auth()->user()->username != 'demouser') {
            return back()->with('warning', 'Demo mode you are not allowed to access this feature');
        }
        try {
            $vendor = auth()->user();

            if (!$vendor->stripe_account_id || !$vendor->stripe_onboarded) {
                return redirect()->route('stripe.connect')->with('warning', 'You must complete Stripe onboarding first.');
            }

            if ($vendor->stripe_account_type === 'express') {
                $loginLink = $this->stripe->accounts->createLoginLink($vendor->stripe_account_id);
                return redirect($loginLink->url);
            }

            return redirect()->{$this->getRedirectRoute()}->with('success', 'Standard account connected. You can manage it from your Stripe dashboard.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function getRedirectRoute()
    {
        if (Auth::guard('affiliate')->check()) {
            return route('affiliate.dashboard');
        } else {
            return route('user.dashboard');
        }
    }
}
