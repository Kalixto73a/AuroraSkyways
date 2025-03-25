<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flight extends Model
{
    use HasFactory;
    protected $fillable = [
        'departure_date',
        'arrival_date',
        'origin',
        'destination',
        'plane_id',
        'available'
    ];


    public function bookings()
    {
        return $this->hasMany(Booking::class, 'flight_id');
    }
}
