<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Idempotently ensures the one main admin account exists — admins are
     * never created through a public registration route; this is the only
     * account created outside the admin dashboard's own "Create Admin"
     * action, and only if no admin with this email already exists.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => config('admin.default_email')],
            [
                'username' => config('admin.default_username'),
                'password' => Hash::make(config('admin.default_password')),
            ]
        );
    }
}
