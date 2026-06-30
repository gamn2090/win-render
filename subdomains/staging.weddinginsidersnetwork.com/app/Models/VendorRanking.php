<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorRanking extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_id',
        'client_community',
        "vendor_community",
        'reviews',
        'vendor_referral',
        'endorsements',
        'badges'
    ];
}
