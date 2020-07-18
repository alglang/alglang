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
                <div
                    aria-labelledby="gloss-detail-row-title"
                    class="p-2 mb-2 flex items-center"
                >
                    <h3
                        id="gloss-detail-row-title"
                        class="inline-block w-64 uppercase"
                    >
                        Gloss
                    </h3>
                    <div class="inline-block w-full">
                        <p>
                            {{ $morpheme->gloss }}
                        </p>
                    </div>
                </div>

                <div
                    aria-labelledby="slot-detail-row-title"
                    class="p-2 mb-2 flex items-center"
                >
                    <h3
                        id="slot-detail-row-title"
                        class="inline-block w-64 uppercase"
                    >
                        Slot
                    </h3>
                    <div class="inline-block w-full">
                        <p>
                        <a href="{{ $morpheme->slot->url }}" style="color: {{ $morpheme->slot->colour }}">
                                {{ $morpheme->slot->abv }}
                            </a>
                        </p>
                    </div>
                </div>

                @if ($morpheme->historical_notes)
                    <div
                        aria-labelledby="historical-notes-detail-row-title"
                        class="p-2 mb-2 flex items-center"
                    >
                        <h3
                            id="historical-notes-detail-row-title"
                            class="inline-block w-64 uppercase"
                        >
                            Historical notes
                        </h3>
                        <div class="inline-block w-full">
                            {!! $morpheme->historical_notes !!}
                        </div>
                    </div>
                @endif

                @if ($morpheme->allomorphy_notes)
                    <div
                        aria-labelledby="allomorphy-notes-detail-row-title"
                        class="p-2 mb-2 flex items-center"
                    >
                        <h3
                            id="allomorphy-notes-detail-row-title"
                            class="inline-block w-64 uppercase"
                        >
                            Allomorphy notes
                        </h3>
                        <div class="inline-block w-full">
                            {!! $morpheme->allomorphy_notes !!}
                        </div>
                    </div>
                @endif

                @can('view private notes')
                @if ($morpheme->private_notes)
                    <div
                        aria-labelledby="private-notes-detail-row-title"
                        class="p-2 mb-2 flex items-center"
                    >
                        <h3
                            id="private-notes-detail-row-title"
                            class="inline-block w-64 uppercase"
                        >
                            Private notes
                        </h3>
                        <div class="inline-block w-full">
                            {!! $morpheme->private_notes !!}
                        </div>
                    </div>
                @endif
                @endcan
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
