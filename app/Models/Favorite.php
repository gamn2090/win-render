<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id'
    ];

    public function vendor(){
        return Vendor::where('id', $this->vendor_id)->first();
    }
}
