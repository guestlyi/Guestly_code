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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('username', 100)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('country_code', 20)->nullable();
            $table->string('country', 191)->nullable();
            $table->string('state', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->string('phone_code', 20)->nullable();
            $table->string('phone', 191)->nullable();
            $table->decimal('balance', 11, 8)->nullable();
            $table->string('image')->nullable();
            $table->string('image_driver')->nullable();
            $table->string('password', 191)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('time_zone', 191)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_seen')->nullable();
            $table->dateTime('password_updated')->nullable();
            $table->integer('total_click')->default(0)->comment('Total affiliate click');
            $table->tinyInteger('is_affiliatable')->default(0)->comment('0 => No, 1 => Yes');
            $table->tinyInteger('status')->default(1)->comment('0 => Inactive, 1 => Active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
