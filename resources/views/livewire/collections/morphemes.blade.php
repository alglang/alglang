<div>
    <div class="mb-4">
        <div class="inline-flex text-gray-700">
            <button
                type="button"
                class="w-8 mr-1 p-1 bg-gray-200 hover:bg-gray-100 shadow hover:shadow-md transition-all duration-150 ease-in-out"
                wire:click.prefetch="prevPage"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                type="button"
                class="w-8 p-1 bg-gray-200 hover:bg-gray-100 shadow hover:shadow-md transition-all duration-150 ease-in-out"
                wire:click.prefetch="nextPage"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        <div wire:loading>
            Loading...
        </div>
    </div>

    <ul class="flex justify-center md:justify-start flex-wrap">
        @foreach ($this->morphemes as $morpheme)
        <li class="mr-4">
            <x-morpheme-card :morpheme="$morpheme"></x-morpheme-card>
        </li>
        @endforeach
    </ul>
</div>
