<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [
        'type',
        'client',
        'vendor',
        'date',
        'data',
        'approved'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function readableTime(){
        return Carbon::parse($this->date)->format('m/d/Y \a\t g:i A');
    }

    public function readableDate(){
        return Carbon::parse($this->date)->format('m/d/Y');
    }

    public function readableHour(){
        return Carbon::parse($this->date)->format('g:i A');
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor');
    }

    public function client() {
        return $this->belongsTo(User::class, 'client');
    }

    public function otherParticipant($participant){
        if($participant->userType() == 'vendor'){
            return $this->client();
        } else {
            return $this->vendor();
        }
    }
}
