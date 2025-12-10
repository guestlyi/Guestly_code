<?php

namespace App\Services\Gateway\uddoktapay;

use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Http;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $baseURL = rtrim($gateway->parameters->base_url, '/') . '/';
        $apiKEY = $gateway->parameters->api_key;

        $fields = [
            'full_name' => $deposit->user?->fullname ?? 'Guest User',
            'email' => $deposit->user?->email ?? 'guest@example.com',
            'amount' => (float)$deposit->payable_amount,
            'metadata' => [
                'order_id' => $deposit->trx_id,
            ],
            'redirect_url' => route('ipn', [$gateway->code, $deposit->trx_id]),
            'cancel_url' => route('failed'),
        ];

        $response = Http::withHeaders([
            'RT-UDDOKTAPAY-API-KEY' => $apiKEY,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($baseURL . 'api/checkout-v2', $fields);


        $res = $response->json();

        if (isset($res) && $res['status']) {
            $send['redirect'] = true;
            $send['redirect_url'] = $res['payment_url'];
        } else {
            $send['error'] = true;
            $send['message'] = 'Unexpected Error! Please Try Again';
        }
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null)
    {
        $baseURL = rtrim($gateway->parameters->base_url, '/') . '/';
        $apiKEY = $gateway->parameters->api_key;

        $fields = [
            'invoice_id' => $request['invoice_id'] ?? $request->invoice_id
        ];

        $response = Http::withHeaders([
            'RT-UDDOKTAPAY-API-KEY' => $apiKEY,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($baseURL . 'api/verify-payment', $fields);

        $res = $response->json();

        if (isset($res) && $res['status'] == 'COMPLETED') {
            BasicService::preparePaymentUpgradation($deposit);

            $data['status'] = 'success';
            $data['msg'] = 'Transaction was successful.';
            $data['redirect'] = route('success');
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Unsuccessful transaction.';
            $data['redirect'] = route('failed');
        }

        return $data;
    }
}
