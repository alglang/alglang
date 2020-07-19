<footer class="flex flex-wrap justify-between p-6 bg-gray-900 text-yellow-400">
    <div>
        <h1 class="uppercase mb-3 text-xs md:text-md">
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

    <div class="pt-6 md:pt-0">
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
