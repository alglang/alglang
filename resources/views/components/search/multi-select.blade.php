@php
    $id = Str::uuid();
@endphp

<legend
    id="{{ $id }}"
    class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2 w-full"
>
    {{ $label }}
</legend>
<fieldset
    aria-labelledby="{{ $id }}"
    class="overflow-auto p-2 border-l border-r border-b border-gray-300 h-56 scrollbar-thin scrollbar-thumb-gray-600 hover:scrollbar-thumb-gray-500"
    style="overflow: auto"
>
    @foreach ($options as $option)
        <label class="flex items-center mb-2 last:mb-0">
            <input
                type="checkbox"
                wire:model="{{ $modelKey }}.{{ $option->$optionValueKey }}"
                name="{{ $name }}"
                value="{{ $option->$optionValueKey }}"
                class="form-checkbox rounded-none text-blue-400"
            />
            <span class="ml-1">
                {{ $option->$optionLabelKey }}
            </span>
        </label>
    @endforeach
</fieldset>
