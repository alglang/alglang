@php
    $id = Str::uuid();
@endphp

@component('components.search.field', ['label' => $label, 'for' => $id])
    <div class="flex justify-center">
        <input
            id={{ $id }}
            type="checkbox"
            wire:model="{{ $modelKey }}"
            class="form-checkbox h-6 w-6 my-2 rounded-none text-blue-400"
        />
    </div>
@endcomponent
