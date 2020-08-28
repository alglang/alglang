@extends('layouts.app')

@section('content')
    <alglang-details title="Nominal form details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800">
                {!! $form->formatted_shape !!}
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$form->language">
                    {{ $form->language->name }}
                </x-preview>
            </p>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Paradigm">
                    <x-preview-link :model="$form->structure->paradigm">
                        {{ $form->structure->paradigm->name }}
                    </x-preview-link>
                </alglang-detail-row>

                <alglang-detail-row label="Pronominal feature">
                    <x-preview-link :model="$form->structure->pronominalFeature">
                        {{ $form->structure->pronominalFeature->name }}
                    </x-preview-link>
                </alglang-detail-row>

                <alglang-detail-row label="Nominal feature">
                    <x-preview-link :model="$form->structure->nominalFeature">
                        {{ $form->structure->nominalFeature->name }}
                    </x-preview-link>
                </alglang-detail-row>

                @if($form->morphemes->count() > 0)
                    <alglang-detail-row label="Morphology">
                        <x-morpheme-table :morphemes="$form->morphemes" />
                    </alglang-detail-row>
                @endif

                @if($form->parent)
                    <alglang-detail-row label="Parent">
                        <div class="mb-2">
                            <x-preview-link :model="$form->parent">
                                {{ $form->parent->shape }}
                            </x-preview-link>

                            <span class="inline-flex">
                                (
                                <x-preview-link :model="$form->parent->language">
                                    {{ $form->parent->language->name }}
                                </x-preview-link>
                                )
                            </span>
                        </div>

                        @if($form->parent->morphemes->count() > 0)
                            <x-morpheme-table :morphemes="$form->parent->morphemes" />
                        @endif
                    </alglang-detail-row>
                @endif
                
                @if($form->historical_notes)
                    <alglang-detail-row label="Historical notes">
                        {!! $form->historical_notes !!}
                    </alglang-detail-row>
                @endif
                
                @if($form->allomorphy_notes)
                    <alglang-detail-row label="Allomorphy">
                        {!! $form->allomorphy_notes !!}
                    </alglang-detail-row>
                @endif
                
                @if($form->usage_notes)
                    <alglang-detail-row label="Usage notes">
                        {!! $form->usage_notes !!}
                    </alglang-detail-row>
                @endif

                @can('view private notes')
                @if($form->private_notes)
                    <alglang-detail-row label="Private notes">
                        {!! $form->private_notes !!}
                    </alglang-detail-row>
                @endif
                @endcan

                @if($form->sources->count() > 0)
                    <alglang-detail-row label="Sources">
                        <x-source-list :sources="$form->sources" />
                    </alglang-detail-row>
                @endif
            </div>
        </alglang-detail-page>

        <alglang-detail-page title="Examples" :count="{{ $form->examples_count }}">
        </alglang-detail-page>
    </alglang-details>
@endsection
