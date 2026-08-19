<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Console\Command;

class RecalculateVendorRankings extends Command
{
    /**
     * @var string
     */
    protected $signature = 'vendors:recalculate-rankings';

    /**
     * @var string
     */
    protected $description = 'Recalculate every vendor\'s WINfluence badges and ranking scores, so they stay fresh without needing to log in';

    public function handle(VendorService $vendorService): int
    {
        // Reset cyclical badge counters BEFORE recalculating, so today's/this
        // week's activity isn't compared against a stale prior-period count.
        if (now()->day === 1) {
            Vendor::query()->update(['storefront_views_month' => 0]);
        }
        // Trending badge: recalculates weekly.
        if (now()->isMonday()) {
            Vendor::query()->update(['storefront_views_week' => 0]);
        }
        // Fast Responder badge: an ongoing daily cycle — a vendor who stops
        // responding within 24h loses it the next day until they qualify again.
        Vendor::query()->update(['fast_responses_today' => 0]);

        $count = 0;

        Vendor::chunk(100, function ($vendors) use ($vendorService, &$count) {
            foreach ($vendors as $vendor) {
                $vendorService->refreshEarnedBadges($vendor);
                $vendor->updateAllRankingScores();
                $count++;
            }
        });

        $this->info("Recalculated WINfluence rankings for {$count} vendor(s).");

        return self::SUCCESS;
    }
}
