<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-300 text-gray-900 antialiased font-body leading-none">
    <div id="app" class="flex flex-col min-h-screen">
        <nav class="flex items-center justify-between flex-wrap bg-gray-900 px-6">
            <a href="{{ route('home') }}" class="flex items-center flex-shrink-0 p-3 bg-yellow-400 text-gray-900 hover:bg-yellow-500 hover:text-gray-900">
                <h1 class="font-light text-2xl tracking-tight">
                    alglang.net
                </h1>
                <div class="block">
                    <button class="flex items-center px-3">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <title>Menu</title>
                            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                        </svg>
                    </button>
                </div>
            </a>

            <div class="flex items-center self-stretch">
                <input type="text" class="bg-gray-600 placeholder-gray-800 p-2 mx-3 border border-gray-900 text-gray-100 hover:border-yellow-400 focus:outline-none focus:border-red-700" placeholder="Smart search..." />
                <a href="/groups/algonquian" class="flex items-center px-3 h-full uppercase text-gray-100 hover:bg-red-700 hover:text-gray-900">
                    <span>Languages</span>
                </a>

                <a href="#" class="flex items-center px-3 h-full uppercase text-gray-100 hover:bg-red-700 hover:text-gray-900">
                    <span>Search</span>
                </a>

                @guest
                <a href="{{ route('login') }}" class="flex items-center px-3 h-full uppercase bg-yellow-400 text-gray-900 hover:bg-yellow-500 hover:text-gray-900">
                    <span>Log in</span>
                </a>
                @else
                <a href="#" class="flex items-center bg-yellow-400 text-gray-900 uppercase px-3 h-full uppercase bg-yellow-400 text-gray-900 hover:bg-yellow-500 hover:text-gray-900">
                    <span>Add</span>
                </a>
                @endguest
            </div>
        </nav>

        <div class="m-6 flex-grow">
            @yield('content')
        </div>

        <footer class="flex justify-between p-6 bg-gray-900 text-yellow-400">
            <div>
                <h1 class="uppercase mb-3">
                    &copy; Database of Algonquian Language Structures
                </h1>

                <ul class="flex-text-gray-100">
                    <li>
                        <a href="#" class="mr-3 text-gray-100 hover:text-gray-300">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#" class="mr-3 text-gray-100 hover:text-gray-300">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="#" class="mr-3 text-gray-100 hover:text-gray-300">
                            Resources
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                @guest
                <a href="{{ route('login') }}" class="block uppercase mb-2 text-yellow-400 hover:text-yellow-600">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="block uppercase mb-2 text-yellow-400 hover:text-yellow-600">
                    Register
                </a>
                @else
                <a
                    href="{{ route('logout') }}"
                    class="block uppercase hover:text-yellow-800"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                >
                    Log out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    {{ csrf_field() }}
                </form>
                @endguest
            </div>
        </footer>
    </div>
</body>
</html>
