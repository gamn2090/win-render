<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KlaviyoService
{
    private const API_BASE = 'https://a.klaviyo.com/api';

    /**
     * Klaviyo calls should never block app flow when unconfigured (mirrors
     * HubspotService::integrationsEnabled()).
     */
    public static function integrationsEnabled(): bool
    {
        if (App::environment(['development', 'staging'])) {
            return false;
        }

        if (! config('klaviyo.enabled', true)) {
            return false;
        }

        return filled(config('klaviyo.api_key'));
    }

    /**
     * Track an event against a Klaviyo profile, upserting the profile in the same call.
     *
     * @param  string  $metric  Event/metric name, e.g. "Vendor Booked".
     * @param  string  $email  Profile-identifying email address.
     * @param  array<string, mixed>  $properties  Event-specific properties.
     * @param  array<string, mixed>  $profileProperties  Profile fields to upsert (first_name, last_name, organization, etc).
     */
    public static function track(string $metric, string $email, array $properties = [], array $profileProperties = []): bool
    {
        if (! static::integrationsEnabled()) {
            return false;
        }

        if (blank($email)) {
            return false;
        }

        $profileAttributes = array_filter(
            array_merge(['email' => $email], $profileProperties),
            fn ($value) => filled($value)
        );

        $payload = [
            'data' => [
                'type' => 'event',
                'attributes' => [
                    'properties' => $properties,
                    'metric' => [
                        'data' => [
                            'type' => 'metric',
                            'attributes' => ['name' => $metric],
                        ],
                    ],
                    'profile' => [
                        'data' => [
                            'type' => 'profile',
                            'attributes' => $profileAttributes,
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Klaviyo-API-Key ' . config('klaviyo.api_key'),
                'revision' => config('klaviyo.revision'),
                'Accept' => 'application/json',
            ])->post(self::API_BASE . '/events/', $payload);

            if ($response->failed()) {
                Log::warning('KlaviyoService::track failed', [
                    'metric' => $metric,
                    'email' => $email,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('KlaviyoService::track exception', [
                'metric' => $metric,
                'email' => $email,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
