<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'vendor_type_id',
        'name',
        'value',
        'hidden'
    ];
}
