@extends('layouts.app')

@section('content')
    <alglang-details title="Verb paradigm details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800">
                {{ $paradigm->name }}
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$paradigm->language">
                    {{ $paradigm->language->name }}
                </x-preview-link>
            </p>
        </template>

        <alglang-detail-page title="Basic details">
            <alglang-detail-row label="Paradigm">
                <x-paradigm-table :forms="$paradigm->forms" />
            </alglang-detail-row>
        </alglang-detail-page>
    </alglang-details>
@endsection
