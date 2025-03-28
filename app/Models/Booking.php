<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Pivot
{
    use HasFactory;
     protected $table = 'bookings';
    protected $fillable = [
        'id',
        'user_id',
        'flight_id',
        'seat_number',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }   

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

}
