@php
    $filterSlug = uniqid();
@endphp

<span>
    <label
        for="filter-input-{{ $filterSlug }}"
        class="block uppercase text-gray-700 text-xs uppercase tracking-wider"
    >
        Filter
    </label>
    <input
        id="filter-input-{{ $filterSlug }}"
        class="p-2 bg-gray-200 hover:bg-gray-100 focus:bg-gray-100 shadow-inner transition-bg duration-75 ease-in-out"
        wire:model="filter"
    />
</span>
