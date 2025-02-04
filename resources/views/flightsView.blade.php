@extends('layouts.index')
@section('content')
<div class="relative h-screen bg-black flex justify-center items-center font-[K2D]">
    <!-- Imagen de fondo -->
    <img 
      src="https://res.cloudinary.com/dzfqdntdw/image/upload/v1738684129/imagen_2025-02-04_164846399_zowfpl.png" 
      alt="Avión en el cielo" 
      class="absolute inset-0 w-full h-full object-cover opacity-50"
    />
  
    <!-- Contenedor principal -->
    <div class="relative z-10 w-11/12 max-w-4xl bg-white/90 rounded-xl shadow-lg max-h-[30rem] overflow-y-scroll no-scrollbar">
      <!-- Encabezado -->
      <div class="flex justify-between items-center bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-t-xl p-4 sticky top-0">
        <button class="bg-white text-cyan-500 font-bold py-2 px-4 rounded-full shadow-md hover:scale-110 transition transition-transform duration-500">Actuales</button>
        <h1 class="text-2xl font-bold">VUELOS</h1>
        <button class="bg-white text-cyan-500 font-bold py-2 px-4 rounded-full shadow-md hover:scale-110 transition transition-transform duration-500">Antiguos</button>
      </div>
  
      <!-- Lista de vuelos -->
      <div class="p-4 space-y-4">
        @foreach ($flights as $flight)
        <div class="flex items-center justify-between bg-white rounded-lg shadow-md p-4">
          <!-- Icono -->
          <div class="flex items-center space-x-4">
            <div class="bg-cyan-500 text-white rounded-full p-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.894 2.553a1 1 0 00-1.788 0L7.082 7.62l-5.281.767a1 1 0 00-.554 1.706l3.82 3.72-.9 5.263a1 1 0 001.45 1.054L10 17.862l4.715 2.479a1 1 0 001.45-1.054l-.9-5.263 3.82-3.72a1 1 0 00-.554-1.706l-5.281-.767-2.024-5.067z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-bold">IDA: {{$flight->departure_date}}</p>
              <p class="text-sm font-bold">VUELTA: {{$flight->arrival_date}}</p>
              <p class="text-sm">ORIGEN: {{$flight->origin}}</p>
            </div>
          </div>
  
          <!-- Información del vuelo -->
          <div>
            <p class="text-sm font-bold">CAPACIDAD TOTAL: {{-- {{$flight->capacity}} --}} 150</p>
            <p class="text-sm font-bold">CAPACIDAD RESTANTE: {{-- {{$flight->remaining_capacity}} --}}30</p>
            <p class="text-sm">DESTINO: {{$flight->destination}}</p>
          </div>
  
          <!-- Botón reservar -->
          <div>
            <button class="bg-cyan-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-cyan-600">
              Reservar
            </button>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>  
@endsection