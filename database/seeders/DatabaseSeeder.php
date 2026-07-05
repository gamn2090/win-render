<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! app()->environment('development')) {
            throw new \RuntimeException('DatabaseSeeder only runs when APP_ENV=development.');
        }

        $this->call(VendorTypesSeeder::class);
        $this->call(TagTypesSeeder::class);

        if (\App\Models\User::count() === 0) {
            \App\Models\User::factory(10)->create();
        }

        if (\App\Models\Vendor::count() === 0) {
            \App\Models\Vendor::factory(10)->create();
        }

        $this->call(VendorNetworkDemoSeeder::class);
        $this->call(VendorCurrentClientsDemoSeeder::class);
    }
}
