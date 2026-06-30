<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'threshold'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'threshold' 
    ];
}