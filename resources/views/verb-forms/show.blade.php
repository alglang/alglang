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
                        <span>{{ $verbForm->subject->name }}</span>
                        <span>{{ $verbForm->class->abv }}</span>
                        <span>{{ $verbForm->order->name }}</span>
                        <span>{{ $verbForm->mode->name }}</span>
                    </p>
                </alglang-detail-row>

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
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
