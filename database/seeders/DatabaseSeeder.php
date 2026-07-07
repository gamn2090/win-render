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
        // if (! app()->environment('development')) {
        //     throw new \RuntimeException('DatabaseSeeder only runs when APP_ENV=development.');
        // }

        $this->call(VendorTypesSeeder::class);
        $this->call(TagTypesSeeder::class);

        $this->call(UsersAndVendorsSeeder::class);

        $this->call(VendorNetworkDemoSeeder::class);
        $this->call(VendorCurrentClientsDemoSeeder::class);
    }
}
