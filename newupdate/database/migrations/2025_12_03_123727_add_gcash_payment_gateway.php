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
        $id = 47;
        while (DB::table('gateways')->where('id', $id)->exists()) {
            $id++;
        }

        DB::table('gateways')->insert([
            'id' => $id,
            'code' => 'gcash',
            'name' => 'Gcash',
            'sort_by' => $id,
            'image' => 'gateway/demo.webp',
            'driver' => 'local',
            'status' => 1,
            'parameters' => json_encode([
                'public_key' => '',
                'secret_key' => ''
            ]),
            'currencies' => json_encode([
                "0" => [
                    'PHP' => 'PHP'
                ]
            ], JSON_FORCE_OBJECT),
            'extra_parameters' => null,
            'supported_currency' => json_encode(['PHP']),
            'receivable_currencies' => json_encode([
                [
                    'name' => 'PHP',
                    'currency_symbol' => 'PHP',
                    'conversion_rate' => '1',
                    'min_limit' => '1',
                    'max_limit' => '100000',
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
        DB::table('gateways')->where('code', 'gcash')->delete();
    }
};
