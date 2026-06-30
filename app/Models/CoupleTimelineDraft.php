<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoupleTimelineDraft extends Model
{
    protected $fillable = [
        'user_id',
        'payload',
    ];
}
