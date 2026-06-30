<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VendorTypes;
use App\Models\RankingCategory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Profile;

class vendor_types extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user_profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach(User::all() as $user){
            Profile::create([
                'type' => 'client',
                'belongs_to' => $user->id,
            ]);
        }

    }
}
