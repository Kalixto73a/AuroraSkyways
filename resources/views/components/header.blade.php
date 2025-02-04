<header class="flex items-center justify-between bg-[#446878] text-white p-4 font-[K2D]">
  <!-- Botón de Reservas -->
  <button class="ml-8 bg-[#53CCDC] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition transition-transform duration-500">
    Reservas
  </button>
  
  <!-- Título del header -->
  <div class="text-2xl font-bold ml-8">AuroraSkyways</div>
  
  <!-- Botones del lado derecho -->
  <div class="flex items-center gap-4">
    <!-- Botón de Vuelos -->
    <button class="bg-[#53CCDC] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition transition-transform duration-500">
        <a href="{{route('flights')}}">Vuelos</a>
    </button>
    
    <!-- Botón de Usuario -->
    <button class="mr-4 bg-white rounded-full w-9 h-9 flex items-center justify-center font-bold shadow-md hover:scale-110 transition transition-transform duration-500">
      <img 
        class="w-5 h-5" 
        src="https://res.cloudinary.com/dzfqdntdw/image/upload/v1738685836/imagen_2025-02-04_171714318_m6qnvx.png" 
        alt="Usuario"
      />
    </button>
  </div>
</header>
