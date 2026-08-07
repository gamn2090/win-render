<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use Illuminate\Console\Command;

class GrantMonthlyContactCredits extends Command
{
    /**
     * @var string
     */
    protected $signature = 'vendors:grant-monthly-credits';

    /**
     * @var string
     */
    protected $description = 'Reset every active (paid membership) vendor to 10 contact credits for the new calendar month';

    private const MONTHLY_CREDITS = 10;

    public function handle(): int
    {
        $vendors = Vendor::all();

        $granted = 0;
        $skipped = 0;

        foreach ($vendors as $vendor) {
            if (! $vendor->isActiveMember()) {
                $skipped++;
                continue;
            }

            // Recycles monthly — this is a hard reset to 10, not additive.
            // Unused credits from last month don't roll over, matching
            // "credits recycle monthly" in the spec.
            $vendor->contact_credits = self::MONTHLY_CREDITS;
            $vendor->save();
            $granted++;
        }

        $this->info("Granted {$granted} active vendor(s) " . self::MONTHLY_CREDITS . " contact credits. Skipped {$skipped} inactive vendor(s).");

        return self::SUCCESS;
    }
}
