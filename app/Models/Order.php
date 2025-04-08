<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'apartment_id', 'arrival_date', 'departure_date', 'total_price', 'headcount', 'status'
    ];

    // Kapcsolat a user táblával
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kapcsolat az apartments táblával
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}

