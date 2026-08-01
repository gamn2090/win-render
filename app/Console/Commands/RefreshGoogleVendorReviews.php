<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Console\Command;

class RefreshGoogleVendorReviews extends Command
{
    /**
     * @var string
     */
    protected $signature = 'vendors:refresh-google-reviews';

    /**
     * @var string
     */
    protected $description = 'Re-fetch Google rating, review count, and reviews for every vendor with a linked Google Place';

    public function handle(VendorService $vendorService): int
    {
        $vendors = Vendor::whereNotNull('google_place_id')->get();

        $this->info("Refreshing Google data for {$vendors->count()} vendor(s)...");

        $refreshed = 0;
        $failed = 0;

        foreach ($vendors as $vendor) {
            if ($vendorService->syncGooglePlaceData($vendor)) {
                $refreshed++;
            } else {
                $failed++;
                $this->warn("Failed to refresh vendor #{$vendor->id} ({$vendor->business_name})");
            }

            // Stay well under Google's rate limits when refreshing many vendors in one run.
            usleep(200_000);
        }

        $this->info("Done. Refreshed: {$refreshed}, Failed: {$failed}");

        return self::SUCCESS;
    }
}
