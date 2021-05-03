@php
    $id = Str::uuid();
@endphp

@component('components.search.field', ['label' => $label, 'for' => $id])
    @if ($value)
        @if ($value->person)
            <input
                name="{{ $prefix }}[{{ $featureKey }}_persons][]"
                type="hidden"
                value="{{ $value->person }}"
            />
        @endif

        @if ($value->number)
            <input
                name="{{ $prefix }}[{{ $featureKey }}_numbers][]"
                type="hidden"
                value="{{ $value->number }}"
            />
        @endif
    @else
        <input
            name="{{ $prefix }}[{{ $featureKey }}]"
            type="hidden"
            value="0"
        />
    @endif

    <select
        id="{{ $id }}"
        wire:model="{{ $modelKey }}.{{ $featureKey }}_name"
        class="form-select rounded-none border-none w-full focus:z-10"
    >
        @if (isset($includeNone) && $includeNone)
            <option>
                None
            </option>
        @endif

        @foreach ($features as $feature)
            <option>
                {{ $feature->name }}
            </option>
        @endforeach
    </select>
@endcomponent
