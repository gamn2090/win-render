<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoupleInvestmentPlannerDraft extends Model
{
    protected $fillable = [
        'user_id',
        'payload',
    ];
}

