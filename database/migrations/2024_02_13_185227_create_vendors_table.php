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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->string('business_name')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('image')->default('user.jpg');
            $table->integer('discount');
            $table->integer('yearly_weddings')->nullable();
            $table->string('location')->nullable();
            $table->string('service_radius')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('google_place_id')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('ref_by')->nullable();
            $table->foreign('ref_by')->references('id')->on('vendors');
            $table->integer('storefront_views')->default(0);
            $table->integer('fast_responses')->default(0);
            $table->integer('slow_responses')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
