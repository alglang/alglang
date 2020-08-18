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
                <table>
                    <thead class="bg-gray-800 text-gray-300 uppercase font-medium tracking-wider text-xs">
                        <tr>
                            <th class="px-4 py-2">Features</th>
                            <th class="px-4 py-2">Form</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paradigm->forms as $form)
                            <tr class="odd:bg-gray-100 even:bg-gray-200">
                                <td class="px-4 py-2">
                                    {{ $form->structure->feature_string }}
                                </td>
                                <td class="px-4 py-2">
                                    <div class="mb-4">
                                        <x-preview-link :model="$form">
                                            {{ $form->shape }}
                                        </x-preview-link>
                                    </div>
                                    <x-morpheme-table :morphemes="$form->morphemes" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </alglang-detail-row>
        </alglang-detail-page>
    </alglang-details>
@endsection
