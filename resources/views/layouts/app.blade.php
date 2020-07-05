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
        <nav class="flex items-center justify-between flex-wrap bg-gray-900 px-6">
            <div class="relative group">
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

                <ul class="absolute py-1 border-t border-gray-100 bg-gray-900 hidden group-hover:block whitespace-no-wrap">
                    <li>
                        <a href="{{ route('structural-survey') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                            Structural survey
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('verbs') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                            Verb forms
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('nominals') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                            Nominal forms
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('phonology') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                            Phonology
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bibliography') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                            Bibliography
                        </a>
                    </li>
                </ul>
            </div>

            <div class="flex items-center self-stretch">
                <input type="text" class="bg-gray-600 placeholder-gray-800 p-2 mx-3 border border-gray-900 text-gray-100 hover:border-yellow-400 focus:outline-none focus:border-red-700" placeholder="Smart search..." />
                <div class="relative group h-full">
                    <a href="{{ route('groups.show', ['group' => 'algonquian']) }}" class="flex items-center px-3 h-full uppercase tracking-wide text-gray-100 hover:bg-red-700 hover:text-gray-900">
                        <span>Languages</span>
                    </a>
                    
                    <ul class="absolute py-1 border-1 border-gray-100 bg-gray-900 hidden group-hover:block whitespace-no-wrap">
                        @foreach($languages as $language)
                        <li>
                            <a href="{{ $language->url }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                                {{ $language->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <a href="#" class="flex items-center px-3 h-full uppercase tracking-wide text-gray-100 hover:bg-red-700 hover:text-gray-900">
                    <span>Search</span>
                </a>

                @guest
                <div class="relative group h-full">
                    <a href="{{ route('login') }}" class="flex items-center px-3 h-full uppercase tracking-wide bg-yellow-400 text-gray-900 hover:bg-yellow-500 hover:text-gray-900">
                        <span>Log in</span>
                    </a>

                    <ul class="absolute right-0 border-1 border-gray-800 bg-gray-100 py-4 px-6 hidden group-hover:block shadow-lg">
                        <li>
                            <a href="{{ route('auth', ['provider' => 'github']) }}" class="flex items-center p-2 text-gray-100 bg-gray-800 hover:text-gray-100 hover:bg-gray-700">
                                <svg class="w-8 p-1 fill-current border-r border-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.18 11.309c-.718 0-1.3.807-1.3 1.799 0 .994.582 1.801 1.3 1.801s1.3-.807 1.3-1.801c-.001-.992-.582-1.799-1.3-1.799zm4.526-4.683c.149-.365.155-2.439-.635-4.426 0 0-1.811.199-4.551 2.08-.575-.16-1.548-.238-2.519-.238-.973 0-1.945.078-2.52.238C4.74 2.399 2.929 2.2 2.929 2.2c-.789 1.987-.781 4.061-.634 4.426C1.367 7.634.8 8.845.8 10.497c0 7.186 5.963 7.301 7.467 7.301l1.734.002 1.732-.002c1.506 0 7.467-.115 7.467-7.301 0-1.652-.566-2.863-1.494-3.871zm-7.678 10.289h-.056c-3.771 0-6.709-.449-6.709-4.115 0-.879.31-1.693 1.047-2.369C5.537 9.304 7.615 9.9 9.972 9.9h.056c2.357 0 4.436-.596 5.664.531.735.676 1.045 1.49 1.045 2.369 0 3.666-2.937 4.115-6.709 4.115zm-3.207-5.606c-.718 0-1.3.807-1.3 1.799 0 .994.582 1.801 1.3 1.801.719 0 1.301-.807 1.301-1.801 0-.992-.582-1.799-1.301-1.799z"/></svg>
                                <p class="px-2">
                                    Github
                                </p>
                            </a>
                        </li>
                    </ul>
                </div>
                @else
                <a href="#" class="flex items-center bg-yellow-400 text-gray-900 uppercase tracking-wide px-3 h-full uppercase bg-yellow-400 text-gray-900 hover:bg-yellow-500 hover:text-gray-900">
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
                        <a href="{{ route('home') }}" class="mr-3 text-gray-100 hover:text-gray-300">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="mr-3 text-gray-100 hover:text-gray-300">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('resources') }}" class="mr-3 text-gray-100 hover:text-gray-300">
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
                @else
                <a
                    href="{{ route('logout') }}"
                    class="block uppercase mb-2 text-yellow-400 hover:text-yellow-600"
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
