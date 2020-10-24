@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details']
];
@endphp

@section('content')
    <x-details title="Gap details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {{ $gap->structure->name }}
            </h1>

            <div class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$gap->language">
                    {{ $gap->language->name }}
                </x-preview-link>
            </div>
        @endslot

        @slot('basic_details')
            @if ($gap->historical_notes)
            <x-detail-row label="Historical notes">
                {!! $gap->historical_notes !!}
            </x-detail-row>
            @endif

            @if ($gap->usage_notes)
            <x-detail-row label="Usage notes">
                {!! $gap->usage_notes !!}
            </x-detail-row>
            @endif

            @can ('view private notes')
            @if ($gap->private_notes)
            <x-detail-row label="Private notes">
                {!! $gap->private_notes !!}
            </x-detail-row>
            @endif
            @endcan

            @if ($gap->sources->count() > 0)
            <x-detail-row label="Sources">
                <x-source-list :sources="$gap->sources" />
            </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
