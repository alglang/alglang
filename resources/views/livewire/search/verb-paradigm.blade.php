<form
    method="GET"
    action="/search/verbs/paradigm-results"
    target="_blank"
>
    <p class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2">
        Query
    </p>

    <div class="grid lg:flex" style="grid-template-columns: min-content 1fr">
        @component('components.search.select', [
            'label' => 'Class',
            'options' => $classes,
            'optionKey' => 'abv',
            'modelKey' => 'classQuery',
            'name' => 'classes[]'
        ])
        @endcomponent

        @component('components.search.select', [
            'label' => 'Order',
            'options' => $orders,
            'optionKey' => 'name',
            'modelKey' => 'orderQuery',
            'name' => 'orders[]'
        ])
        @endcomponent

        @component('components.search.select', [
            'label' => 'Language',
            'options' => $languages,
            'optionKey' => 'name',
            'optionValueKey' => 'code',
            'modelKey' => 'languageQuery',
            'name' => 'languages[]'
        ])
        @endcomponent
    </div>

    <div class="flex justify-end mt-4">
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
