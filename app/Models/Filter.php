<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id', 'wellness', 'breakfast', 'parking', 'wifi', 'all_inclusive', 
        'near_the_beach', 'near_the_center', 'pet_friendly', 'smoking_allowed'
    ];

    // Kapcsolat az apartments táblával
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}

