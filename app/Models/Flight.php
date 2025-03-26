<?php

namespace App\Models;

use Carbon\Carbon;
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


    public function plane()
    {
    return $this->belongsTo(Plane::class, 'plane_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'flight_id');
    }

    public function getRemainingCapacityAttribute()
    {

    $reservedSeats = $this->bookings()->where('status', 'Activo')->count();
    return $this->plane->max_seats - $reservedSeats;

    }
    
    public function updateStatus()
    {
    $totalSeats = $this->plane->max_seats;
    $reservedSeats = $this->bookings()->where('status', 'Activo')->count();
    $remainingCapacity = $totalSeats - $reservedSeats;

    if ($remainingCapacity <= 0 || Carbon::now()->greaterThanOrEqualTo($this->departure_date)) {
        // Update all bookings to 'Inactivo'
        $this->bookings()->update(['status' => 'Inactivo']);
        $this->available = false;
    } else {
        // Update all bookings to 'Activo'
        $this->bookings()->update(['status' => 'Activo']);
        $this->available = true;
    }

    $this->save();
    }
}
