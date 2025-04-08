<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'apartment_id', 'rating_id', 'comment'
    ];

    // Kapcsolat a user táblával
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}
