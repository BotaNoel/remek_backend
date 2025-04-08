<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id', 'postal_code', 'city', 'street', 'address_number'
    ];

    // Kapcsolat az apartments táblával
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}

