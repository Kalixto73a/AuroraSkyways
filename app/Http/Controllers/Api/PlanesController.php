<?php

namespace App\Http\Controllers\Api;

use App\Models\Plane;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PlanesController extends Controller
{
    public function all()
    {
        $user = JWTAuth::user();

        $planes = Plane::with(['flights.bookings.user'])->get();

        return response()->json($planes, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:planes,name|max:255',
            'max_seats' => 'required|integer|min:1'
        ]);

        $plane = Plane::create($validatedData);

        return response()->json([
            'message' => 'Avión creado exitosamente',
            'plane' => $plane
        ], 201);
    }

    public function show($id)
    {
        $plane = Plane::with(['flights.bookings.user'])->find($id);

        if (!$plane) {
            return response()->json(['message' => 'Avión no encontrado'], 404);
        }

        return response()->json($plane, 200);
    }

    public function update(Request $request, $id)
    {
        $plane = Plane::find($id);

        if (!$plane) {
            return response()->json(['message' => 'Avión no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'max_seats' => 'integer|min:1'
        ]);

        $plane->update($validatedData);

        return response()->json([
            'message' => 'Avión actualizado exitosamente',
            'plane' => $plane
        ], 200);
    }

    public function destroy($id)
    {
        $plane = Plane::find($id);

        if (!$plane) {
            return response()->json(['message' => 'Avión no encontrado'], 404);
        }

        $plane->delete();

        return response()->json(['message' => 'Avión eliminado exitosamente'], 200);
    }
}
