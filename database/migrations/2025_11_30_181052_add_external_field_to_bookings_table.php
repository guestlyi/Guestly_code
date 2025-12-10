<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('external_calendar_uid')->nullable()->after('affiliate_user_id')->index();
            $table->boolean('is_external')->default(false)->after('external_calendar_uid');
            $table->string('external_source')->nullable()->after('is_external');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['external_calendar_uid', 'is_external', 'external_source']);
        });
    }
};
