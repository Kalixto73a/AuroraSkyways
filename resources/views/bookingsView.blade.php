@extends('layouts.index')
@section('content')
<div class="relative h-screen bg-black flex justify-center items-center font-[K2D]">
  
    <img 
      src="https://res.cloudinary.com/dzfqdntdw/image/upload/v1738684129/imagen_2025-02-04_164846399_zowfpl.png" 
      alt="Avión en el cielo" 
      class="absolute inset-0 w-full h-full object-cover opacity-50"
    />

    <div class="relative z-10 w-11/12 max-w-4xl bg-white/90 rounded-xl shadow-lg max-h-[30rem] overflow-y-scroll no-scrollbar">
      <div class="flex justify-between items-center bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-t-xl p-4 sticky top-0">
        <button onclick="filterBookings('Activo')" class="bg-white text-cyan-500 font-bold py-2 px-4 rounded-full shadow-md hover:scale-110 transition transition-transform duration-500">Actuales</button>
        <h1 class="text-2xl font-bold">MIS RESERVAS</h1>
        <button onclick="filterBookings('Inactivo')" class="bg-white text-cyan-500 font-bold py-2 px-4 rounded-full shadow-md hover:scale-110 transition transition-transform duration-500">Antiguos</button>
      </div>
      <div class="p-4 space-y-4">
        @foreach ($bookings as $booking)
        <div class="booking-item" data-status="{{ $booking->status }}">
          <div class="flex items-center justify-between bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center space-x-4">
              <div class="bg-cyan-500 text-white rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.894 2.553a1 1 0 00-1.788 0L7.082 7.62l-5.281.767a1 1 0 00-.554 1.706l3.82 3.72-.9 5.263a1 1 0 001.45 1.054L10 17.862l4.715 2.479a1 1 0 001.45-1.054l-.9-5.263 3.82-3.72a1 1 0 00-.554-1.706l-5.281-.767-2.024-5.067z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-bold">IDA: {{ $booking->flight->departure_date }}</p>
                <p class="text-sm font-bold">VUELTA: {{ $booking->flight->arrival_date }}</p>
                <p class="text-sm">ORIGEN: {{ $booking->flight->origin }}</p>
              </div>
            </div>
            <div>
              <p class="text-sm font-bold">ASIENTO: {{ $booking->seat_number }}</p>
              <p class="text-sm font-bold">ESTADO: {{ $booking->status }}</p>
              <p class="text-sm">DESTINO: {{ $booking->flight->destination }}</p>
            </div>
            <div class="flex flex-col items-center text-sm font-bold">
              <p class="pb-2">{{ $booking->flight->plane->name }}</p>
              <div class="flex space-x-4">
                @if ($booking->status == 'Activo')
                  <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600">
                    Activo
                  </button>
                @else
                  <button class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600">
                    Inactivo
                  </button>
                @endif
                <button class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600">
                  Cancelar
                </button>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
</div>
<script>
  function filterBookings(status) {
    let bookings = document.querySelectorAll(".booking-item");

    bookings.forEach(booking => {
      let bookingStatus = booking.getAttribute("data-status");
      booking.style.display = (bookingStatus === status) ? "block" : "none";
    });
  }

  // Mostrar solo las reservas activas al cargar la página
  window.onload = function() {
    filterBookings("Activo");
  };
</script>
@endsection