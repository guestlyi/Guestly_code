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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('property_id');
            $table->integer('guest_id');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->text('information')->nullable()->comment('booking information like number of guest, number of adults, number of children, number of pets etc.');
            $table->decimal('total_amount', 10, 2);
            $table->tinyInteger('status')->default(0)->comment('0: pending, 1:confirmed, 2:cancelled, 3:completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
