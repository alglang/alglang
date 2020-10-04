@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details']
];
@endphp

@section('content')
    <x-details title="Reflex details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                <x-preview-link :model="$reflex->phoneme">
                    {!! $reflex->phoneme->formattedShape !!}
                </x-preview-link>
                &gt;
                <x-preview-link :model="$reflex->reflex">
                    {!! $reflex->reflex->formattedShape !!}
                </x-preview-link>
            </h1>

            <div class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$reflex->phoneme->language">
                    {{ $reflex->phoneme->language->name }}
                </x-preview-link>
                &gt;
                <x-preview-link :model="$reflex->reflex->language">
                    {{ $reflex->reflex->language->name }}
                </x-preview-link>
            </div>
        @endslot

        @slot('basic_details')
            <x-detail-row label="Environment">
                <p>
                    {{ $reflex->environment ?? 'Elsewhere' }}
                </p>
            </x-detail-row>

            @if ($reflex->public_notes)
            <x-detail-row label="Notes">
                {!! $reflex->public_notes !!}
            </x-detail-row>
            @endif

            @can ('view private notes')
            @if ($reflex->private_notes)
            <x-detail-row label="Private notes">
                {!! $reflex->private_notes !!}
            </x-detail-row>
            @endif
            @endcan

            @if ($reflex->sources->count() > 0)
            <x-detail-row label="Sources">
                <x-source-list :sources="$reflex->sources" />
            </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
