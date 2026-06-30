<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Musonza\Chat\ConfigurationManager;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chat_message_attachments')) {
            return;
        }

        Schema::create('chat_message_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('conversation_id');

            $table->string('original_name', 255);
            $table->string('stored_path', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');

            $table->string('uploader_type', 191);
            $table->unsignedBigInteger('uploader_id');

            $table->timestamps();

            $table->index('message_id', 'cma_message_id_index');
            $table->index('conversation_id', 'cma_conversation_id_index');
            $table->index(['uploader_type', 'uploader_id'], 'cma_uploader_index');

            $table->foreign('message_id', 'cma_message_id_foreign')
                ->references('id')
                ->on(ConfigurationManager::MESSAGES_TABLE)
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('chat_message_attachments')) {
            Schema::table('chat_message_attachments', function (Blueprint $table) {
                $table->dropForeign('cma_message_id_foreign');
            });

            Schema::drop('chat_message_attachments');
        }
    }
};
