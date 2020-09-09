@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details']
];
@endphp

@section('content')
    <x-details title="Nominal paradigm details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {{ $paradigm->name }}
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$paradigm->language">
                    {{ $paradigm->language->name }}
                </x-preview-link>
            </p>
        @endslot

        @slot('basic_details')
            <x-detail-row label="Type">
                <p>{{ $paradigm->type->name }}</p>
            </x-detail-row>

            <x-detail-row label="Paradigm">
                <x-paradigm-table :forms="$paradigm->forms" />
            </x-detail-row>

            @if($paradigm->sources->count() > 0)
            <x-detail-row label="Sources">
                <x-source-list :sources="$paradigm->sources" />
            </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
