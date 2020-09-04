<section class="bg-white p-6">
    <header class="flex justify-between mb-6">
        <div class="leading-normal w-full md:w-auto text-center md:text-left">
            <h2 class="block text-base uppercase text-gray-700">
                {{ $title }}
            </h2>
            <div>
                {!! $header !!}
            </div>
        </div>
    </header>

    <div
        x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : 'basic_details' }"
        class="flex flex-wrap md:flex-no-wrap"
    >
        <ul
            role="tablist"
            class="uppercase font-semibold grid md:block grid-cols-2
                   md:whitespace-no-wrap mb-4 md:mr-4 w-full md:w-fit"
        >
            @foreach ($pages as $page)
            <li class="block">
                <a
                    :aria-selected="tab === '{{ $page['hash'] }}'"
                    class="text-xs md:text-base flex justify-between items-center h-full p-2"
                    :class="tab === '{{ $page['hash'] }}' ?
                        'bg-red-700 hover:bg-red-700 cursor-default text-gray-200 hover:text-gray-200' :
                        'bg-gray-200 hover:bg-gray-300 text-gray-700 hover:text-gray-700'"

                    href="#{{ $page['hash'] }}"
                    role="tab"
                    @click.prevent="tab = '{{ $page['hash'] }}'; window.location.hash = '{{ $page['hash'] }}'"
                >
                    <span class="block">
                        {{ Str::of($page['hash'])->replace('_', ' ')->ucfirst() }}
                    </span>

                    @isset($page['count'])
                    <span class="block ml-2 md:ml-4 bg-white shadow-inner text-gray-700 px-1 rounded-full text-xs font-bold">
                        {{ $page['count'] }}
                    </span>
                    @endisset
                </a>
            </li>
            @endforeach
        </ul>

        <div class="overflow-hidden w-full relative">
            @foreach ($pages as $page)
            <div x-show="tab === '{{ $page['hash'] }}'">
                {{ ${"{$page['hash']}"} }}
            </div>
            @endforeach
        </div>
    </div>
</section>
