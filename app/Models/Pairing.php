<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Vendor;

class Pairing extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'vendor_id',
        'main_connection',
        'discount_eligible',
        'approved',
        'status',
        'vendor_type'
    ];

    /** status:
     * 0: none
     * 1: inquiry sent
     * 2: meeting approved
     * 3: booked
    **/ 

    public function client(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo {
        return $this->belongsTo(Vendor::class);
    }

    public function setStatus($status){
        $this->update(['status' => $status]);
    }

    public function statusText(){
        switch($this->status){
            case 1:
                return "Inquiry";
            case 2:
                return "Consultation";
            case 3:
                return "Booked";
            default:
                return "None";
        }
    }
}
