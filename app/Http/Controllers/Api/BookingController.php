<?php

namespace App\Http\Controllers\Api;

use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class BookingController extends Controller
{
    public function all()
    {
        $user = JWTAuth::user();

        $flights = Flight::whereHas('bookings', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['bookings' => function ($query) use ($user) {
            $query->where('user_id', $user->id)->select('id', 'flight_id', 'user_id');
        }])->get();

        if ($flights->isNotEmpty()) {
            return response()->json($flights, 200);
        }

        return response()->json(['message' => 'No hay vuelos reservados'], 404);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'seat_number' => 'required',
            'status'    => 'required|in:Activo',
        ]);

        $user = JWTAuth::user(); 

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
        
        $existingBooking = Booking::where('user_id', $user->id)
        ->where('flight_id', $validatedData['flight_id'])
        ->first();

        if ($existingBooking) {
            return response()->json(['message' => 'Ya tienes una reserva en este vuelo'], 400);
        }
        
        $booking = Booking::create([
            'user_id'   => $user->id,
            'flight_id' => $validatedData['flight_id'],
            'seat_number' => $validatedData['seat_number'],
            'status'    => $validatedData['status'],
        ]);

        $lastBookingFromUser = Booking::where('user_id', $user->id)
        ->latest('created_at')
        ->first();

        return response()->json(['message' => 'Reserva creada exitosamente', 'booking_id' => $lastBookingFromUser->id, 'booking' => $booking], 201);
    }

    public function show(Request $request, $id)
    {
        $user = JWTAuth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
    
        $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();
    
        if (!$booking) {
            return response()->json(['message' => 'Reserva no encontrada o no autorizada'], 403);
        }
    
        return response()->json(['booking' => $booking], 200);
    }

    public function update(Request $request, $id)
    {
 
    $user = JWTAuth::user();

    $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();

    if (!$booking) {
        return response()->json(['message' => 'Reserva no encontrada o no tienes permiso'], 403);
    }

    $request->validate([
        'flight_id' => 'required|exists:flights,id',
        'status' => 'required|in:Activo,Inactivo',
    ]);

    $booking->flight_id = $request->flight_id ?? $booking->flight_id;
    $booking->status = $request->status ?? $booking->status;

    if ($booking->isDirty()) {
        $booking->updated_at = now(); 
        $booking->save();
        return response()->json(['message' => 'Reserva actualizada correctamente', 'booking' => $booking]);
    }

    return response()->json(['message' => 'No se realizaron cambios', 'booking' => $booking], 200);
    }

    public function destroy($id)
    {   
        $user = JWTAuth::user();

        $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Reserva no encontrada o no tienes permiso'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Reserva eliminada correctamente'], 200);
    }
}
