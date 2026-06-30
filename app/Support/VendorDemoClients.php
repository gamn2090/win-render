<?php

namespace App\Support;

use App\Models\Pairing;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorDemoClients
{
    /**
     * Seed demo couples for the logged-in vendor in local env when they have no clients yet.
     */
    public static function ensureFor(Vendor $vendor): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $hasClients = Pairing::query()
            ->where('vendor_id', $vendor->id)
            ->where(function ($q) {
                $q->where('active', true)->orWhereNull('active');
            })
            ->exists();

        if ($hasClients) {
            return;
        }

        self::upsertDemoClients($vendor);
    }

    /**
     * Always upsert demo couples (artisan seeder / manual QA).
     */
    public static function seedFor(Vendor $vendor): void
    {
        self::upsertDemoClients($vendor);
    }

    private static function upsertDemoClients(Vendor $vendor): void
    {
        $demos = [
            [
                'first_name' => 'Brigette',
                'last_name' => 'Smith',
                'fiance_first_name' => 'Alec',
                'fiance_last_name' => 'Jones',
                'wedding_location' => 'Worcester, MA',
                'wedding_date' => '2027-12-22',
            ],
            [
                'first_name' => 'Melissa',
                'last_name' => 'Lewis',
                'fiance_first_name' => 'James',
                'fiance_last_name' => 'Carter',
                'wedding_location' => 'Boston, MA',
                'wedding_date' => '2026-09-14',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Nguyen',
                'fiance_first_name' => 'David',
                'fiance_last_name' => 'Park',
                'wedding_location' => 'Cambridge, MA',
                'wedding_date' => '2028-06-03',
            ],
        ];

        foreach ($demos as $demo) {
            $slug = Str::slug($demo['first_name'] . '-' . $demo['fiance_first_name']);
            $email = "demo.{$slug}.v{$vendor->id}@win.local.test";

            $client = User::query()->firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $demo['first_name'],
                    'last_name' => $demo['last_name'],
                    'fiance_first_name' => $demo['fiance_first_name'],
                    'fiance_last_name' => $demo['fiance_last_name'],
                    'wedding_location' => $demo['wedding_location'],
                    'wedding_date' => $demo['wedding_date'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            Pairing::query()->firstOrCreate(
                [
                    'vendor_id' => $vendor->id,
                    'client_id' => $client->id,
                ],
                [
                    'main_connection' => true,
                    'discount_eligible' => true,
                    'approved' => true,
                    'active' => true,
                    'status' => 3,
                ]
            );
        }
    }
}
