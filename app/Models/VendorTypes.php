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
        return static::ordered()->get();
    }

    /**
     * Safe ordering for MySQL/Postgres when `priority` column may be missing (fresh migrate).
     */
    public function scopeOrdered($query)
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('vendor_types', 'priority')) {
            return $query->orderBy('priority', 'asc');
        }

        return $query->orderBy('type', 'asc');
    }
}
