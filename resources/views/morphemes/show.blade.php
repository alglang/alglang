@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details'],
    ['hash' => 'verb_forms', 'count' => $morpheme->verb_forms_count],
    ['hash' => 'nominal_forms', 'count' => $morpheme->nominal_forms_count]
];
@endphp

@section('content')
    <x-details title="Morpheme details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                <span>{!! $morpheme->formatted_shape !!}</span><!--
             --><span class="text-base align-top">{{ $morpheme->disambiguator + 1 }}</span>
            </h1>

            <div class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$morpheme->language">
                    {{ $morpheme->language->name }}
                </x-preview-link>
            </div>
        @endslot

        @slot('basic_details')
            <x-detail-row label="Gloss">
                <p class="small-caps">{{ $morpheme->gloss }}</p>
            </x-detail-row>

            <x-detail-row label="Slot">
                <p>
                    <a href="{{ $morpheme->slot->url }}" style="color: {{ $morpheme->slot->colour }}">
                        {{ $morpheme->slot->abv }}
                    </a>
                </p>
            </x-detail-row>

            @if ($morpheme->parent)
            <x-detail-row label="Parent">
                <x-preview-link :model="$morpheme->parent">
                    {{ $morpheme->parent->shape }}
                </x-preview-link>
                @if($morpheme->parent->gloss)
                <span class="small-caps">
                    ({{ $morpheme->parent->gloss }})
                </span>
                @endif
                <span class="inline-flex">
                    (
                    <x-preview-link :model="$morpheme->parent->language">
                        {{ $morpheme->parent->language->name }}
                    </x-preview-link>
                    )
                </span>
            </x-detail-row>
            @endif

            @if ($morpheme->historical_notes)
            <x-detail-row label="Historical notes">
                {!! $morpheme->historical_notes !!}
            </x-detail-row>
            @endif

            @if ($morpheme->allomorphy_notes)
            <x-detail-row label="Allomorphy notes">
                {!! $morpheme->allomorphy_notes !!}
            </x-detail-row>
            @endif

            @if ($morpheme->usage_notes)
            <x-detail-row label="Usage notes">
                {!! $morpheme->usage_notes !!}
            </x-detail-row>
            @endif

            @can('view private notes')
            @if ($morpheme->private_notes)
            <x-detail-row label="Private notes">
                {!! $morpheme->private_notes !!}
            </x-detail-row>
            @endif
            @endcan

            @if ($morpheme->sources->count() > 0)
            <x-detail-row label="Sources">
                <x-source-list :sources="$morpheme->sources" />
            </x-detail-row>
            @endif
        @endslot

        @slot('verb_forms')
            {{-- <livewire:collections.verb-forms :url="'/api/verb-forms?with_morphemes[]=' . $morpheme->id" /> --}}
        @endslot

        @slot('nominal_forms')
            {{-- <livewire:collections.nominal-forms :url="'/api/nominal-forms?with_morphemes[]=' . $morpheme->id" /> --}}
        @endslot
    </x-details>
@endsection
