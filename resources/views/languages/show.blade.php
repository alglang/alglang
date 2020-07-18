@extends('layouts.app')

@section('content')
    <alglang-details title="Language details">
        <template v-slot:header>
            <h1 class="text-3xl font-light">
                {{ $language->name }}
            </h1>

            @if ($language->reconstructed)
                <p class="mb-2 p-1 inline text-sm leading-none bg-gray-300 rounded">
                    Reconstructed
                </p>
            @endif
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Algonquianist code">
                    <p>{{ $language->algo_code }}</p>
                </alglang-detail-row>

                @if($language->iso)
                    <alglang-detail-row label="ISO">
                        <p>{{ $language->iso }}</p>
                    </alglang-detail-row>
                @endif

                <alglang-detail-row label="Group">
                    <p>
                        <a href="{{ $language->group->url }}">
                            {{ $language->group->name }}
                        </a>
                    </p>
                </alglang-detail-row>

                @if($language->parent)
                    <alglang-detail-row label="Parent">
                        <p>
                            <a href="{{ $language->parent->url }}">
                                {{ $language->parent->name }}
                            </a>
                        </p>
                    </alglang-detail-row>
                @endif

                @if ($language->notes)
                    <alglang-detail-row label="Notes">
                        {!! $language->notes !!}
                    </alglang-detail-row>
                @endif

                @if ($language->position)
                    <alglang-detail-row label="Location">
                        <alglang-map
                            style="height: 300px"
                            :locations="[{ name: '{{ $language->name }}', url: '{{ $language->url }}', position: {{ json_encode($language->position) }} }]"
                        />
                    </alglang-detail-row>
                @endif
            </div>
        </alglang-detail-page>

        <alglang-detail-page title="Morphemes">
            <alglang-language-morphemes url="/api{{ $language->url }}/morphemes" />
        </alglang-detail-page>
    </alglang-details>
@endsection
