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
        Schema::create('vendor_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('host_vendor');
            $table->foreign('host_vendor')->references('id')->on('vendors');
            $table->unsignedBigInteger('aff_vendor');
            $table->foreign('aff_vendor')->references('id')->on('vendors');
            $table->boolean('approved')->default(false);
            $table->unsignedBigInteger('aff_vendor_type');
            $table->unsignedBigInteger('host_vendor_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_connections');
    }
};
