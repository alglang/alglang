<form
     method="GET"
     action="/search/nominals/paradigms/results"
     target="_blank"
>
    <div class="md:flex">
        <div class="mx-4 mb-4 md:mb-0 md:mr-4 md:ml-0">
            @component('components.search.multi-select', [
                'label' => 'Languages',
                'modelKey' => 'languageQueries',
                'name' => 'languages[]',
                'options' => $languages,
                'optionLabelKey' => 'name',
                'optionValueKey' => 'code'
            ])
            @endcomponent
        </div>

        <div class="mx-4 md:mx-0">
            @component('components.search.multi-select', [
                'label' => 'Paradigms',
                'modelKey' => 'paradigmQueries',
                'name' => 'nominal_paradigms[]',
                'options' => $paradigmTypes,
                'optionLabelKey' => 'name',
                'optionValueKey' => 'name'
            ])
            @endcomponent
        </div>
    </div>

    <div class="flex justify-end mx-4 md:mx-0 mt-4">
        <button
            type="submit"
            aria-label="Search button"
            class="px-3 py-2 shadow uppercase text-lg text-gray-700 bg-yellow-400 hover:bg-yellow-500
                   focus:outline-none focus:shadow-outline"
        >
            Search
        </button>
    </div>
</form>
