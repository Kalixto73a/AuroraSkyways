<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Santa Claus')</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/app.css')}}">
</head>
<body class="w-full">
    <div>
        <x-header />

        <main class="bg-black bg-fixed bg-no-repeat bg-cover">
            @yield('content')
            @yield('script')
        </main>
        <x-footer />
    </div>
</body>
</html>