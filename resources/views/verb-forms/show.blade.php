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
                        <span>{{ $verbForm->argument_string }}</span>
                        <span>{{ $verbForm->class->abv }}</span>
                        <span>{{ $verbForm->order->name }}</span>
                        <span>{{ $verbForm->mode->name }}</span>
                    </p>
                </alglang-detail-row>

                @if($verbForm->morphemes->count() > 0)
                    <alglang-detail-row label="Morphology">
                        <table class="text-center">
                            <tbody>
                                <tr class="hyphenated">
                                    @foreach($verbForm->morphemes as $morpheme)
                                        <td class="px-3 first:pl-0 pb-1">
                                            <a href="{{ $morpheme->url }}" style="color: {{ $morpheme->slot->colour }};">
                                                {{ trim($morpheme->shape, '-') }}
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>

                                <tr>
                                    @foreach($verbForm->morphemes as $morpheme)
                                        <td class="px-3 first:pl-0" style="color: {{ $morpheme->slot->colour }};">
                                            <alglang-gloss-field :value="{{ $morpheme->glosses }}" />
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
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
                    <ul>
                        <alglang-detail-row label="Sources">
                            <ul>
                                @foreach($verbForm->sources as $source)
                                    <x-preview-link :model="$source">
                                        {{ $source->short_citation }}
                                    </x-preview-link>
                                @endforeach
                            </ul>
                        </alglang-detail-row>
                    </ul>
                @endif
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
