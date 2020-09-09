@extends('layouts.app')

@php
$pages = [
    ['hash' => 'basic_details']
];
@endphp

@section('content')
    <x-details title="Group details" :pages="$pages">
        @slot('header')
            <h1 class="text-2xl text-gray-800">
                {{ $group->name }} languages
            </h1>
        @endslot

        @slot('basic_details')
            @if($group->parent)
            <x-detail-row label="Parent">
                <p>
                    <x-preview-link :model="$group->parent">
                        {{ $group->parent->name }}
                    </x-preview-link>
                </p>
            </x-detail-row>
            @endif

            @if($group->children->count() > 0)
            <x-detail-row label="Children">
                <ul>
                    @foreach($group->children as $child)
                    <li>
                        <x-preview-link :model="$child">
                            {{ $child->name }}
                        </x-preview-link>
                    </li>
                    @endforeach
                </ul>
            </x-detail-row>
            @endif

            @if($group->description)
            <x-detail-row label="Description">
                <p>{{ $group->description }}</p>
            </x-detail-row>
            @endif

            <x-detail-row label="Languages">
                @livewire('map', ['locations' => $group->languagesWithDescendants()->whereNotNull('position')])

                <div class="mt-2">
                    <b class="font-semibold">Not shown:</b>

                    <ul class="flex">
                        @foreach($group->languagesWithDescendants()->whereNull('position')->sortBy('name') as $language)
                        @if(!$loop->first)
                        <span class="mx-1" aria-hidden="true">&#9642;</span>
                        @endif

                        <li>
                            <x-preview-link :model="$language">
                                {{ $language->name }}
                            </x-preview-link>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </x-detail-row>
        @endslot
    </x-details>
@endsection
