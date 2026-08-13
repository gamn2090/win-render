<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * The 4 badges vendors can earn (Vendor::trendingBadge(), earlyAdopterBadge(),
     * fastResponderBadge(), communityBuilderBadge()). IDs must stay pinned to
     * 1-4 — VendorService::refreshEarnedBadges() hardcodes those ids when
     * recording a vendor's earned badges. Uses the query builder (not the
     * Badge model) so the explicit id isn't silently dropped by mass-assignment
     * guarding. Idempotent so it can run alongside DatabaseSeeder without
     * duplicating rows.
     */
    public function run(): void
    {
        $badges = [
            ['id' => 1, 'name' => 'Trending', 'icon' => 'trending-badge.png', 'threshold' => 15],
            ['id' => 2, 'name' => 'Early Adopter', 'icon' => 'early-adopter-badge.png', 'threshold' => 6],
            ['id' => 3, 'name' => 'Fast Responder', 'icon' => 'fast-responder-badge.png', 'threshold' => null],
            ['id' => 4, 'name' => 'Community Builder', 'icon' => 'community-builder-badge.png', 'threshold' => 15],
        ];

        foreach ($badges as $badge) {
            $fields = [
                'name' => $badge['name'],
                'icon' => $badge['icon'],
                'threshold' => $badge['threshold'],
                'updated_at' => now(),
            ];

            if (DB::table('badges')->where('id', $badge['id'])->exists()) {
                DB::table('badges')->where('id', $badge['id'])->update($fields);
            } else {
                DB::table('badges')->insert($fields + ['id' => $badge['id'], 'created_at' => now()]);
            }
        }
    }
}
