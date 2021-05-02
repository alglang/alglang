<form
    class="flex flex-wrap"
    method="GET"
    action="/search/verbs/forms/results"
    target="_blank"
>
    <div class="md:mr-8 mb-4 w-full md:w-auto">
        <legend
            id="language-select-label"
            class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2 w-full"
        >
            Languages
        </legend>
        <fieldset
            aria-labelledby="language-select-label"
            class="overflow-auto p-2 border-l border-r border-b border-gray-300 h-56 scrollbar scrollbar-thumb-gray-600 hover:scrollbar-thumb-gray-500"
            style="overflow: auto"
        >
            @foreach ($languages as $language)
                <label class="flex items-center mb-2 last:mb-0">
                    <input
                        type="checkbox"
                        wire:model="languageQueries.{{ $language->code }}"
                        name="languages[]"
                        value="{{ $language->code }}"
                        class="form-checkbox rounded-none text-blue-400"
                    />
                    <span class="ml-1">
                        {{ $language->name }}
                    </span>
                </label>
            @endforeach
        </fieldset>
    </div>

    <div class="w-full md:w-auto">
        <ul aria-label="Structure queries">
            @foreach ($structureQueries as $index => $structureQuery)
                <li wire:key="structure-queries-{{ $index }}">
                    <x-search.verb-form-structure
                        index="{{ $index }}"
                        :model="$structureQuery"
                        :classes="$classes"
                        :orders="$orders"
                        :modes="$modes"
                        :features="$features"
                    />
                </li>
            @endforeach
        </ul>

        <div class="flex justify-end">
            <button
                type="button"
                class="bg-gray-300 text-gray-700 hover:bg-blue-200 mr-3
                       shadow focus:outline-none focus:shadow-outline"
                aria-label="Remove structure query"
                wire:click="removeStructureQuery"
            >
                <svg
                    class="w-8"
                    aria-hidden="true"
                    tabindex="-1"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                      fill-rule="evenodd"
                      d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                      clip-rule="evenodd"
                    />
                </svg>
            </button>
            <button
                type="button"
                class="bg-gray-300 text-gray-700 hover:bg-blue-200 mr-3
                       shadow focus:outline-none focus:shadow-outline"
                aria-label="Add structure query"
                wire:click="addStructureQuery"
            >
                <svg
                    class="w-8"
                    aria-hidden="true"
                    tabindex="-1"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1
                           1 0 011-1z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
            <button
                type="submit"
                aria-label="Search button"
                class="px-3 py-2 shadow uppercase text-lg text-gray-700 bg-yellow-400 hover:bg-yellow-500
                       focus:outline-none focus:shadow-outline"
            >
                Search
            </button>
        </div>
    </div>
</form>
