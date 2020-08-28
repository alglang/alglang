@extends('layouts.app')

@section('content')
    <alglang-details title="Verb form details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800">
                {!! $verbForm->formatted_shape !!}
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
                        <a href="{{ $verbForm->paradigm->url }}">
                            <span>{{ $verbForm->structure->class->abv }}</span>
                            <span>{{ $verbForm->structure->order->name }}</span>
                            <span>{{ $verbForm->structure->mode->name }}</span>
                            @if($verbForm->structure->is_negative)
                                <span>(Negative)</span>
                            @endif
                            @if($verbForm->structure->is_diminutive)
                                <span>(Diminutive)</span>
                            @endif
                            @isset($verbForm->structure->is_absolute)
                                @if($verbForm->structure->is_absolute)
                                    <span>(Absolute)</span>
                                @else
                                    <span>(Objective)</span>
                                @endif
                            @endif
                        </a>
                    </p>
                </alglang-detail-row>

                @if($verbForm->morphemes->count() > 0)
                    <alglang-detail-row label="Morphology">
                        <x-morpheme-table :morphemes="$verbForm->morphemes" />
                    </alglang-detail-row>
                @endif

                @if($verbForm->parent)
                    <alglang-detail-row label="Parent">
                        <div class="mb-2">
                            <x-preview-link :model="$verbForm->parent">
                                {{ $verbForm->parent->shape }}
                            </x-preview-link>

                            <span class="inline-flex">
                                (
                                <x-preview-link :model="$verbForm->parent->language">
                                    {{ $verbForm->parent->language->name }}
                                </x-preview-link>
                                )
                            </span>
                        </div>

                        @if($verbForm->parent->morphemes->count() > 0)
                            <x-morpheme-table :morphemes="$verbForm->parent->morphemes" />
                        @endif
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
