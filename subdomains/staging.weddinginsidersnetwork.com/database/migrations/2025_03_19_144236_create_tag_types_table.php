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
        Schema::create('tag_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_type_id')->constrained();
            $table->string('name');
            $table->string('allowed_values')->default('[]'); // JSON encoded array of allowed values
            $table->string('input_type')->default('select'); // default to 'select', can be 'text', 'checkbox', etc.
            $table->string('search_type')->default('select'); // default to 'select', can be 'text', 'checkbox', etc.
            $table->boolean('is_required')->default(false);
            $table->boolean('hidden')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_types');
    }
};
