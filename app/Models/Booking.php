<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Pivot
{
    /* protected $table = 'bookings';
    protected $fillable = [
        'user_id',
        'flight_id',
        'seat_number',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    } */
}
