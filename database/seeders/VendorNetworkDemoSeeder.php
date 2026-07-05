<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Support\VendorDemoConnections;
use Illuminate\Database\Seeder;

class VendorNetworkDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('development')) {
            throw new \RuntimeException('VendorNetworkDemoSeeder only runs when APP_ENV=development.');
        }

        $vendor = Vendor::query()->orderBy('id')->first();

        if ($vendor === null) {
            $this->command?->warn('No vendors found — skip VendorNetworkDemoSeeder.');

            return;
        }

        VendorDemoConnections::seedFor($vendor);
        $this->command?->info("Demo vendor network seeded for vendor #{$vendor->id}.");
    }
}
