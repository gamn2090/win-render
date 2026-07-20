<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('name');
            $table->longText('payload')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->index('vendor_id');
        });

        if (Schema::hasTable('vendor_timeline_drafts')) {
            $existingDrafts = DB::table('vendor_timeline_drafts')->whereNotNull('payload')->get();

            foreach ($existingDrafts as $draft) {
                DB::table('vendor_timelines')->insert([
                    'vendor_id' => $draft->vendor_id,
                    'name' => 'My Timeline',
                    'payload' => $draft->payload,
                    'created_at' => $draft->created_at,
                    'updated_at' => $draft->updated_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_timelines');
    }
};
