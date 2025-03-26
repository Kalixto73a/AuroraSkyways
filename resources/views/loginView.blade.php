@extends('layouts.index')
@section('content')
<div class="relative h-screen bg-black flex justify-center items-center font-[K2D]">
    <img 
        src="https://res.cloudinary.com/dzfqdntdw/image/upload/v1738684129/imagen_2025-02-04_164846399_zowfpl.png" 
        alt="Avión en el cielo" 
        class="absolute inset-0 w-full h-full object-cover opacity-50"
    />
    <div class="relative z-10 bg-[#53CCDC] rounded-lg shadow-lg w-[400px] pt-2">
        <h2 class="text-2xl text-white font-bold text-center mb-6">Iniciar Sesión</h2>
    
        @if ($errors->any())
            <div class="bg-red-500 text-white p-2 rounded mb-4 flex justify-center items-center">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('webLogin') }}">
            @csrf
            <div class="mb-4 px-6">
                <label for="email" class="block text-white">Email:</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded">
            </div>
            <div class="mb-4 px-6">
                <label for="password" class="block text-white">Contraseña:</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded">
            </div>

            <button type="submit" class="w-full text-white py-2 rounded-b bg-blue-700 hover:bg-blue-800 transition">Iniciar Sesión</button>
        </form>
    </div>
</div>   
@endsection

