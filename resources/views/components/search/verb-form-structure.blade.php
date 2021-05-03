@php
    $uuid = Str::uuid();
    $modelKey = "structureQueries.$index";
    $prefix = "structures[$index]";
@endphp

<div class="border border-gray-300 max-w-sm lg:max-w-full mb-4">
    <p class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2">
        Structure
    </p>

    <div class="grid lg:flex" style="grid-template-columns: min-content 1fr;">
        <div class="contents lg:flex lg:border-r border-gray-400">
            @component('components.search.select', [
                'label' => 'Class',
                'name' => "${prefix}[classes][]",
                'modelKey' => "$modelKey.class_abv",
                'options' => $classes,
                'optionKey' => 'abv'
            ])
            @endcomponent
        </div>

        <div class="contents lg:flex lg:border-r border-gray-400">
            <div class="contents lg:block">
                @component('components.search.feature-select', [
                    'label' => 'Subject',
                    'value' => $model->subject,
                    'prefix' => $prefix,
                    'modelKey' => $modelKey,
                    'featureKey' => 'subject',
                    'features' => $features
                ])
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.feature-select', [
                    'label' => 'Primary Object',
                    'value' => $model->primaryObject,
                    'prefix' => $prefix,
                    'modelKey' => $modelKey,
                    'featureKey' => 'primary_object',
                    'features' => $features,
                    'includeNone' => true
                ])
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.feature-select', [
                    'label' => 'Secondary Object',
                    'value' => $model->secondaryObject,
                    'prefix' => $prefix,
                    'modelKey' => $modelKey,
                    'featureKey' => 'secondary_object',
                    'features' => $features,
                    'includeNone' => true
                ])
                @endcomponent
            </div>
        </div>

        <div class="contents lg:flex lg:border-r border-gray-400">
            <div class="contents lg:block">
                @component('components.search.select', [
                    'label' => 'Order',
                    'name' => "${prefix}[orders][]",
                    'modelKey' => "$modelKey.order_name",
                    'options' => $orders,
                    'optionKey' => 'name'
                ])
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.select', [
                    'label' => 'Mode',
                    'name' => "${prefix}[modes][]",
                    'modelKey' => "$modelKey.mode_name",
                    'options' => $modes,
                    'optionKey' => 'name'
                ])
                @endcomponent
            </div>
        </div>

        <div class="contents lg:flex lg:border-r border-gray-400">
            <div class="contents lg:block">
                @component('components.search.checkbox', [
                    'label' => 'Negative',
                    'modelKey' => "$modelKey.is_negative"
                ])
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.checkbox', [
                    'label' => 'Diminutive',
                    'modelKey' => "$modelKey.is_diminutive"
                ])
                @endcomponent
            </div>
        </div>
    </div>
</div>
