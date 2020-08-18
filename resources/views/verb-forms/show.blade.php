@extends('layouts.app')

@section('content')
    <alglang-details title="Verb form details">
        <template v-slot:header>
            <h1 class="text-3xl font-light">
                {{ $verbForm->shape }}
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$verbForm->language">
                    {{ $verbForm->language->name }}
                </x-preview>
            </p>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Description">
                    <p>
                        <span>{{ $verbForm->structure->feature_string }}</span>
                        <span>{{ $verbForm->structure->class->abv }}</span>
                        <span>{{ $verbForm->structure->order->name }}</span>
                        <span>{{ $verbForm->structure->mode->name }}</span>
                    </p>
                </alglang-detail-row>

                @if($verbForm->morphemes->count() > 0)
                    <alglang-detail-row label="Morphology">
                        <x-morpheme-table :morphemes="$verbForm->morphemes" />
                    </alglang-detail-row>
                @endif

                @if($verbForm->historical_notes)
                    <alglang-detail-row label="Historical notes">
                        {!! $verbForm->historical_notes !!}
                    </alglang-detail-row>
                @endif

                @if($verbForm->allomorphy_notes)
                    <alglang-detail-row label="Allomorphy">
                        {!! $verbForm->allomorphy_notes !!}
                    </alglang-detail-row>
                @endif

                @if($verbForm->usage_notes)
                    <alglang-detail-row label="Usage notes">
                        {!! $verbForm->usage_notes !!}
                    </alglang-detail-row>
                @endif

                @can('view private notes')
                @if($verbForm->private_notes)
                    <alglang-detail-row label="Private notes">
                        {!! $verbForm->private_notes !!}
                    </alglang-detail-row>
                @endif
                @endcan

                @if($verbForm->sources->count() > 0)
                    <alglang-detail-row label="Sources">
                        <x-source-list :sources="$verbForm->sources" />
                    </alglang-detail-row>
                @endif
            </div>
        </alglang-detail-page>

        <alglang-detail-page title="Examples" :count="{{ $verbForm->examples_count }}">
            <alglang-examples url="/api/examples?form_id={{ $verbForm->id }}"></alglang-examples>
        </alglang-detail-page>
    </alglang-details>
@endsection
