@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details']
];
@endphp

@section('content')
    <x-details title="Example details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {!! $example->formatted_shape !!}
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$example->form->language">
                    {{ $example->form->language->name }}
                </x-preview>
            </p>
        @endslot

        @slot('basic_details')
            <x-detail-row label="Form">
                <x-preview-link :model="$example->form">
                    {!! $example->form->formatted_shape !!}
                </x-preview-link>
            </x-detail-row>

            @if($example->phonemic_shape)
            <x-detail-row label="Phonology">
                <p>
                    {!! $example->formatted_phonemic_shape !!}
                </p>
            </x-detail-row>
            @endif

            <x-detail-row label="Morphology">
                <x-morpheme-table :morphemes="$example->morphemes" />
            </x-detail-row>

            <x-detail-row label="Translation">
                <p>
                    {{ $example->translation }}
                </p>
            </x-detail-row>

            @if($example->parent)
            <x-detail-row label="Parent">
                <div class="mb-2">
                    <x-preview-link :model="$example->parent">
                        {{ $example->parent->shape }}
                    </x-preview-link>

                    <span class="inline-flex">
                        (
                        <x-preview-link :model="$example->parent->language">
                            {{ $example->parent->language->name }}
                        </x-preview-link>
                        )
                    </span>
                </div>
            </x-detail-row>
            @endif

            @if($example->notes)
            <x-detail-row label="Notes">
                {!! $example->notes !!}
            </x-detail-row>
            @endif

            @can('view private notes')
            @if($example->private_notes)
            <x-detail-row label="Private notes">
                {!! $example->private_notes !!}
            </x-detail-row>
            @endif
            @endcan

            @if($example->sources->count() > 0)
            <x-detail-row label="Sources">
                <x-source-list :sources="$example->sources" />
            </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
