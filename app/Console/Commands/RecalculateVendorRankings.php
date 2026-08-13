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
        // Trending is meant to reflect "this month" — reset the monthly
        // counter before recalculating badges, so the 1st of the month
        // starts everyone fresh instead of comparing against last month's
        // accumulated views.
        if (now()->day === 1) {
            Vendor::query()->update(['storefront_views_month' => 0]);
        }

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
