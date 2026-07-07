<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Deleting a user's account (ProfileController::destroy) fails with a
     * foreign key violation because inquiries.user_id and meetings.client
     * reference users.id without ON DELETE CASCADE, unlike the other
     * user-owned tables (couple_timeline_drafts, couple_investment_planner_drafts).
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE inquiries DROP CONSTRAINT inquiries_user_id_foreign');
            DB::statement('ALTER TABLE inquiries ADD CONSTRAINT inquiries_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');

            DB::statement('ALTER TABLE meetings DROP CONSTRAINT meetings_client_foreign');
            DB::statement('ALTER TABLE meetings ADD CONSTRAINT meetings_client_foreign FOREIGN KEY (client) REFERENCES users(id) ON DELETE CASCADE');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE inquiries DROP CONSTRAINT inquiries_user_id_foreign');
            DB::statement('ALTER TABLE inquiries ADD CONSTRAINT inquiries_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id)');

            DB::statement('ALTER TABLE meetings DROP CONSTRAINT meetings_client_foreign');
            DB::statement('ALTER TABLE meetings ADD CONSTRAINT meetings_client_foreign FOREIGN KEY (client) REFERENCES users(id)');
        }
    }
};
