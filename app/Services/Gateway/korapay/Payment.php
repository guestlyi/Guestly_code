<?php

namespace App\Services\Gateway\korapay;

use Illuminate\Support\Facades\Http;
use Facades\App\Services\BasicService;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $params = [
            'amount' => $deposit->payable_amount,
            'redirect_url' => route('ipn', [$gateway->code, $deposit->trx_id]),
            'currency' => $deposit->payment_method_currency,
            'reference' => $deposit->trx_id,
            'narration' => 'Payment',
            'merchant_bears_cost' => false,
            'customer' => [
                'name' => $deposit->user->firstname . ' ' . $deposit->user->lastname,
                'email' => $deposit->user->email,
            ],
            "notification_url" => route('ipn', [$gateway->code, $deposit->trx_id]),
        ];

        $response = Http::withToken(optional($gateway->parameters)->secret_key)
            ->post('https://api.korapay.com/merchant/api/v1/charges/initialize', $params)
            ->json();

        if (isset($response['status']) && $response['status'] === 'success') {
            return json_encode([
                'redirect' => true,
                'redirect_url' => $response['data']['checkout_url']
            ]);
        }

        return json_encode([
            'error' => true,
            'message' => $response['message'] ?? 'Payment initialization failed.'
        ]);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $reference = $deposit->trx_id;
        if ($reference) {
            $response = Http::withToken($gateway->parameters->secret_key)
                ->get("https://api.korapay.com/merchant/api/v1/charges/{$reference}");

            $verify = $response->json();

            if (isset($verify['data']['status']) && $verify['data']['status'] === 'success') {

                BasicService::preparePaymentUpgradation($deposit);

                $data['status'] = 'success';
                $data['msg'] = 'Transaction was successful.';
                $data['redirect'] = route('success');

                return $data;
            }
        }

        $data['status'] = 'error';
        $data['msg'] = 'Unsuccessful transaction.';
        $data['redirect'] = route('failed');

        return $data;
    }
}
