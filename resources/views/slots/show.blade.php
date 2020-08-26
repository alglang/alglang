@extends('layouts.app')

@section('content')
    <alglang-details title="Slot details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800" style="color: {{ $slot->colour }}">
                {{ $slot->abv }}
            </h1>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Full name">
                    <p>{{ $slot->name }}</p>
                </alglang-detail-row>

                @if ($slot->description)
                    <alglang-detail-row label="Description">
                        {!! $slot->description !!}
                    </alglang-detail-row>
                @endif
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
