<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Apartment_type;
use App\Models\User;
use App\Models\Filter;
use App\Models\Photo;
use App\Models\Location;
use App\Models\Comment;
use App\Models\Order;


class Apartment extends Model
{
    use HasFactory;

    // A fillable tömb beállítása az apartman adataihoz
    protected $fillable = [
        'user_id', 'type_id', 'name', 'max_capacity', 'description', 'price_per_night'
    ];

    // Kapcsolat a user táblával
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kapcsolat az apartment_types táblával
    public function type()
    {
        return $this->belongsTo(Apartment_type::class, 'type_id');
    }

    // Kapcsolat a filters táblával
    public function filters()
    {
        return $this->hasOne(Filter::class);
    }

    // Kapcsolat a photos táblával
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // Kapcsolat a locations táblával
    public function location()
    {
        return $this->hasOne(Location::class);
    }

    // Kapcsolat a comments táblával
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Kapcsolat az orders táblával
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class); // vagy amilyen osztályneved van
    }
}

