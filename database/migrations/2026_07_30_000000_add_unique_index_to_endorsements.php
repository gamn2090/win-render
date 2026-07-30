<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Endorsement::upsert(..., uniqueBy: ['endorser', 'type', 'vendor_id']) relies
        // on a real unique constraint over exactly those columns — Postgres' ON
        // CONFLICT needs one to exist, and the columns were added (in
        // add_production_snapshot_columns) without one, so every endorsement
        // attempt failed with "no unique or exclusion constraint matching the
        // ON CONFLICT specification".
        if (! Schema::hasTable('endorsements')) {
            return;
        }

        $this->dropDuplicateRows();

        Schema::table('endorsements', function (Blueprint $table) {
            $table->unique(['endorser', 'type', 'vendor_id'], 'endorsements_endorser_type_vendor_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('endorsements')) {
            return;
        }

        Schema::table('endorsements', function (Blueprint $table) {
            $table->dropUnique('endorsements_endorser_type_vendor_id_unique');
        });
    }

    /**
     * Defensive cleanup so the new unique index can't fail to apply: the
     * write path (Endorsement::upsert) has always errored before ever
     * inserting a row, so this is expected to be a no-op in practice — but
     * table is tiny, so grouping in PHP is simplest and driver-agnostic.
     */
    private function dropDuplicateRows(): void
    {
        $rows = DB::table('endorsements')
            ->select('id', 'endorser', 'type', 'vendor_id')
            ->whereNotNull('endorser')
            ->whereNotNull('type')
            ->whereNotNull('vendor_id')
            ->orderBy('id')
            ->get();

        $seen = [];
        $idsToDelete = [];

        foreach ($rows as $row) {
            $key = $row->endorser . ':' . $row->type . ':' . $row->vendor_id;
            if (isset($seen[$key])) {
                $idsToDelete[] = $row->id;
            } else {
                $seen[$key] = true;
            }
        }

        if ($idsToDelete !== []) {
            DB::table('endorsements')->whereIn('id', $idsToDelete)->delete();
        }
    }
};
