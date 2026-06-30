<?php

namespace App\Support;

use App\Models\Vendor;
use App\Models\VendorConnection;
use App\Models\VendorTypes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VendorDemoConnections
{
    /**
     * Seed demo connected vendors in local when the vendor has no approved connections yet.
     */
    public static function ensureFor(Vendor $host): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $hasConnections = VendorConnection::query()
            ->where('host_vendor', $host->id)
            ->where('approved', true)
            ->exists();

        if ($hasConnections) {
            return;
        }

        try {
            self::upsertDemoConnections($host);
        } catch (\Throwable $e) {
            Log::warning('VendorDemoConnections::ensureFor skipped: ' . $e->getMessage());
        }
    }

    /**
     * Always upsert demo connections (artisan seeder / manual QA).
     */
    public static function seedFor(Vendor $host): void
    {
        self::upsertDemoConnections($host);
    }

    private static function upsertDemoConnections(Vendor $host): void
    {
        $demos = [
            [
                'business_name' => 'Lens & Light Studio',
                'location' => 'Worcester, MA',
                'type' => 'Photographer',
                'first_name' => 'Alex',
                'last_name' => 'Rivera',
            ],
            [
                'business_name' => 'Bloom & Vine Florals',
                'location' => 'Boston, MA',
                'type' => 'Florist',
                'first_name' => 'Morgan',
                'last_name' => 'Chen',
            ],
            [
                'business_name' => 'Sweet Tier Bakery',
                'location' => 'Cambridge, MA',
                'type' => 'Bakery / Cake',
                'first_name' => 'Jamie',
                'last_name' => 'Brooks',
            ],
            [
                'business_name' => 'Rhythm & Groove DJs',
                'location' => 'Providence, RI',
                'type' => 'DJ',
                'first_name' => 'Taylor',
                'last_name' => 'Reed',
            ],
            [
                'business_name' => 'Elegant Events Co.',
                'location' => 'Hartford, CT',
                'type' => 'Wedding Planner',
                'first_name' => 'Casey',
                'last_name' => 'Walsh',
            ],
        ];

        foreach ($demos as $demo) {
            $typeModel = VendorTypes::query()->where('type', $demo['type'])->first();
            $typeId = $typeModel?->id ?? VendorTypes::query()->ordered()->value('id') ?? 1;

            $slug = Str::slug($demo['business_name']);
            $email = "demo.network.{$slug}.host{$host->id}@win.local.test";

            $affiliate = Vendor::query()->firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $demo['first_name'],
                    'last_name' => $demo['last_name'],
                    'business_name' => $demo['business_name'],
                    'location' => $demo['location'],
                    'type' => $typeId,
                    'discount' => 100,
                    'password' => Hash::make('demo-vendor-network'),
                    'image' => ProfileImageStorage::DEFAULT_FILENAME,
                ]
            );

            if ((int) $affiliate->type !== (int) $typeId) {
                $affiliate->type = $typeId;
                $affiliate->save();
            }

            VendorConnection::query()->updateOrCreate(
                [
                    'host_vendor' => $host->id,
                    'aff_vendor' => $affiliate->id,
                ],
                [
                    'host_vendor_type' => $host->type,
                    'aff_vendor_type' => $affiliate->type,
                    'approved' => true,
                ]
            );
        }
    }
}
