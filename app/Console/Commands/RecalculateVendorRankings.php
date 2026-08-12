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
