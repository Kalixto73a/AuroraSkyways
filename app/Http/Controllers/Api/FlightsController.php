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

        $flight = Flight::create($validatedData);

        return response()->json([
            'message' => 'Vuelo creado exitosamente',
            'flight' => $flight
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
