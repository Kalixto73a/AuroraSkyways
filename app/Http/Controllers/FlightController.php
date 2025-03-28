<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(Request $request)
    {

        $flights = Flight::with('plane')->get()
                                        ->sortByDesc('departure_date');
        
        foreach ($flights as $flight) {
            $flight->updateStatus();
        }

        return view('flightsView', compact('flights'));
    }
    
    
}
