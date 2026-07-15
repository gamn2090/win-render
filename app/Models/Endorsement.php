<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class Endorsement extends Model
{
    use HasFactory;

    public function typeName(){
        switch($this->type){
            case 1:
                return 'Responsive';
            case 2:
                return 'Professional';
            case 3:
                return 'Communicative';
            case 4:
                return 'Creative';
            case 5:
                return 'Resourceful';
            case 6:
                return 'Personable';
            default:
                return 'Unknown';
        }
    }

    public function endorserTypePictures(){
        return Endorsement::where('type', $this->type)->where('vendor_id', $this->vendor_id)->take(3);
    }

    public function endorserPicture(){
        return $this->hasOne(Vendor::class, 'id', 'endorser')->get(['id', 'first_name', 'last_name', 'business_name', 'image']);
    }
}
