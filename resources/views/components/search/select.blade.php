@php
    $id = Str::uuid();
@endphp

@component('components.search.field', ['label' => $label, 'for' => $id])
    <select
        id="{{ $id }}"
        wire:model="{{ $modelKey }}"
        name="{{ $name }}"
        class="form-select rounded-none border-none w-full focus:z-10"
    >
        @foreach ($options as $option)
            <option value="{{ isset($optionValueKey) ? $option->$optionValueKey : $option->$optionKey }}">
                {{ $option->$optionKey }}
            </option>
        @endforeach
    </select>
@endcomponent
