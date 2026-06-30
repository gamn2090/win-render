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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('consultation');
            $table->unsignedBigInteger('vendor');
            $table->foreign('vendor')->references('id')->on('vendors');
            $table->unsignedBigInteger('client')->nullable();
            $table->foreign('client')->references('id')->on('users');
            $table->date('date')->nullable();
            $table->text('data')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
