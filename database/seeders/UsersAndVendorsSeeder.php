<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersAndVendorsSeeder extends Seeder
{
    /**
     * Idempotent demo seeder: 3 users (couples) + 3 vendors with known
     * credentials (password: "password") so they're easy to log in with
     * for manual testing.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Emily',
                'last_name' => 'Johnson',
                'fiance_first_name' => 'Michael',
                'fiance_last_name' => 'Johnson',
                'email' => 'test.couple1@example.com',
                'wedding_date' => now()->addMonths(8)->toDateString(),
                'wedding_location' => 'Austin, TX',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'fiance_first_name' => 'David',
                'fiance_last_name' => 'Williams',
                'email' => 'test.couple2@example.com',
                'wedding_date' => now()->addMonths(5)->toDateString(),
                'wedding_location' => 'Denver, CO',
            ],
            [
                'first_name' => 'Jessica',
                'last_name' => 'Brown',
                'fiance_first_name' => 'Chris',
                'fiance_last_name' => 'Brown',
                'email' => 'test.couple3@example.com',
                'wedding_date' => now()->addMonths(11)->toDateString(),
                'wedding_location' => 'Charleston, SC',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                array_merge($user, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'in_network' => true,
                    'allow_vendor_contact' => true,
                ])
            );
        }

        $vendors = [
            [
                'type' => 'Photographer',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'business_name' => 'Smith Photography',
                'email' => 'test.vendor1@example.com',
                'discount' => 100,
            ],
            [
                'type' => 'Florist',
                'first_name' => 'Anna',
                'last_name' => 'Lee',
                'business_name' => 'Lee Florals',
                'email' => 'test.vendor2@example.com',
                'discount' => 50,
            ],
            [
                'type' => 'DJ',
                'first_name' => 'Mark',
                'last_name' => 'Davis',
                'business_name' => 'Davis DJ Services',
                'email' => 'test.vendor3@example.com',
                'discount' => 150,
            ],
        ];

        foreach ($vendors as $vendor) {
            $typeId = VendorTypes::where('type', $vendor['type'])->value('id') ?? 1;

            Vendor::firstOrCreate(
                ['email' => $vendor['email']],
                array_merge(array_diff_key($vendor, ['type' => null]), [
                    'type' => $typeId,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
