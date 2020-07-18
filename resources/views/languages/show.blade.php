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
                <div
                    aria-labelledby="algonquianist-code-detail-row-title"
                    class="p-2 mb-2 flex items-center"
                >
                    <h3
                        id="algonquianist-code-detail-row-title"
                        class="inline-block w-64 uppercase"
                    >
                        Algonquianist code
                    </h3>
                    <div class="inline-block w-full">
                        <p>
                            {{ $language->algo_code }}
                        </p>
                    </div>
                </div>

                <div
                    aria-labelledby="group-detail-row-title"
                    class="p-2 mb-2 flex items-center"
                >
                    <h3
                        id="group-detail-row-title"
                        class="inline-block w-64 uppercase"
                    >
                        Group
                    </h3>
                    <div class="inline-block w-full">
                        <p>
                            <a href="{{ $language->group->url }}">
                                {{ $language->group->name }}
                            </a>
                        </p>
                    </div>
                </div>

                @if($language->parent)
                    <div
                        aria-labelledby="parent-detail-row-title"
                        class="p-2 mb-2 flex items-center"
                    >
                        <h3
                            id="parent-detail-row-title"
                            class="inline-block w-64 uppercase"
                        >
                            Parent
                        </h3>
                        <div class="inline-block w-full">
                            <p>
                                <a href="{{ $language->parent->url }}">
                                    {{ $language->parent->name }}
                                </a>
                            </p>
                        </div>
                    </div>
                @endif

                @if ($language->notes)
                    <div
                        aria-labelledby="notes-detail-row-title"
                        class="p-2 mb-2 flex items-center"
                    >
                        <h3
                            id="notes-detail-row-title"
                            class="inline-block w-64 uppercase"
                        >
                            Notes
                        </h3>
                        <div class="inline-block w-full">
                            {!! $language->notes !!}
                        </div>
                    </div>
                @endif

                @if ($language->position)
                    <div
                        aria-labelledby="location-detail-row-title"
                        class="p-2 mb-2 flex items-center"
                    >
                        <h3
                            id="location-detail-row-title"
                            class="inline-block w-64 uppercase"
                        >
                            Location
                        </h3>
                        <div class="inline-block w-full">
                            <alglang-map style="height: 300px" :locations="[{ name: '{{ $language->name }}', url: '{{ $language->url }}', position: {{ json_encode($language->position) }} }]" />
                        </div>
                    </div>
                @endif
            </div>
        </alglang-detail-page>

        <alglang-detail-page title="Morphemes">
        </alglang-detail-page>
    </alglang-details>
@endsection
