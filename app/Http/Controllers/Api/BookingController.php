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
                // Obtener el usuario autenticado
                $user = JWTAuth::user();
            
                // Recuperar todos los vuelos en los que el usuario tiene reservas con el ID del booking
                $flights = Flight::whereHas('bookings', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->with(['bookings' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)->select('id', 'flight_id', 'user_id'); // Selecciona solo los campos relevantes
                }])->get();
            
                // Verificar si hay vuelos disponibles
                if ($flights->isNotEmpty()) {
                    return response()->json($flights, 200);
                }
            
                return response()->json(['message' => 'No hay vuelos reservados'], 404);
    }

    public function store(Request $request)
    {
            // Validar los datos recibidos
        $validatedData = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'plane_id'  => 'required|exists:planes,id',
            'seat_number' => 'required',
            'status'    => 'required|in:Activo',
        ]);

        // Obtener el usuario autenticado
        $user = JWTAuth::user(); 

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        // Crear la reserva
        $booking = Booking::create([
            'user_id'   => $user->id,  // Se obtiene el ID del usuario autenticado
            'flight_id' => $validatedData['flight_id'],
            'plane_id'  => $validatedData['plane_id'],
            'seat_number' => $validatedData['seat_number'],
            'status'    => $validatedData['status'],
        ]);

        return response()->json(['message' => 'Reserva creada exitosamente', 'booking' => $booking], 201);
    }

    public function show(Request $request, $id)
    {
        $user = JWTAuth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
    
        // Buscar la reserva y verificar si pertenece al usuario autenticado
        $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();
    
        if (!$booking) {
            return response()->json(['message' => 'Reserva no encontrada o no autorizada'], 403);
        }
    
        return response()->json(['booking' => $booking], 200);
    }

    public function update(Request $request, $id)
    {
    // Obtener usuario autenticado
    $user = JWTAuth::user();

    // Buscar la reserva del usuario
    $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();

    if (!$booking) {
        return response()->json(['message' => 'Reserva no encontrada o no tienes permiso'], 403);
    }

    // Validar datos
    $request->validate([
        'flight_id' => 'required|exists:flights,id',
        'status' => 'required|in:Activo,Inactivo',
    ]);

    // Actualizar datos
    $booking->flight_id = $request->flight_id ?? $booking->flight_id;
    $booking->status = $request->status ?? $booking->status;

    // Verificar si hay cambios antes de guardar
    if ($booking->isDirty()) {
        $booking->updated_at = now(); 
        $booking->save();
        return response()->json(['message' => 'Reserva actualizada correctamente', 'booking' => $booking]);
    }

    return response()->json(['message' => 'No se realizaron cambios', 'booking' => $booking], 200);
    }

    public function destroy($id)
    {   
        $user = JWTAuth::user(); // Obtener usuario autenticado

        $booking = Booking::where('id', $id)->where('user_id', $user->id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Reserva no encontrada o no tienes permiso'], 403);
        }

        $booking->delete(); // Eliminar la reserva

        return response()->json(['message' => 'Reserva eliminada correctamente'], 200);
    }
}
