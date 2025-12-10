<?php

namespace App\Services;


use App\Jobs\StripePayoutDistribute;
use App\Models\AffiliateEarning;
use App\Models\AffiliateStatistics;
use App\Models\Booking;
use App\Models\Gateway;
use App\Models\Transaction;
use App\Traits\Notify;
use GPBMetadata\Google\Api\Auth;

class BasicService
{
    use Notify;

    public function setEnv($value)
    {
        $envPath = base_path('.env');
        $env = file($envPath);
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            $env[$env_key] = array_key_exists($entry[0], $value) ? $entry[0] . "=" . $value[$entry[0]] . "\n" : $env_value;
        }
        $fp = fopen($envPath, 'w');
        fwrite($fp, implode($env));
        fclose($fp);
    }

    public function preparePaymentUpgradation($deposit)
    {
        try {
            if ($deposit->status == 0 || $deposit->status == 2) {
                $deposit->status = 1;
                $deposit->save();

                if ($deposit->user) {
                    if ($deposit->depositable_type == Booking::class) {
                        $booking = $deposit->depositable;
                        if (isset($booking)) {
                            $this->bookingCompleteAction($booking, $deposit);
                            $this->checkAffiliate($booking);

                        }
                    }
                }
                return true;
            }
        } catch (\Exception $e) {
        }
    }

    public function bookingCompleteAction($booking, $deposit)
    {
        $booking->status = 4;
        $booking->save();

        $owner = $booking->property->host;

        if ($owner) {
            if (basicControl()->stripe_connect_status && $owner->stripe_account_id && $owner->stripe_onboarded && !basicControl()->stripe_connect_hold_payout) {
                dispatch(new StripePayoutDistribute($owner, $booking->host_received));
            } else {
                $owner->balance += $booking->host_received;
                $owner->save();
            }
        }

        $booking->property->increment('total_sell');

        $remark = 'Payment via ' . $deposit->gateway->name . 'for property booking';
        $this->makeTransaction($booking->guest_id, $deposit->user->balance, $deposit->payable_amount_in_base_currency, '-', $remark, $booking->id, Booking::class, $deposit->base_currency_charge, $deposit->host_id, null);

        $params = [
            'title' => $booking->property->title,
            'date_range' => $booking->check_in_date . '-' . $booking->check_out_date,
            'guest_name' => $booking->guest->firstname . ' ' . $booking->guest->lastname,
            'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
            'transaction' => $deposit->trx_id,
        ];

        $action = [
            "link" => route('user.reservations'),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->sendMailSms($booking->property->host, 'PROPERTY_BOOKING_HOST', $params);
        $this->userPushNotification($booking->property->host, 'PROPERTY_BOOKING_HOST', $params, $action);
        $this->userFirebasePushNotification($booking->property->host, 'PROPERTY_BOOKING_HOST', $params);

        $params = [
            'title' => $booking->property->title,
            'date_range' => $booking->check_in_date . '-' . $booking->check_out_date,
            'amount' => number_format($deposit->amount, 2) . $deposit->payment_method_currency,
            'transaction' => $deposit->trx_id,
        ];

        $guestAction = [
            "link" => route('user.reservations'),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->sendMailSms($deposit->user, 'PROPERTY_BOOKING_GUEST', $params);
        $this->userPushNotification($deposit->user, 'PROPERTY_BOOKING_GUEST', $params, $guestAction);
        $this->userFirebasePushNotification($deposit->user, 'PROPERTY_BOOKING_GUEST', $params);

        $params = [
            'username' => optional($deposit->user)->username,
            'title' => $booking->property->title,
            'date_range' => $booking->check_in_date . '-' . $booking->check_out_date,
            'amount' => number_format($deposit->amount, 2) . $deposit->payment_method_currency,
            'transaction' => $deposit->trx_id,
        ];

        $actionAdmin = [
            "name" => optional($deposit->user)->firstname . ' ' . optional($deposit->user)->lastname,
            "image" => getFile(optional($deposit->user)->image_driver, optional($deposit->user)->image),
            "link" => route('admin.all.booking'),
            "icon" => "fas fa-ticket-alt text-white"
        ];

        $this->adminMail('PROPERTY_BOOKING_ADMIN', $params, $action);
        $this->adminPushNotification('PROPERTY_BOOKING_ADMIN', $params, $actionAdmin);
        $this->adminFirebasePushNotification('PROPERTY_BOOKING_ADMIN', $params);
    }

    public function checkAffiliate($booking): void
    {
        $affiliateId = session()->get('affiliate.affiliate_id');
        $propertyId = session()->get('affiliate.property_id');

        if ($affiliateId && $propertyId && $booking->property_id == $propertyId) {
            $booking->affiliate_user_id = $affiliateId;
            $booking->save();

            session()->forget('affiliate');
        }
    }

    public function distributeAffiliate($booking): void
    {
        if ($booking->affiliate_user_id && $booking->affiliate_user) {
            $affiliateCharge = $booking->total_amount * basicControl()->affiliate_commission_percentage / 100;

            $affiliateEarning = new AffiliateEarning();
            $affiliateEarning->affiliate_id = $booking->affiliate_user_id;
            $affiliateEarning->property_id = $booking->property_id;
            $affiliateEarning->amount = $affiliateCharge;
            $affiliateEarning->payment_release_date = $booking->check_in_date;
            $affiliateEarning->save();

            $params = [
                'username' => $booking->affiliate?->username ?? '',
                'title' => $affiliateEarning->property->title ?? '',
                'release_date' => dateTime($affiliateEarning->payment_release_date),
                'amount' => currencyPosition($affiliateCharge)
            ];

            $guestAction = [
                "link" => route('affiliate.pending.earning'),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($affiliateEarning->affiliate, 'AFFILIATE_EARNING', $params);
            $this->userPushNotification($affiliateEarning->affiliate, 'AFFILIATE_EARNING', $params, $guestAction);
            $this->userFirebasePushNotification($affiliateEarning->affiliate, 'AFFILIATE_EARNING', $params);
        }
    }

    public function makeTransaction($userId, $userBalance, $amount, $trxType, $remark, $transactionalId = null, $transactionalType = null, $charge_in_base_currency = null, $vendor_id = null, $affiliate_id = null): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->amount = $amount;
        $transaction->charge = $charge_in_base_currency;
        $transaction->balance = $userBalance;
        $transaction->trx_type = $trxType;
        $transaction->remarks = $remark;
        $transaction->host_id = $vendor_id;
        $transaction->affiliate_id = $affiliate_id;
        $transaction->transactional_id = $transactionalId;
        $transaction->transactional_type = $transactionalType;
        $transaction->save();
    }

    public function cryptoQR($wallet, $amount, $crypto = null)
    {
        $varb = $wallet . "?amount=" . $amount;
        return "https://quickchart.io/chart?cht=qr&chs=150x150&chl=$varb";
    }

}
