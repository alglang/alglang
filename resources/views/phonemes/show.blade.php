@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details']
];
@endphp

@section('content')
    <x-details title="Phoneme details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {!! $phoneme->formatted_shape !!}
            </h1>

            <div class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$phoneme->language">
                    {{ $phoneme->language->name }}
                </x-preview-link>
            </div>
            <div class="mb-2 ml-1 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                {{ $phoneme->type }}
            </div>
        @endslot

        @slot('basic_details')
            @if ($phoneme->shape)
            <x-detail-row label="Orthographic transcription">
                <i>{{ $phoneme->shape }}</i>
            </x-detail-row>
            @endif

            @if ($phoneme->ipa)
            <x-detail-row label="IPA transcription">
                /<i>{{ $phoneme->ipa }}</i>/
            </x-detail-row>
            @endif

            @if ($phoneme->type === 'vowel')
            <x-detail-row label="Height">
                {{ $phoneme->features->height_name }}
            </x-detail-row>
            <x-detail-row label="Backness">
                {{ $phoneme->features->backness_name }}
            </x-detail-row>
            <x-detail-row label="Length">
                {{ $phoneme->features->length_name }}
            </x-detail-row>
            @endif

            @if ($phoneme->type === 'consonant')
            <x-detail-row label="Place">
                {{ $phoneme->features->place_name }}
            </x-detail-row>
            <x-detail-row label="Manner">
                {{ $phoneme->features->manner_name }}
            </x-detail-row>
            @endif

            @if ($phoneme->sources->count() > 0)
                <x-detail-row label="Sources">
                    <x-source-list :sources="$phoneme->sources" />
                </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
