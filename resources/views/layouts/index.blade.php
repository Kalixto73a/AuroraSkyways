<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'AuroraSkyways')</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/app.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="w-full no-scrollbar">
    <div>
        <x-header />

        <main class="bg-black bg-fixed bg-no-repeat bg-cover z-0">
            @yield('content')
            @yield('script')
        </main>
        <x-footer />
    </div>
</body>
</html>