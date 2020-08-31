@extends('layouts.app')

@section('content')
    <alglang-details title="Example details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800">
                {!! $example->formatted_shape !!}
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$example->form->language">
                    {{ $example->form->language->name }}
                </x-preview>
            </p>
        </template>

        <alglang-detail-page title="Basic details">
            <alglang-detail-row label="Form">
                <x-preview-link :model="$example->form">
                    {!! $example->form->formatted_shape !!}
                </x-preview-link>
            </alglang-detail-row>

            @if($example->phonemic_shape)
                <alglang-detail-row label="Phonology">
                    <p>
                        {!! $example->formatted_phonemic_shape !!}
                    </p>
                </alglang-detail-row>
            @endif

            <alglang-detail-row label="Morphology">
                <x-morpheme-table :morphemes="$example->morphemes" />
            </alglang-detail-row>

            <alglang-detail-row label="Translation">
                <p>
                    {{ $example->translation }}
                </p>
            </alglang-detail-row>

            @if($example->parent)
                <alglang-detail-row label="Parent">
                    <div class="mb-2">
                        <x-preview-link :model="$example->parent">
                            {{ $example->parent->shape }}
                        </x-preview-link>

                        <span class="inline-flex">
                            (
                            <x-preview-link :model="$example->parent->language">
                                {{ $example->parent->language->name }}
                            </x-preview-link>
                            )
                        </span>
                    </div>
                </alglang-detail-row>
            @endif

            @if($example->notes)
                <alglang-detail-row label="Notes">
                    {!! $example->notes !!}
                </alglang-detail-row>
            @endif

            @can('view private notes')
            @if($example->private_notes)
                <alglang-detail-row label="Private notes">
                    {!! $example->private_notes !!}
                </alglang-detail-row>
            @endif
            @endcan

            @if($example->sources->count() > 0)
                <alglang-detail-row label="Sources">
                    <x-source-list :sources="$example->sources" />
                </alglang-detail-row>
            @endif
        </alglang-detail-page>
    </alglang-details>
@endsection
