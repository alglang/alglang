<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scrollbar scrollbar-track-gray-400 scrollbar-thumb-gray-700 hover:scrollbar-thumb-gray-600"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Database of Algonquian Language Structures</title>

    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @include('layouts.partials.favicon')
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-300 text-gray-900 antialiased font-body leading-none">
    <div id="app" class="flex flex-col min-h-screen">
        @include('layouts.partials.header')

        @include('layouts.partials.errors')

        <div class="m-2 md:m-4 lg:m-6 flex-grow">
            @yield('content')
        </div>

        @include('layouts.partials.footer')
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
