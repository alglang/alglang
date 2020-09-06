<div>
    <div class="mb-4 flex justify-between items-end relative">
        <span>
            <label
                for="filter-input"
                class="block uppercase text-gray-700 text-xs uppercase tracking-wider"
            >
                Filter
            </label>
            <input
                id="filter-input"
                class="p-2 bg-gray-200 hover:bg-gray-100 focus:bg-gray-100 shadow-inner transition-bg duration-75 ease-in-out"
                wire:model="filter"
            />
        </span>

        <div
            class="absolute flex justify-center w-full transform translate-y-10 transition-all duration-150 ease-in-out opacity-0"
            wire:loading.class="translate-y-24 opacity-100"
            style="pointer-events: none;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12" viewBox="0 0 200 200">
              <animateTransform
                  attributeType="xml"
                  attributeName="transform"
                  type="rotate"
                  from="0 0 0"
                  to="360 0 0"
                  dur="1s"
                  calcMode="spline"
                  keyTimes="0; 1"
                  keySplines="0.76 0 0.24 1"
                  repeatCount="indefinite"
              />

              <rect x="0" y="0" width="100" height="100" fill="#2D3748" />
              <rect x="100" y="0" width="100" height="100" fill="#EDF2F7" />
              <rect x="100" y="100" width="100" height="100" fill="#ECC948" />
              <rect x="0" y="100" width="100" height="100" fill="#C53030" />
            </svg>
        </div>

        <div class="inline-flex text-gray-700">
            <button
                type="button"
                class="w-8 mr-1 p-1 bg-gray-200 hover:bg-gray-100 disabled:bg-gray-500 disabled:cursor-default
                       shadow hover:shadow-md disabled:shadow-none transition-all duration-150 ease-in-out"
                @if ($this->page === 0)
                disabled="disabled"
                @endif
                wire:click.prefetch="prevPage"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                type="button"
                class="w-8 p-1 bg-gray-200 hover:bg-gray-100 disabled:bg-gray-500 disabled:cursor-default
                       shadow hover:shadow-md disabled:shadow-none transition-all duration-150 ease-in-out"
                wire:click.prefetch="nextPage"
                @if (!$this->hasMoreSources())
                disabled="disabled"
                @endif
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <ul class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-8 ">
        @foreach ($this->sources as $source)
        <li class="mr-4 leading-relaxed">
            <x-preview-link :model="$source">
                {{ $source->short_citation }}
            </x-preview-link>
        </li>
        @endforeach
    </ul>
</div>
