@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details']
];
@endphp

@section('content')
    <x-details title="Rule details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {!! $rule->name !!}
            </h1>

            <div class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$rule->language">
                    {{ $rule->language->name }}
                </x-preview-link>
            </div>
        @endslot

        @slot('basic_details')
            @can('view rule abbreviations')
            <x-detail-row label="Abbreviation">
                <p>{{ $rule->abv }}</p>
            </x-detail-row>
            @endcan

            <x-detail-row label="Type">
                <p>{{ $rule->type->name }}</p>
            </x-detail-row>

            @if ($rule->description)
            <x-detail-row label="Description">
                {!! $rule->description !!}
            </x-detail-row>
            @endif

            @if ($rule->public_notes)
            <x-detail-row label="Notes">
                {!! $rule->public_notes !!}
            </x-detail-row>
            @endif

            @can('view private notes')
            @if ($rule->private_notes)
            <x-detail-row label="Private notes">
                {!! $rule->private_notes !!}
            </x-detail-row>
            @endif
            @endcan

            @if ($rule->sources->count() > 0)
            <x-detail-row label="Sources">
                <x-source-list :sources="$rule->sources" />
            </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
