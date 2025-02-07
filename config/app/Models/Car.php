<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = ['name', 'city_id', 'availability_status', 'price_per_day'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
