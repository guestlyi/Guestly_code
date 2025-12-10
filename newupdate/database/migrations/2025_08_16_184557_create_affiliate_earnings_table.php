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
        Schema::create('affiliate_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->index()->nullable();
            $table->foreignId('property_id')->index()->nullable();
            $table->decimal('amount', 10,2)->default(0);
            $table->dateTime('payment_release_date')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1 = completed, 0 = pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_earnings');
    }
};
