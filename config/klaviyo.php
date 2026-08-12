<?php

return [
    // Server-side Private API Key from Klaviyo > Settings > API Keys.
    // Never use a public/site "pk_" key here — this must be able to write events.
    'api_key' => env('KLAVIYO_API_KEY'),

    // Master switch independent of the api_key check, so events can be killed
    // in production without unsetting credentials.
    'enabled' => env('KLAVIYO_ENABLED', true),

    // Klaviyo Events API revision this integration targets.
    'revision' => env('KLAVIYO_REVISION', '2024-10-15'),
];
