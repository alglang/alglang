<nav class="flex items-center justify-between flex-wrap bg-gray-900 md:px-6">
    {{-- Left side --}}
    <div class="relative w-full md:w-auto flex justify-between">
        {{-- Logo --}}
        <a
            href="{{ route('home') }}"
            class="flex items-center flex-shrink-0 px-6 py-4 md:px-3 md:py-3
                   text-yellow-400 md:bg-yellow-400 md:text-gray-900 md:hover:bg-yellow-500 md:hover:text-gray-900"
        >
            <h1 class="font-light text-2xl tracking-tight">
                alglang.net
            </h1>
        </a>

        {{-- Main menu --}}
        <div class="group">
            <p
                class="flex h-full items-center px-6 md:px-3 cursor-pointer
                       text-yellow-400 md:text-gray-900 md:bg-yellow-400 md:hover:bg-yellow-500"
                aria-label="Main menu"
                aria-haspopup="true"
                id="main-menu"
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
            </p>

            @include('layouts.partials.dropdown-list', [
                'links' => [
                    'Verb forms' => route('verb-forms'),
                    'Nominal forms' => route('nominal-forms'),
                    'Phonology' => route('phonology'),
                    'Bibliography' => route('bibliography')
                ],
                'class' => 'right-0 md:left-0',
                'labelledby' => 'main-menu'
            ])
        </div>
    </div>

    {{-- Right side --}}
    <div class="flex items-center self-stretch w-full md:w-auto justify-around h-8 md:h-auto bg-gray-800 md:bg-transparent">
        {{-- Language dropdown --}}
        <div class="relative group h-full">
            <a
                href="{{ route('groups.show', ['group' => 'algonquian']) }}"
                class="flex items-center px-3 h-full uppercase tracking-wide
                       text-gray-100 hover:bg-red-700 hover:text-gray-900 cursor-pointer"
                aria-haspopup="true"
                id="language-menu"
            >
                <span>Languages</span>
            </a>

            @include('layouts.partials.dropdown-list', [
                'links' => App\Models\Language::orderBy('name')->get()->mapWithKeys(fn ($language) => [$language->name => $language->url]),
                'class' => 'md:right-0 overflow-auto',
                'labelledby' => 'language-menu'
            ])
        </div>

        {{-- Search dropdown --}}
        <div class="relative group h-full">
            <p
                class="flex items-center px-3 h-full uppercase tracking-wide
                       text-gray-100 hover:bg-red-700 hover:text-gray-900 cursor-pointer"
                aria-haspopup="true"
                id="search-menu"
            >
                <span>Search</span>
            </p>

            @component('layouts.partials.dropdown-list', [
                'links' => [
                    'Nominal paradigms' => route('search.nominals.paradigms'),
                    'Verb paradigms' => route('search.verbs.paradigms'),
                    'Verb forms' => route('search.verbs.forms'),
                ],
                'class' => 'right-0',
                'labelledby' => 'search-menu'
            ])
                <form
                    class="contents"
                    method="GET"
                    action="{{ route('smart-search') }}"
                >
                    <input
                        type="search"
                        autocomplete="off"
                        class="mx-1 mt-1 p-2 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:bg-gray-300 placeholder-gray-100 focus:placeholder-gray-700 shadow-inner"
                        placeholder="Smart search..."
                        aria-label="Smart search"
                        name="q"
                        required
                    />
                </form>
            @endcomponent
        </div>

        {{-- Add dropdown --}}
        @role('contributor')
            <div class="relative group h-full">
                <p class="flex items-center uppercase tracking-wide px-3 h-full uppercase cursor-pointer
                          text-gray-100 hover:text-gray-100 hover:bg-red-700 md:bg-yellow-400 md:text-gray-900 md:hover:bg-yellow-500 md:hover:text-gray-900"
                   aria-haspopup="true"
                   id="add-menu"
                >
                    <span>Add</span>
                </p>

                @include('layouts.partials.dropdown-list', [
                    'links' => [
                        'Language' => route('languages.create')
                    ],
                    'class' => 'right-0',
                    'labelledby' => 'add-menu'
                ])
            </div>
        @endrole
    </div>
</nav>
