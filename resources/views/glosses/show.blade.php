@extends('layouts.app')

@section('content')
    <alglang-details title="Gloss details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800">
                {{ $gloss->abv }}
            </h1>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Full name">
                    <p>{{ $gloss->name }}</p>
                </alglang-detail-row>

                @if ($gloss->description)
                    <alglang-detail-row label="Description">
                        {!! $gloss->description !!}
                    </alglang-detail-row>
                @endif
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
