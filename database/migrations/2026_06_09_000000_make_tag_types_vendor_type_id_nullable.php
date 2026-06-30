<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * tag_types id=11 ("Event") uses vendor_type_id=null in snapshot/MySQL;
     * PostgreSQL failed with NOT NULL on SnapshotSeeder import.
     */
    public function up(): void
    {
        if (! Schema::hasTable('tag_types')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE tag_types DROP CONSTRAINT IF EXISTS tag_types_vendor_type_id_foreign');
            DB::statement('ALTER TABLE tag_types ALTER COLUMN vendor_type_id DROP NOT NULL');
            DB::statement('ALTER TABLE tag_types ADD CONSTRAINT tag_types_vendor_type_id_foreign FOREIGN KEY (vendor_type_id) REFERENCES vendor_types(id) ON DELETE SET NULL');
        } elseif (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE tag_types MODIFY vendor_type_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('tag_types')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE tag_types DROP CONSTRAINT IF EXISTS tag_types_vendor_type_id_foreign');
            DB::statement('UPDATE tag_types SET vendor_type_id = (SELECT id FROM vendor_types ORDER BY id LIMIT 1) WHERE vendor_type_id IS NULL');
            DB::statement('ALTER TABLE tag_types ALTER COLUMN vendor_type_id SET NOT NULL');
            DB::statement('ALTER TABLE tag_types ADD CONSTRAINT tag_types_vendor_type_id_foreign FOREIGN KEY (vendor_type_id) REFERENCES vendor_types(id) ON DELETE CASCADE');
        } elseif (DB::getDriverName() === 'mysql') {
            DB::statement('UPDATE tag_types SET vendor_type_id = (SELECT id FROM vendor_types ORDER BY id LIMIT 1) WHERE vendor_type_id IS NULL');
            DB::statement('ALTER TABLE tag_types MODIFY vendor_type_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
