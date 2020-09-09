@extends('layouts.app')

@section('content')
    <x-details title="Gloss details" :pages="[['hash' => 'basic_details']]">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {{ $gloss->abv }}
            </h1>
        @endslot

        @slot('basic_details')
            <x-detail-row label="Full name">
                <p>{{ $gloss->name }}</p>
            </x-detail-row>

            @if ($gloss->description)
            <x-detail-row label="Description">
                {!! $gloss->description !!}
            </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
