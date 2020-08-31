<nav class="flex items-center justify-between flex-wrap bg-gray-900 md:px-6">
    <div class="relative w-full md:w-auto flex justify-between">
        <a href="{{ route('home') }}" class="flex items-center flex-shrink-0 px-6 py-4 md:px-3 md:py-3 text-yellow-400 md:bg-yellow-400 md:text-gray-900 md:hover:bg-yellow-500 md:hover:text-gray-900">
            <h1 class="font-light text-2xl tracking-tight">
                alglang.net
            </h1>
        </a>
        <div class="group">
            <button
                class="flex h-full items-center px-6 md:px-3 text-yellow-400 md:text-gray-900 md:bg-yellow-400 md:hover:bg-yellow-500"
                aria-label="Main menu"
            >
                <svg
                    class="fill-current h-3 w-3"
                    aria-hidden="true"
                    tabindex="-1"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <title>Menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
            </button>

            <ul class="absolute right-0 md:right-auto md:left-0 py-1 border-t border-gray-100 bg-gray-900 hidden group-hover:block whitespace-no-wrap z-50">
                <li>
                    <a href="{{ route('verb-forms') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                        Verb forms
                    </a>
                </li>
                <li>
                    <a href="{{ route('nominal-forms') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
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
    </div>

    <div class="flex items-center self-stretch w-full md:w-auto justify-around h-8 md:h-auto bg-gray-800 md:bg-transparent">
        <input
            type="text"
            class="hidden md:block bg-gray-600 placeholder-gray-800 p-2 mx-3 border border-gray-900 text-gray-100 hover:border-yellow-400 focus:outline-none focus:border-red-700"
            placeholder="Smart search..."
            aria-label="Smart search"
        />

        <div class="relative group h-full">
            <a href="{{ route('groups.show', ['group' => 'algonquian']) }}" class="flex items-center px-3 h-full uppercase tracking-wide text-gray-100 hover:bg-red-700 hover:text-gray-900">
                <span>Languages</span>
            </a>
            
            <ul class="absolute md:right-0 py-1 border-t border-gray-100 bg-gray-900 hidden group-hover:block whitespace-no-wrap z-50">
                @foreach(App\Models\Language::limit(10)->get() as $language)
                <li>
                    <a href="{{ $language->url }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                        {{ $language->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="relative group h-full">
            <div class="flex items-center px-3 h-full uppercase tracking-wide text-gray-100 hover:bg-red-700 hover:text-gray-900 cursor-pointer">
                <span>Search</span>
            </div>

            <ul class="absolute right-0 py-1 border-t border-gray-100 bg-gray-900 hidden group-hover:block whitespace-no-wrap z-50">
                <li>
                    <a href="{{ route('search.nominals.paradigms') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                        Nominal paradigms
                    </a>
                </li>
                <li>
                    <a href="{{ route('search.verbs.paradigms') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                        Verb paradigms
                    </a>
                </li>
                <li>
                    <a href="{{ route('search.verbs.forms') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                        Verb forms
                    </a>
                </li>
            </ul>
        </div>

        @role('contributor')
            <div class="relative group h-full">
                <span class="flex items-center uppercase tracking-wide px-3 h-full uppercase text-gray-100 hover:text-gray-100 hover:bg-red-700 md:bg-yellow-400 md:text-gray-900 md:hover:bg-yellow-500 md:hover:text-gray-900">
                    <span>Add</span>
                </span>

                <ul class="absolute py-1 right-0 border-t border-gray-100 bg-gray-900 hidden group-hover:block whitespace-no-wrap z-50">
                    <li>
                        <a href="{{ route('languages.create') }}" class="block p-2 uppercase tracking-wide text-gray-100 hover:text-gray-100 hover:bg-red-700">
                            Language
                        </a>
                    </li>
                </ul>
            </div>
        @endrole
    </div>
</nav>
