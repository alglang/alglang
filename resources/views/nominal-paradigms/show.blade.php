@extends('layouts.app')

@section('content')
    <alglang-details title="Nominal paradigm details">
        <template v-slot:header>
            <h1 class="text-3xl font-light">
                {{ $paradigm->name }}
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$paradigm->language">
                    {{ $paradigm->language->name }}
                </x-preview-link>
            </p>
        </template>

        <alglang-detail-page title="Basic details">
            <alglang-detail-row label="Type">
                <p>{{ $paradigm->type->name }}</p>
            </alglang-detail-row>

            <alglang-detail-row label="Paradigm">
                <x-paradigm-table :forms="$paradigm->forms" />
            </alglang-detail-row>

            @if($paradigm->sources->count() > 0)
                <alglang-detail-row label="Sources">
                    <x-source-list :sources="$paradigm->sources" />
                </alglang-detail-row>
            @endif
        </alglang-detail-page>
    </alglang-details>
@endsection
