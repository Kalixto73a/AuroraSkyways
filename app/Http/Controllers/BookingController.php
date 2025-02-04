<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::all();
        /* $booking = Booking::where('user_id', )->get(); */
        return view('bookingsView', compact('bookings'));
    }
/*     public function edit(Booking $booking)
    {
        //
    }
    public function update(Request $request, Booking $booking)
    {
        //
    }
    public function destroy(Booking $booking)
    {
        //
    } */
}
