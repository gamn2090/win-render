<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\VendorTypes;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_type',
        'hits',
        'requestable'
    ];

    public function vendorType() {
        return $this->belongsTo(VendorTypes::class, 'vendor_type');
    }
}
