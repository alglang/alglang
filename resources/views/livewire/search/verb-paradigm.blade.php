<form
    method="GET"
    action="/search/verbs/paradigm-results"
    target="_blank"
>
    <div class="flex justify-end mb-1">
        <button type="button" wire:click="$emit('toggleMode')" class="text-blue-600">
            @if ($advancedMode)
                Simple Search
            @else
                Advanced Search
            @endif
        </button>
    </div>

    @if ($advancedMode)
        <div class="grid lg:flex">
            <div class="contents lg:block">
                @component('components.search.multi-select', [
                    'label' => 'Class',
                    'options' => $classes,
                    'optionKey' => 'abv',
                    'modelKey' => 'classQuery',
                    'name' => 'classes[]'
                ])
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.multi-select', [
                    'label' => 'Order',
                    'options' => $orders,
                    'optionKey' => 'name',
                    'modelKey' => 'orderQuery',
                    'name' => 'orders[]'
                ])
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.multi-select', [
                    'label' => 'Mode',
                    'options' => $modes,
                    'optionKey' => 'name',
                    'modelKey' => 'modeQuery',
                    'name' => 'modes[]'
                ])
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Other features', 'for' => 'other-features-field'])
                    <div aria-labelledby="other-features-field" class="p-2">
                        <label class="block mb-2">
                            <input
                                type="checkbox"
                                class="form-checkbox rounded-none text-blue-400"
                                wire:model="negative"
                                name="negative"
                            />
                            Negative
                        </label>

                        <label class="block mb-2">
                            <input
                                type="checkbox"
                                class="form-checkbox rounded-none text-blue-400"
                                wire:model="diminutive"
                                name="diminutive"
                            />
                            Diminutive
                        </label>
                    </div>
                @endcomponent
            </div>

            <div class="contents lg:block">
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
        </div>
    @else
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
    @endif

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
