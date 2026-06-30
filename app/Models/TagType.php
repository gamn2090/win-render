<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class TagType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'allowed_values',
        'search_type',
        'input_type',
    ];

    public function searchValues(){
        if($this->name == "Budget"){
            return '["<$500","$500-$2,000","$2,000-$3,000","$3,000-$5,000","$5,000-$8,000","$8,000-$10,000","$10,000+"]';
        }
        if($this->name == "Event"){
            return '[' . Event::whereIn('id', json_decode($this->allowed_values))->first()->name . ']';
        }
        return $this->allowed_values;
    }
}
