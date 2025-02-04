<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'departure_date',
        'arrival_date',
        'origin',
        'destination',
        'airplane_id',
        'available'
    ];

    /* public function airplane()
    {
        return $this->belongsTo(Airplane::class);
    } */
}
