@extends('layouts.app')

@section('content')
    <alglang-details title="Morpheme details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800">
                <span>{{ $morpheme->shape }}</span><!--
             --><span class="text-base align-top">{{ $morpheme->disambiguator + 1 }}</span>
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <x-preview-link :model="$morpheme->language">
                    {{ $morpheme->language->name }}
                </x-preview-link>
            </p>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Gloss">
                    <p class="small-caps">{{ $morpheme->gloss }}</p>
                </alglang-detail-row>

                <alglang-detail-row label="Slot">
                    <p>
                        <a href="{{ $morpheme->slot->url }}" style="color: {{ $morpheme->slot->colour }}">
                            {{ $morpheme->slot->abv }}
                        </a>
                    </p>
                </alglang-detail-row>

                @if ($morpheme->parent)
                    <alglang-detail-row label="Parent">
                        <x-preview-link :model="$morpheme->parent">
                            {{ $morpheme->parent->shape }}
                        </x-preview-link>
                        @if($morpheme->parent->gloss)
                            <span class="small-caps">
                                ({{ $morpheme->parent->gloss }})
                            </span>
                        @endif
                        <span class="inline-flex">
                            (
                            <x-preview-link :model="$morpheme->parent->language">
                                {{ $morpheme->parent->language->name }}
                            </x-preview-link>
                            )
                        </span>
                    </alglang-detail-row>
                @endif

                @if ($morpheme->historical_notes)
                    <alglang-detail-row label="Historical notes">
                        {!! $morpheme->historical_notes !!}
                    </alglang-detail-row>
                @endif

                @if ($morpheme->allomorphy_notes)
                    <alglang-detail-row label="Allomorphy notes">
                        {!! $morpheme->allomorphy_notes !!}
                    </alglang-detail-row>
                @endif

                @if ($morpheme->usage_notes)
                    <alglang-detail-row label="Usage notes">
                        {!! $morpheme->usage_notes !!}
                    </alglang-detail-row>
                @endif

                @can('view private notes')
                @if ($morpheme->private_notes)
                    <alglang-detail-row label="Private notes">
                        {!! $morpheme->private_notes !!}
                    </alglang-detail-row>
                @endif
                @endcan

                @if ($morpheme->sources->count() > 0)
                    <alglang-detail-row label="Sources">
                        <x-source-list :sources="$morpheme->sources" />
                    </alglang-detail-row>
                @endif
            </div>
        </alglang-detail-page>

        <alglang-detail-page title="Verb forms" :count="{{ $morpheme->verb_forms_count }}">
            <alglang-language-verb-forms url="/api/verb-forms?with_morphemes[]={{ $morpheme->id }}" />
        </alglang-detail-page>

        <alglang-detail-page title="Nominal forms" :count="{{ $morpheme->nominal_forms_count }}">
            <alglang-nominal-forms url="/api/nominal-forms?with_morphemes[]={{ $morpheme->id }}" />
        </alglang-detail-page>
    </alglang-details>
@endsection
