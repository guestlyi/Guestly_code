<?php

namespace App\Services\Gateway\gcash;

use Illuminate\Support\Facades\Http;
use Facades\App\Services\BasicService;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $secretKey = $gateway->parameters->secret_key;

        $amountPhp = $deposit->payable_amount ?? 100.00;
        $amount = (int)round($amountPhp * 100);

        $payload = [
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'currency' => 'PHP',
                    'payment_method_types' => ['gcash'],
                    'billing' => [
                        'name' => $deposit->user->name ?? 'John Doe',
                        'email' => $deposit->user->email ?? 'customer@example.com',
                    ],
                    'metadata' => [
                        'trx_id' => $deposit->trx_id,
                    ],
                    'line_items' => [
                        [
                            'name' => 'Wallet Deposit',
                            'quantity' => 1,
                            'amount' => $amount,
                            'currency' => 'PHP',
                        ],
                    ],
                    'success_url' => route('ipn', [$gateway->code, $deposit->trx_id]),
                    'cancel_url' => route('failed'),
                ],
            ],
        ];

        $response = Http::withBasicAuth($secretKey, '')
            ->post('https://api.paymongo.com/v1/checkout_sessions', $payload);

        $json = $response->json();

        if ($response->successful() && isset($json['data']['attributes']['checkout_url'])) {
            $deposit->update([
                'payment_id' => $json['data']['id'],
            ]);
            return json_encode([
                'redirect' => true,
                'redirect_url' => $json['data']['attributes']['checkout_url'],
            ]);
        }

        return json_encode([
            'error' => true,
            'message' => $json['errors'][0]['detail'] ?? 'Payment initialization failed.',
        ]);
    }


    public static function ipn($request, $gateway, $deposit = null)
    {
        $secretKey = $gateway->parameters->secret_key;
        $sessionId = $deposit->payment_id;

        if (!$sessionId) {
            $data['status'] = 'error';
            $data['msg'] = 'Missing checkout session ID.';
            return $data;
        }

        $sessionUrl = "https://api.paymongo.com/v1/checkout_sessions/{$sessionId}";
        $sessionResponse = Http::withBasicAuth($secretKey, '')->get($sessionUrl);
        $sessionJson = $sessionResponse->json();

        if (!isset($sessionJson['data'])) {
            $data['status'] = 'error';
            $data['msg'] = 'Invalid checkout session response.';
            return $data;
        }

        $status = $sessionJson['data']['attributes']['payment_intent']['attributes']['status'] ?? null;

        if ($status == 'succeeded') {
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
