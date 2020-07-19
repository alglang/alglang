<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Database of Algonquian Language Structures</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-300 text-gray-900 antialiased font-body leading-none">
    <div id="app" class="flex flex-col min-h-screen">
        @include('layouts.header')

        <div class="m-2 md:m-4 lg:m-6 flex-grow">
            @yield('content')
        </div>

        @include('layouts.footer')
    </div>
</body>
</html>
