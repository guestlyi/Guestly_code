<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SumsubService
{
    protected $appToken;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->appToken = config('services.sumsub.app_token');
        $this->secretKey = config('services.sumsub.secret_key');
        $this->baseUrl = 'https://api.sumsub.com';
    }

    // Generate applicant token
    public function createAccessToken(array $applicantData)
    {
        $ts = time();
        $urlPath = "/resources/accessTokens/sdk";
        $method = 'POST';
        $body = json_encode($applicantData);
        $signature = hash_hmac('sha256', $ts . $method . $urlPath . $body, $this->secretKey);

        $response = Http::withHeaders([
            'X-App-Token' => $this->appToken,
            'X-App-Access-Sig' => $signature,
            'X-App-Access-Ts' => $ts,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . $urlPath, $applicantData);

        if (isset($response->json()['token'])) {
            return $response->json()['token'];
        }
        return null;
    }
}
