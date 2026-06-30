<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class VendorConnection extends Model
{
    use HasFactory;

    public function getHostVendor(){
        return Vendor::where('id', 'host_vendor')->first();
    }
}
