@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details'],
    ['hash' => 'examples', 'count' => $form->examples_count]
];
@endphp

@section('content')
    <x-details title="Nominal form details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {!! $form->formatted_shape !!}
            </h1>

            <div class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$form->language">
                    {{ $form->language->name }}
                </x-preview>
            </div>
        @endslot

        @slot('basic_details')
            <x-detail-row label="Paradigm">
                <x-preview-link :model="$form->structure->paradigm">
                    {{ $form->structure->paradigm->name }}
                </x-preview-link>
            </x-detail-row>

            @if($form->structure->pronominalFeature)
            <x-detail-row label="Pronominal feature">
                <x-preview-link :model="$form->structure->pronominalFeature">
                    {{ $form->structure->pronominalFeature->name }}
                </x-preview-link>
            </x-detail-row>
            @endif

            @if($form->structure->nominalFeature)
            <x-detail-row label="Nominal feature">
                <x-preview-link :model="$form->structure->nominalFeature">
                    {{ $form->structure->nominalFeature->name }}
                </x-preview-link>
            </x-detail-row>
            @endif

            @if($form->phonemic_shape)
            <x-detail-row label="Phonology">
                <p>
                    {!! $form->formatted_phonemic_shape !!}
                </p>
            </x-detail-row>
            @endif

            @if($form->morphemes->count() > 0)
            <x-detail-row label="Morphology">
                <x-morpheme-table :morphemes="$form->morphemes" />
            </x-detail-row>
            @endif

            @if($form->parent)
            <x-detail-row label="Parent">
                <div class="mb-2">
                    <x-preview-link :model="$form->parent">
                        {{ $form->parent->shape }}
                    </x-preview-link>

                    <span class="inline-flex">
                        (
                        <x-preview-link :model="$form->parent->language">
                            {{ $form->parent->language->name }}
                        </x-preview-link>
                        )
                    </span>
                </div>

                @if($form->parent->morphemes->count() > 0)
                <x-morpheme-table :morphemes="$form->parent->morphemes" />
                @endif
            </x-detail-row>
            @endif
            
            @if($form->historical_notes)
            <x-detail-row label="Historical notes">
                {!! $form->historical_notes !!}
            </x-detail-row>
            @endif
            
            @if($form->allomorphy_notes)
            <x-detail-row label="Allomorphy">
                {!! $form->allomorphy_notes !!}
            </x-detail-row>
            @endif
            
            @if($form->usage_notes)
            <x-detail-row label="Usage notes">
                {!! $form->usage_notes !!}
            </x-detail-row>
            @endif

            @can('view private notes')
            @if($form->private_notes)
            <x-detail-row label="Private notes">
                {!! $form->private_notes !!}
            </x-detail-row>
            @endif
            @endcan

            @if($form->sources->count() > 0)
            <x-detail-row label="Sources">
                <x-source-list :sources="$form->sources" />
            </x-detail-row>
            @endif
        @endslot

        @slot('examples')
            <livewire:collections.examples :model="$form"></livewire:collections.examples>
        @endslot
    </x-details>
@endsection
