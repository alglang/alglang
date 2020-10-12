@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details'],
    ['hash' => 'morphemes', 'count' => $source->morphemes_count],
    ['hash' => 'nominal_paradigms', 'count' => $source->nominal_paradigms_count],
    ['hash' => 'verb_forms', 'count' => $source->verb_forms_count],
    ['hash' => 'nominal_forms', 'count' => $source->nominal_forms_count],
    ['hash' => 'examples', 'count' => $source->examples_count],
    ['hash' => 'rules', 'count' => $source->rules_count],
];
@endphp

@section('content')
    <x-details title="Source details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {{ $source->short_citation }}
            </h1>
        @endslot

        @slot('basic_details')
            @if($source->full_citation)
            <x-detail-row label="Full citation">
                <div class="hanging-indent">
                    {!! $source->full_citation !!}
                </div>
            </x-detail-row>
            @endif

            @if($source->summary)
            <x-detail-row label="Summary">
                {!! $source->summary !!}
            </x-detail-row>
            @endif

            @if($source->website)
            <x-detail-row label="Website">
                <a href="{{ $source->website }}">
                    {{ $source->website }}
                </a>
            </x-detail-row>
            @endif

            @if($source->notes)
            <x-detail-row label="Notes">
                {!! $source->notes !!}
            </x-detail-row>
            @endif
        @endslot

        @slot('morphemes')
            <livewire:collections.morphemes :model="$source" />
        @endslot

        @slot('nominal_paradigms')
            <ul>
                @foreach($source->nominalParadigms as $paradigm)
                <li>
                    <x-preview-link :model="$paradigm">
                        {{ $paradigm->name }}
                    </x-preview-link>
                </li>
                @endforeach
            </ul>
        @endslot

        @slot('verb_forms')
            <livewire:collections.verb-forms :model="$source"></livewire:collections.verb-forms>
        @endslot

        @slot('nominal_forms')
            <livewire:collections.nominal-forms :model="$source"></livewire:collections.nominal-forms>
        @endslot

        @slot('examples')
            <livewire:collections.examples :model="$source"></livewire:collections.examples>
        @endslot

        @slot('rules')
            <ul>
                @foreach($source->rules as $rule)
                <li>
                    <x-preview-link :model="$rule">
                        {{ $rule->name }}
                    </x-preview-link>
                </li>
                @endforeach
            </ul>
        @endslot
    </x-details>
@endsection
