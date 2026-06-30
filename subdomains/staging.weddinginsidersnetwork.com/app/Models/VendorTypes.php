<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class VendorTypes extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'icon',
    ];

    public function countVendorsWithType(){
        return Vendor::where('type', $this->id)->count();
    }

    public function getAllOrdered(){
        return VendorTypes::orderBy('priority', 'asc')->get();
    }
}
