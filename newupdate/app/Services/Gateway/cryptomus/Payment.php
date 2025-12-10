<?php

namespace App\Services\Gateway\cryptomus;

use Illuminate\Support\Facades\Http;
use Facades\App\Services\BasicService;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $postParams = [
            'amount'      => $deposit->payable_amount,
            'currency'    => $deposit->payment_method_currency,
            'order_id'    => $deposit->trx_id,
            'url_return'  => route('user.dashboard'),
            'url_success' => route('success'),
            'url_callback'=> route('ipn', [$gateway->code, $deposit->trx_id]),
        ];

        $sign = md5(base64_encode(json_encode($postParams)) . $gateway->parameters->api_key);

        $response = Http::withHeaders([
            'Merchant'     => $gateway->parameters->mercent_id,
            'Sign'         => $sign,
            'Content-Type' => 'application/json',
        ])->post('https://api.cryptomus.com/v1/payment', $postParams);

        if ($response->successful() && isset($response['result']['url'])) {
            return json_encode([
                'redirect'      => true,
                'redirect_url'  => $response['result']['url'],
            ]);
        }

        return json_encode([
            'error'   => true,
            'message' => 'Unexpected Error! Please Try Again',
        ]);
    }


    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $postParams = [
            'order_id' => $deposit->trx_id,
        ];

        $sign = md5(base64_encode(json_encode($postParams)) . $gateway->parameters->api_key);

        $response = Http::withHeaders([
            'Merchant'     => $gateway->parameters->mercent_id,
            'Sign'         => $sign,
            'Content-Type' => 'application/json',
        ])->post('https://api.cryptomus.com/v1/payment/info', $postParams);

        if ($response->successful() && isset($response['result']) &&
            in_array($response['result']['payment_status'], ['paid', 'paid_over'])) {

            BasicService::preparePaymentUpgradation($deposit);

            return [
                'status'   => 'success',
                'msg'      => 'Transaction was successful.',
                'redirect' => route('success'),
            ];
        }

        return [
            'status'   => 'error',
            'msg'      => 'Unsuccessful transaction.',
            'redirect' => route('failed'),
        ];
    }
}
