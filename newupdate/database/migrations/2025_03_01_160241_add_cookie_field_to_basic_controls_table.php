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
        Schema::table('basic_controls', function (Blueprint $table) {
            $table->tinyInteger('cookie_status')->default(0);
            $table->string('cookie_heading', 255)->nullable();
            $table->text('cookie_description')->nullable();
            $table->string('cookie_button', 255)->nullable();
            $table->string('cookie_button_link', 255)->nullable();
            $table->string('cookie_image', 200)->nullable();
            $table->string('cookie_image_driver', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_controls', function (Blueprint $table) {
            //
        });
    }
};
