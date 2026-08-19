<?php

return [
    // Vendors who join within 3 months of this date earn the Early Adopter
    // badge, permanently (comparison is against their immutable created_at,
    // so it never needs to be re-evaluated once earned or lost).
    'relaunch_date' => env('RELAUNCH_DATE'),
];
