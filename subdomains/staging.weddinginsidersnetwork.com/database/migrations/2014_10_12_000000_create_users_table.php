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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('fiance_first_name')->nullable();
            $table->string('fiance_last_name')->nullable();
            $table->string('email')->unique();
            $table->date('wedding_date')->nullable();
            $table->boolean('in_network')->default(false);
            $table->string('wedding_location')->nullable();
            $table->string('image')->default('user.jpg');
            $table->string('password')->nullable();
            $table->string('bio')->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
