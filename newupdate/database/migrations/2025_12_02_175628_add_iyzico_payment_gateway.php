<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $id = 40;
        while (DB::table('gateways')->where('id', $id)->exists()) {
            $id++;
        }

        DB::table('gateways')->insert([
            'id' => $id,
            'code' => 'iyzico',
            'name' => 'Iyzico',
            'sort_by' => $id,
            'image' => 'gateway/demo.webp',
            'driver' => 'local',
            'status' => 1,
            'parameters' => json_encode([
                'api_key' => '',
                'secret' => '',
                'base_url' => 'https://sandbox-api.iyzipay.com'
            ]),
            'currencies' => json_encode([
                "0" => [
                    'TRY' => 'TRY',
                    'EUR' => 'EUR',
                    'GBP' => 'GBP',
                    'RUB' => 'RUB',
                    'NOK' => 'NOK',
                    'CHF' => 'CHF',
                    'USD' => 'USD'
                ]
            ], JSON_FORCE_OBJECT),
            'extra_parameters' => null,
            'supported_currency' => json_encode(['TRY']),
            'receivable_currencies' => json_encode([
                [
                    'name' => 'TRY',
                    'currency_symbol' => 'TRY',
                    'conversion_rate' => '0.0091',
                    'min_limit' => '1',
                    'max_limit' => '10000',
                    'percentage_charge' => '0',
                    'fixed_charge' => '0'
                ]
            ]),
            'description' => 'Send from your payment gateway. Your bank may charge you a cash advance fee.',
            'currency_type' => 1,
            'is_sandbox' => 1,
            'environment' => 'test',
            'is_manual' => null,
            'note' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('gateways')->where('code', 'iyzico')->delete();
    }
};
