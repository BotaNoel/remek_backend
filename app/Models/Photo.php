<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id', 'url'
    ];

    // Kapcsolat az apartments táblával
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}

