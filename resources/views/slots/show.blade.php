@extends('layouts.app')

@section('content')
    <x-details title="Slot details" :pages="[['hash' => 'basic_details']]">
        @slot('header')
            <h1 class="text-2xl text-gray-800" style="color: {{ $slot->colour }}">
                {{ $slot->abv }}
            </h1>
        @endslot

        @slot('basic_details')
            <x-detail-row label="Full name">
                <p>{{ $slot->name }}</p>
            </x-detail-row>

            @if ($slot->description)
            <x-detail-row label="Description">
                {!! $slot->description !!}
            </x-detail-row>
            @endif
        @endslot
    </x-details>
@endsection
