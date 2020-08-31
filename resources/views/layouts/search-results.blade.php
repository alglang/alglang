<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content="width=600" name="viewport">

    <title>Database of Algonquian Language Structures</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @include('layouts.partials.favicon')
</head>
<body class="bg-gray-300 m-5 text-gray-900 antialiased bont-body leading-none">
    @yield('content')
</body>
</html>
