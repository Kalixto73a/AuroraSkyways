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

    protected $appends = ['remaining_capacity'];

    public function getRemainingCapacityAttribute()
    {
        return $this->plane ? $this->plane->max_seats - $this->bookings()->where('status', 'Activo')->count() : 0;
    }

    
    public function updateStatus()
    {
        $reservedSeats = $this->bookings()->where('status', 'Activo')->count();
        $remainingCapacity = $this->plane->max_seats - $reservedSeats;
    
        // Si no quedan asientos o la fecha de salida ya pasó, marcar como inactivo
        if ($remainingCapacity <= 0 || now()->greaterThanOrEqualTo($this->departure_date)) {
            $this->available = false;
        } else {
            $this->available = true;
        }
    
        $this->save();
    }
}
