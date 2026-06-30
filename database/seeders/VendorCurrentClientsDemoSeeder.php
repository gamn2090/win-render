<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Support\VendorDemoClients;
use Illuminate\Database\Seeder;

class VendorCurrentClientsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::query()->limit(10)->get();

        if ($vendors->isEmpty()) {
            $this->command?->warn('No vendors found. Create a vendor account first.');

            return;
        }

        foreach ($vendors as $vendor) {
            VendorDemoClients::seedFor($vendor);
        }

        $this->command?->info('Demo current clients seeded (emails *@win.local.test).');
    }
}
