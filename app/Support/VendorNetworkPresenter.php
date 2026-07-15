<?php

namespace App\Support;

use App\Models\Vendor;
use App\Models\VendorTypes;

class VendorNetworkPresenter
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forVendor(Vendor $host): array
    {
        return $host->connections()
            ->get()
            ->map(fn (Vendor $affiliate) => self::rowFromVendor($affiliate, true))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function rowFromVendor(Vendor $vendor, bool $includeRemove): ?array
    {
        $typeModel = $vendor->getType();
        $typeLabel = $typeModel?->type ?? 'Vendor';
        $iconPath = $typeModel?->icon;

        $businessName = trim((string) ($vendor->business_name ?? ''));
        if ($businessName === '') {
            $businessName = trim((string) ($vendor->first_name ?? '') . ' ' . (string) ($vendor->last_name ?? ''));
        }
        if ($businessName === '') {
            $businessName = 'Business Name';
        }

        $row = [
            'id' => $vendor->id,
            'uuid' => $vendor->uuid,
            'model' => $vendor,
            'type_label' => $typeLabel,
            'type_icon' => $iconPath ? asset(ltrim($iconPath, '/')) : null,
            'business_name' => $businessName,
            'location' => trim((string) ($vendor->location ?? '')) ?: '—',
            'view_url' => route('profile.vendor', ['id' => $vendor->uuid]),
        ];

        if ($includeRemove) {
            $row['remove_url'] = route('vendor.connection.remove');
            $row['aff_vendor_id'] = $vendor->id;
        }

        return $row;
    }
}
