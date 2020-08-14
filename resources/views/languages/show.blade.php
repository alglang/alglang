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
                        <x-preview-link :model="$language->group">
                            {{ $language->group->name }}
                        </x-preview-link>
                    </p>
                </alglang-detail-row>

                @if($language->parent)
                    <alglang-detail-row label="Parent">
                        <p>
                            <x-preview-link :model="$language->parent">
                                {{ $language->parent->name }}
                            </x-preview-link>
                        </p>
                    </alglang-detail-row>
                @endif

                @if($language->children->count() > 0)
                    <alglang-detail-row label="Direct descendants">
                        <ul>
                            @foreach($language->children as $child)
                                <li>
                                    <x-preview-link :model="$child">
                                        {{ $child->name }}
                                    </x-preview-link>
                                </li>
                            @endforeach
                        </ul>
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

        <alglang-detail-page title="Morphemes" :count="{{ $language->morphemes_count }}">
            <alglang-language-morphemes url="/api/morphemes?language_id={{ $language->id }}" />
        </alglang-detail-page>

        <alglang-detail-page title="Verb forms" :count="{{ $language->verb_forms_count }}">
            <alglang-language-verb-forms url="/api/verb-forms?language_id={{ $language->id }}" />
        </alglang-detail-page>

        <alglang-detail-page title="Nominal forms" :count="{{ $language->nominal_forms_count }}">
            <alglang-nominal-forms url="/api/nominal-forms?language_id={{ $language->id }}" />
        </alglang-detail-page>

        @if($language->sources_count)
            <alglang-detail-page title="Sources" :count="{{ $language->sources_count }}">
                <alglang-sources url="/api/sources?language_id={{ $language->id }}" />
            </alglang-detail-page>
        @endif
    </alglang-details>
@endsection
