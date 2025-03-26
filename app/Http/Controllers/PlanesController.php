<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Booking;
use App\Models\Plane;
use Illuminate\Http\Request;

class PlanesController extends Controller
{
    public function index()
    {
        $planes = Plane::with('flights.bookings.user')->get();
        return view('planesView', compact('planes'));
    }   
}