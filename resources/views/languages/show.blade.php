@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details',],
    ['hash' => 'morphemes', 'count' => $language->morphemes_count],
    ['hash' => 'verb_forms', 'count' => $language->verb_forms_count],
];

if ($language->sources_count) {
    $pages[] = ['hash' => 'sources', 'count' => $language->sources_count];
}
@endphp

@section('content')
    @component('components.details', [
        'title' => 'Language details',
        'pages' => $pages
    ])
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {{ $language->name }}
            </h1>

            @if ($language->reconstructed)
            <p class="mb-2 p-1 inline text-sm leading-none bg-gray-300 rounded">
                Reconstructed
            </p>
            @endif
        @endslot

        @slot('basic_details')
            <livewire:languages.basic-details :language="$language" />
        @endslot

        @slot('morphemes')
            <livewire:collections.morphemes :model="$language" />
        @endslot

        @slot('verb_forms')
            <livewire:collections.verb-forms :model="$language"></livewire:collections.verb-forms>
        @endslot

        @slot('sources')
            <livewire:collections.sources :model="$language"></livewire:collections.sources>
        @endslot
    @endcomponent
@endsection
