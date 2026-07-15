<?php

namespace App\Support;

use App\Models\Pairing;
use App\Models\Vendor;
use Carbon\Carbon;

class VendorClientsPresenter
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forVendor(Vendor $vendor, bool $activeOnly = true): array
    {
        $query = Pairing::query()
            ->where('vendor_id', $vendor->id)
            ->with('client');

        if ($activeOnly) {
            $query->where(function ($q) {
                $q->where('active', true)->orWhereNull('active');
            });
        } else {
            $query->where('active', false);
        }

        return $query->get()
            ->map(fn (Pairing $pairing) => self::rowFromPairing($pairing, $vendor))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, string>>
     */
    public static function csvRowsForVendor(Vendor $vendor): array
    {
        return array_map(static function (array $row): array {
            return [
                'Status' => (string) ($row['status_label'] ?? 'Active'),
                'Client First Name' => (string) ($row['first_name'] ?? ''),
                'Fiance Name' => (string) ($row['fiance_name'] ?? ''),
                'Email' => (string) ($row['email'] ?? ''),
                'Wedding Location' => ($row['wedding_location'] ?? '') === '—' ? '' : (string) ($row['wedding_location'] ?? ''),
                'Wedding Date' => ($row['wedding_date'] ?? '') === '—' ? '' : (string) ($row['wedding_date'] ?? ''),
            ];
        }, self::forVendor($vendor));
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function rowFromPairing(Pairing $pairing, Vendor $vendor): ?array
    {
        $client = $pairing->client;
        if ($client === null) {
            return null;
        }

        $isActive = $pairing->active === null || (bool) $pairing->active;
        $fianceName = trim((string) ($client->fiance_first_name ?? ''));

        return [
            'id' => $client->id,
            'uuid' => $client->uuid,
            'model' => $client,
            'first_name' => trim((string) ($client->first_name ?? '')),
            'fiance_name' => $fianceName,
            'email' => trim((string) ($client->email ?? '')),
            'wedding_location' => $client->wedding_location ?: '—',
            'wedding_date' => self::formatWeddingDate($client->wedding_date),
            'is_active' => $isActive,
            'status_label' => $isActive ? 'Active' : 'Archived',
            'action_url' => $isActive
                ? route('vendor.archive.client', ['id' => $client->id, 'ven_id' => $vendor->id])
                : route('vendor.unarchive.client', ['id' => $client->id, 'ven_id' => $vendor->id]),
            'action_label' => $isActive ? 'Archive' : 'Unarchive',
            'view_url' => route('vendor.couple.profile', ['id' => $client->uuid]),
        ];
    }

    private static function formatWeddingDate(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        try {
            return Carbon::parse($value)->format('m-d-Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    }
}
