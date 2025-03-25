@extends('layouts.index')
@section('content')
<div class="relative h-screen bg-black flex justify-center items-center font-[K2D]">
    <!-- Imagen de fondo -->
    <img 
      src="https://res.cloudinary.com/dzfqdntdw/image/upload/v1738684129/imagen_2025-02-04_164846399_zowfpl.png" 
      alt="Avión en el cielo" 
      class="absolute inset-0 w-full h-full object-cover opacity-50"
    />
    <div class="max-w-6xl mx-auto py-8 px-4 z-10 w-[40rem] max-h-[30rem] overflow-y-scroll no-scrollbar rounded-lg shadow-lg bg-[#53CCDC]">
        <table class="w-full text-white">
            <thead>
                <tr class="bg-black">
                    <th class="border-r border-b border-gray-300 px-4 py-2 text-left rounded-tl-lg">Avión</th>
                    <th class="border-x border-b border-gray-300 px-4 py-2 text-left">Asientos</th>
                    <th class="border-l border-b border-gray-300 px-4 py-2 text-left rounded-tr-lg">Vuelos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($planes as $plane)
                <tr class="bg-black cursor-pointer hover:bg-gray-900" onclick="toggleFlightList('{{ $plane->id }}')">
                    <td class="border border-gray-300 px-4 py-2 font-bold">{{ $plane->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $plane->max_seats }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $plane->flights->count() }}</td>
                </tr>
                
                <!-- Filas ocultas para mostrar los vuelos -->
                <tr id="flight-list-{{ $plane->id }}" class="hidden bg-black">
                    <td colspan="3">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-black">
                                    <th class="border border-gray-300 px-4 py-2 text-left">Origen</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Destino</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Fecha de Salida</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Pasajeros</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plane->flights as $flight)
                                <tr class="bg-white cursor-pointer hover:bg-gray-200" onclick="togglePassengerList('{{ $flight->id }}')">
                                    <td class="bg-black border border-gray-300 px-4 py-2">{{ $flight->origin }}</td>
                                    <td class="bg-black border border-gray-300 px-4 py-2">{{ $flight->destination }}</td>
                                    <td class="bg-black border border-gray-300 px-4 py-2">{{ $flight->departure_date }} </td>
                                    <td class="bg-black border border-gray-300 px-4 py-2">{{ $flight->bookings->where('status', 'Activo')->count() }}</td>
                                </tr>
    
                                <!-- Filas ocultas para mostrar los pasajeros -->
                                <tr id="passenger-list-{{ $flight->id }}" class="hidden bg-gray-100">
                                    <td colspan="4">
                                        <table class="w-full border-collapse border border-gray-200">
                                            <thead>
                                                <tr class="bg-black">
                                                    <th class="bg-black border border-gray-300 px-4 py-2 text-left">Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($flight->bookings as $booking)
                                                <tr class="bg-black">
                                                    @if ($booking->status == 'Activo')
                                                        <td class="bg-black border border-gray-300 px-4 py-2">{{ $booking->user->name }}</td>
                                                    @endif
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="border border-gray-300 px-4 py-2 text-center bg-black">No hay pasajeros</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    
    <script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("El script está funcionando");

    window.toggleFlightList = function(planeId) {
        let flightList = document.getElementById(`flight-list-${planeId}`);
        if (flightList) {
            flightList.classList.toggle('hidden');
        } else {
            console.error("No se encontró la lista de vuelos:", flightList);
        }
    };

    window.togglePassengerList = function(flightId) {
        let passengerList = document.getElementById(`passenger-list-${flightId}`);
        if (passengerList) {
            passengerList.classList.toggle('hidden');
        } else {
            console.error("No se encontró la lista de pasajeros:", passengerList);
        }
    };
});

    </script>
@endsection