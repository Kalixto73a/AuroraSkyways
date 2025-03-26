<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        $bookings = Booking::where('user_id', Auth::user()->id)->get();

        return view('bookingsView', compact('bookings'));
    }

    public function store(Request $request)
    {
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'flight_id' => $request->flight_id,
            'plane_id' => $request->plane_id,
            'seat_number' => $request->seat_number,
            'status' => 'Activo'
    ]);

    $booking->flight->updateStatus();

    return redirect()->back()->with('success', 'Reserva realizada correctamente');
    }
}
