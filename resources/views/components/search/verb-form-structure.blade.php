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
            @component('components.search.field', ['label' => 'Class', 'for' => "class-select-$uuid"])
                <select
                    id="class-select-{{ $uuid }}"
                    wire:model="{{ $modelKey }}.class_abv"
                    name="{{ $prefix }}[classes][]"
                    class="form-select rounded-none border-none w-full"
                >
                    @foreach ($classes as $class)
                        <option>
                            {{ $class->abv }}
                        </option>
                    @endforeach
                </select>
            @endcomponent
        </div>

        <div class="contents lg:flex lg:border-r border-gray-400">
            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Subject', 'for' => "subject-select-$uuid"])
                    @if ($model->subject->person)
                        <input
                            name="{{ $prefix }}[subject_persons][]"
                            type="hidden"
                            value="{{ $model->subject->person }}"
                        />
                    @endif

                    @if ($model->subject->number)
                        <input
                            name="{{ $prefix }}[subject_numbers][]"
                            type="hidden"
                            value="{{ $model->subject->number }}"
                        />
                    @endif

                    <select
                        id="subject-select-{{ $uuid }}"
                        wire:model="{{ $modelKey }}.subject_name"
                        class="form-select rounded-none border-none w-full"
                    >
                        @foreach ($features as $feature)
                            <option>
                                {{ $feature->name }}
                            </option>
                        @endforeach

                    </select>
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Primary Object', 'for' => "primary-object-select-$uuid"])
                    @if ($model->primaryObject)
                        @if ($model->primaryObject->person)
                            <input
                                name="{{ $prefix }}[primary_object_persons][]"
                                type="hidden"
                                value="{{ $model->primaryObject->person }}"
                            />
                        @endif

                        @if ($model->primaryObject->number)
                            <input
                                name="{{ $prefix }}[primary_object_numbers][]"
                                type="hidden"
                                value="{{ $model->primaryObject->number }}"
                            />
                        @endif
                    @else
                        <input
                            name="{{ $prefix }}[primary_object]"
                            type="hidden"
                            value="0"
                        />
                    @endif

                    <select
                        id="primary-object-select-{{ $uuid }}"
                        wire:model="{{ $modelKey }}.primary_object_name"
                        class="form-select rounded-none border-none w-full"
                    >
                        <option>
                            None
                        </option>
                        @foreach ($features as $feature)
                            <option>
                                {{ $feature->name }}
                            </option>
                        @endforeach
                    </select>
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Secondary Object', 'for' => "secondary-object-select-$uuid"])
                    @if ($model->secondaryObject)
                        @if ($model->secondaryObject->person)
                            <input
                                name="{{ $prefix }}[secondary_object_persons][]"
                                type="hidden"
                                value="{{ $model->secondaryObject->person }}"
                            />
                        @endif

                        @if ($model->secondaryObject->number)
                            <input
                                name="{{ $prefix }}[secondary_object_numbers][]"
                                type="hidden"
                                value="{{ $model->secondaryObject->number }}"
                            />
                        @endif
                    @else
                        <input
                            name="{{ $prefix }}[secondary_object]"
                            type="hidden"
                            value="0"
                        />
                    @endif

                    <select
                        id="secondary-object-select-{{ $uuid }}"
                        wire:model="{{ $modelKey }}.secondary_object_name"
                        class="form-select rounded-none border-none w-full"
                    >
                        <option>
                            None
                        </option>
                        @foreach ($features as $feature)
                            <option>
                                {{ $feature->name }}
                            </option>
                        @endforeach
                    </select>
                @endcomponent
            </div>
        </div>

        <div class="contents lg:flex lg:border-r border-gray-400">
            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Order', 'for' => "order-select-$uuid"])
                    <select
                        id="order-select-{{ $uuid }}"
                        wire:model="{{ $modelKey }}.order_name"
                        name="{{ $prefix }}[orders][]"
                        class="form-select rounded-none border-none w-full"
                    >
                        @foreach ($orders as $order)
                            <option>
                                {{ $order->name }}
                            </option>
                        @endforeach
                    </select>
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Mode', 'for' => "mode-select-$uuid"])
                    <select
                        id="mode-select-{{ $uuid }}"
                        wire:model="{{ $modelKey }}.mode_name"
                        name="{{ $prefix }}[modes][]"
                        class="form-select rounded-none border-none w-full"
                    >
                        @foreach ($modes as $mode)
                            <option>
                                {{ $mode->name }}
                            </option>
                        @endforeach
                    </select>
                @endcomponent
            </div>
        </div>

        <div class="contents lg:flex lg:border-r border-gray-400">
            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Negative', 'for' => "negative-checkbox-$uuid"])
                    <div class="flex justify-center">
                        <input
                            id="negative-checkbox-{{ $uuid }}"
                            type="checkbox"
                            wire:model="{{ $modelKey }}.is_negative"
                            class="form-checkbox h-6 w-6 my-2 rounded-none text-blue-400"
                        />
                    </div>
                @endcomponent
            </div>

            <div class="contents lg:block">
                @component('components.search.field', ['label' => 'Diminutive', 'for' => "diminutive-checkbox-$uuid"])
                    <div class="flex justify-center">
                        <input
                            id="diminutive-checkbox-{{ $uuid }}"
                            type="checkbox"
                            wire:model="{{ $modelKey }}.is_diminutive"
                            class="form-checkbox h-6 w-6 my-2 rounded-none text-blue-400"
                        />
                    </div>
                @endcomponent
            </div>
        </div>
    </div>
</div>
