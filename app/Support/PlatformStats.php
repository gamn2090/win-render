<?php

namespace App\Support;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Cache;

/**
 * Real signup counts for marketing copy ("3,400+ couples on WIN"), so those
 * numbers track actual growth instead of being hand-edited constants that
 * drift out of sync with reality.
 */
class PlatformStats
{
    public static function coupleCount(): int
    {
        return Cache::remember('platform_stats_couple_count', 3600, fn () => User::count());
    }

    public static function vendorCount(): int
    {
        return Cache::remember('platform_stats_vendor_count', 3600, fn () => Vendor::where('visible', 1)->count());
    }

    /**
     * Rounds down to a "nicer" number for display (e.g. 3,427 -> 3,400,
     * 1,847 -> 1,800) by keeping the top 2 significant digits and zeroing
     * the rest — this is why the "+" suffix in copy like "3,400+" always
     * stays true, and the number doesn't look like an oddly-precise stat.
     */
    public static function roundDownForDisplay(int $n): int
    {
        if ($n < 10) {
            return $n;
        }

        $digits = strlen((string) $n);
        $step = (int) max(10, 10 ** max(0, $digits - 2));

        return intdiv($n, $step) * $step;
    }

    public static function coupleCountDisplay(): string
    {
        return number_format(self::roundDownForDisplay(self::coupleCount()));
    }

    public static function vendorCountDisplay(): string
    {
        return number_format(self::roundDownForDisplay(self::vendorCount()));
    }
}
