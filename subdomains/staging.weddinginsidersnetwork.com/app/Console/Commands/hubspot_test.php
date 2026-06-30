<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vendor;
use App\Models\Endorsement;
use Chat;
use App\Models\Favorite;
use App\Models\Pairing;
use App\Models\Referral;
use App\Models\VendorConnection;
use App\Models\Profile;

class hubspot_test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:delete {id}';

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
        $vendor = Vendor::where('id', $this->argument('id'))->first();

        //delete conversations
        $convos = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
            ->setParticipant($vendor)
            ->page(1)
            ->get();
        foreach ($convos as $convo) {
            $convo->delete();
        }

        //remove favorites
        Favorite::where('vendor_id', $vendor->id)->delete();

        //remove referrals
        Referral::where('ref_by', $vendor->id)->delete();

        //remove reviews
        $vendor->reviews()->delete();

        //remove vendor connections
        VendorConnection::where('host_vendor', $vendor->id)->delete();
        VendorConnection::where('aff_vendor', $vendor->id)->delete();
        
        //remove pairings
        Pairing::where('vendor_id', $vendor->id)->delete();

        //delete endorsements
        $vendor->endorsements()->delete();
        Endorsement::where('endorser', $vendor->id)->delete();

        //delete meetings
        $vendor->meetings()->delete();

        //delete vendor ranking
        $vendor->vendor_ranking()->delete();

        //delete profile
        Profile::where('type', 'vendor')->where('belongs_to', $vendor->id)->delete();

        //delete vendor
        $vendor->delete();
    }
}
