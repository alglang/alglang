@extends('layouts.app')

@section('content')
    <alglang-details title="Morpheme details">
        <template v-slot:header>
            <h1 class="text-3xl font-light">
                <span>{{ $morpheme->shape }}</span><!--
             --><span class="text-base align-top">{{ $morpheme->disambiguator + 1 }}</span>
            </h1>

            <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
                <a href="{{ $morpheme->language->url }}">
                    {{ $morpheme->language->name }}
                </a>
            </p>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Gloss">
                    <p>{{ $morpheme->gloss }}</p>
                </alglang-detail-row>

                <alglang-detail-row label="Slot">
                    <p>
                        <a href="{{ $morpheme->slot->url }}" style="color: {{ $morpheme->slot->colour }}">
                            {{ $morpheme->slot->abv }}
                        </a>
                    </p>
                </alglang-detail-row>

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

                @can('view private notes')
                @if ($morpheme->private_notes)
                    <alglang-detail-row label="Private notes">
                        {!! $morpheme->private_notes !!}
                    </alglang-detail-row>
                @endif
                @endcan
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
