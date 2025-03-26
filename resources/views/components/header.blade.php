<header class="flex flex-wrap items-center justify-center bg-[#446878] text-white p-4 font-[K2D]">
  <a href="{{ route('planes') }}" class="flex items-center rounded-full hover:scale-110 transition-transform duration-500">
    <button class="rounded-full">
      <img src="https://res.cloudinary.com/dzfqdntdw/image/upload/v1742863803/imagen_2025-03-25_015006250_sqd989.png" alt="" class="w-12 h-12 bg-[#FFC107] rounded-full"> 
    </button>
  </a>
@if (request()->routeIs('bookings'))
  <a href="{{ route('home') }}">
    <button class="ml-4 bg-[#53CCDC] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition-transform duration-500">
      <div>Inicio</div>
    </button>
  </a>
@else
  @if (auth()->check() && auth()->user()->role === 'admin')
    <a href="{{ route('planes') }}">
      <button class="ml-4 bg-[#FF5733] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition-transform duration-500">
        <div>Aviones</div>
      </button>
    </a>
  @elseif (auth()->check() && auth()->user()->role === 'user')
    <a href="{{ route('bookings') }}">
      <button class="ml-4 bg-[#53CCDC] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition-transform duration-500">
        <div>Reservas</div>
      </button>
    </a>
  @else
    <a href="{{ route('bookings') }}">
      <button class="ml-4 bg-[#53CCDC] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition-transform duration-500">
        <div>Reservas</div>
      </button>
    </a>
  @endif
@endif
  
  <!-- Título del header -->
  <div class="text-2xl font-bold px-[19rem]">AuroraSkyways</div>

  <!-- Botones del lado derecho -->
  <div class="flex items-center gap-4">
    <!-- Botón de Vuelos -->
    @if (request()->routeIs('flights'))
      <a href="{{ route('home') }}">
        <button class="ml-8 bg-[#53CCDC] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition-transform duration-500">
          <div>Inicio</div>
        </button>
      </a>
    @else
      <a href="{{ route('flights') }}">
        <button class="ml-8 bg-[#53CCDC] px-4 py-2 rounded-full text-lg w-[111px] text-center font-bold shadow-md hover:scale-110 transition-transform duration-500">
          <div>Vuelos</div>
        </button>
      </a>
    @endif

    <!-- Botón de Usuario con Desplegable -->
    <div class="relative z-50" id="authButtons">
      <button id="userDropdownButton" class="mr-4 bg-white rounded-full w-9 h-9 flex items-center justify-center font-bold shadow-md hover:scale-110 transition-transform duration-500">
        <img
          class="w-5 h-5"
          src="https://res.cloudinary.com/dzfqdntdw/image/upload/v1738685836/imagen_2025-02-04_171714318_m6qnvx.png"
          alt="Usuario"
        />
      </button>
      <!-- Menú Desplegable -->
      <div id="userDropdownContent" class="absolute right-0 mt-2 w-64 bg-black rounded-md shadow-lg hidden">
        @guest          
        {{-- Si el usuario NO ha iniciado sesión, muestra "Iniciar sesión" y "Registrarme" --}}
        <a href="{{ route('login') }}" class="text-center block px-4 py-2 text-sm rounded-md text-white hover:bg-gray-700">
            Iniciar sesión
        </a>
        <hr class="border-t border-white mx-4">
        <a href="{{ route('register') }}" class="text-center block px-4 py-2 text-sm rounded-md text-white hover:bg-gray-700">
            Registrarme
        </a>
        @endguest
        @auth          
        {{-- Si el usuario SÍ ha iniciado sesión, muestra "Cerrar sesión" --}}
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <button type="submit" class="text-center block w-full px-4 py-2 text-sm rounded-md text-white hover:bg-gray-700">
                Cerrar sesión
            </button>
        </form>
        @endauth
      </div>
    </div>
  </div>
</header>
<script>
  document.getElementById('userDropdownButton').onclick = function(event) {
    event.stopPropagation();
    var dropdownContent = document.getElementById('userDropdownContent');
    dropdownContent.classList.toggle('hidden');
  }
</script>

  

