<?php

return [
    // The one seeded "main admin" account — additional admins are created
    // from inside the admin dashboard itself, never via a public route.
    'default_email' => env('ADMIN_DEFAULT_EMAIL', 'admin@weddinginsidersnetwork.com'),
    'default_username' => env('ADMIN_DEFAULT_USERNAME', 'admin'),
    'default_password' => env('ADMIN_DEFAULT_PASSWORD', 'ChangeMe123!'),
];
