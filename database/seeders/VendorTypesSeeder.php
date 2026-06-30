<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VendorTypesSeeder extends Seeder
{
    /**
     * Idempotent seeder for vendor categories.
     *
     * - Portable across MySQL and PostgreSQL (uses Laravel's DB facade only).
     * - Safe to re-run: matches by `type` (unique business key) and updates
     *   priority + icon. New rows are inserted, existing ones are updated.
     * - Does NOT touch existing vendors that may reference `vendor_types.id`.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            ['type' => 'Florist',                  'priority' => 198, 'icon' => '/ico/florist.svg'],
            ['type' => 'Venue',                    'priority' => 0,   'icon' => '/ico/venue.svg'],
            ['type' => 'Bakery / Cake',            'priority' => 16,  'icon' => '/ico/cake.svg'],
            ['type' => 'Caterer',                  'priority' => 14,  'icon' => '/ico/caterer.svg'],
            ['type' => 'DJ',                       'priority' => 10,  'icon' => '/ico/music.svg'],
            ['type' => 'Live Bands',               'priority' => 22,  'icon' => '/ico/music.svg'],
            ['type' => 'Hair & Makeup',            'priority' => 8,   'icon' => '/ico/hair.svg'],
            ['type' => 'Officiant',                'priority' => 26,  'icon' => '/ico/officiant.svg'],
            ['type' => 'Photographer',             'priority' => 6,   'icon' => '/ico/camera.svg'],
            ['type' => 'Rentals & Decor',          'priority' => 18,  'icon' => '/ico/rentals.svg'],
            ['type' => 'Transportation',           'priority' => 28,  'icon' => '/ico/transportation.svg'],
            ['type' => 'Wedding Planner',          'priority' => 4,   'icon' => '/ico/plan.svg'],
            ['type' => 'Videographer',             'priority' => 12,  'icon' => '/ico/video.svg'],
            ['type' => 'Jewelers',                 'priority' => 32,  'icon' => '/ico/jewel.svg'],
            ['type' => 'Photo Booth',              'priority' => 24,  'icon' => '/ico/photo-booth.svg'],
            ['type' => 'Bar Services',             'priority' => 20,  'icon' => '/ico/bar.svg'],
            ['type' => 'Content Creators',         'priority' => 30,  'icon' => '/ico/video.svg'],
            ['type' => 'Other',                    'priority' => 200, 'icon' => '/ico/plan.svg'],
            ['type' => 'Invitations / Stationery', 'priority' => 21,  'icon' => '/ico/stationery.svg'],
            ['type' => 'Bridal Shops / Tux Rental','priority' => 23,  'icon' => '/ico/dress.svg'],
            ['type' => 'String Ensembles',         'priority' => 25,  'icon' => '/ico/violin.svg'],
            ['type' => 'Live Artists / Painters',  'priority' => 34,  'icon' => '/ico/painter.svg'],
        ];

        foreach ($rows as $row) {
            $existing = DB::table('vendor_types')->where('type', $row['type'])->first();

            if ($existing) {
                DB::table('vendor_types')
                    ->where('id', $existing->id)
                    ->update([
                        'priority' => $row['priority'],
                        'icon' => $row['icon'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('vendor_types')->insert([
                    'type' => $row['type'],
                    'priority' => $row['priority'],
                    'icon' => $row['icon'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
