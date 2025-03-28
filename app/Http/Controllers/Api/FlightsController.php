<?php

namespace App\Http\Controllers\Api;

use App\Models\Flight;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlightsController extends Controller
{
    public function index()
    {
        $flights = Flight::all();

        return response()->json($flights, 200);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'departure_date' => 'required|date|after:now',
            'arrival_date' => 'required|date|after:departure_date',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'plane_id' => 'required|exists:planes,id',
            'available' => 'required|boolean',
        ]);
    
        // Verificar si ya existe un vuelo con el mismo avión y fechas
        $existingFlight = Flight::where('plane_id', $validatedData['plane_id'])
            ->where('departure_date', $validatedData['departure_date'])
            ->where('arrival_date', $validatedData['arrival_date'])
            ->exists();
    
        if ($existingFlight) {
            return response()->json([
                'error' => 'Ya existe un vuelo con el mismo avión y las mismas fechas.'
            ], 422);
        }
        if ($validatedData['available'] == 0){
            return response()->json([
                'error' => 'No puedes crear un vuelo que no este activo'
            ], 422);
        }
        $flight = Flight::create($validatedData);

        $formattedFlight = [
            'id' => $flight->id,
            'remaining_capacity' => $flight->remaining_capacity,
            'departure_date' => $flight->departure_date,
            'arrival_date' => $flight->arrival_date,
            'origin' => $flight->origin,
            'destination' => $flight->destination,
            'plane_id' => $flight->plane_id,
            'available' => $flight->available,
            'updated_at' => $flight->updated_at,
            'created_at' => $flight->created_at,
            'plane' => $flight->plane
        ];

        return response()->json([
            'message' => 'Vuelo creado exitosamente',
            'flight' => $formattedFlight
        ], 201);
    }

    public function show($id)
    {

        $flight = Flight::find($id);

        if (!$flight) {
            return response()->json(['message' => 'Vuelo no encontrado'], 404);
        }

        return response()->json($flight, 200);
    }

    public function update(Request $request, $id)
    {

        $flight = Flight::find($id);

        if (!$flight) {
            return response()->json(['message' => 'Vuelo no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'departure_date' => 'nullable|date|after:now',
            'arrival_date' => 'nullable|date|after:departure_date',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'plane_id' => 'nullable|exists:planes,id',
            'available' => 'nullable|boolean',
        ]);

        $flight->update($validatedData);

        return response()->json([
            'message' => 'Vuelo actualizado exitosamente',
            'flight' => $flight
        ], 200);
    }

    public function destroy($id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return response()->json(['message' => 'Vuelo no encontrado'], 404);
        }

        $flight->delete();

        return response()->json(['message' => 'Vuelo eliminado exitosamente'], 200);
    }
}
