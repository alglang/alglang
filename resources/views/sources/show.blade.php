@extends('layouts.app')

@section('content')
    <alglang-details title="Source details">
        <template v-slot:header>
            <h1 class="text-3xl font-light">
                {{ $source->short_citation }}
            </h1>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                <alglang-detail-row label="Full citation">
                    <div class="hanging-indent">
                        {!! $source->full_citation !!}
                    </div>
                </alglang-detail-row>
            </div>
        </alglang-detail-page>

        <alglang-detail-page title="Morphemes" :count="{{ $source->morphemes_count }}">
            <alglang-language-morphemes url="/api/morphemes?source_id={{ $source->id }}" />
        </alglang-detail-page>

        <alglang-detail-page title="Verb forms" :count="{{ $source->verb_forms_count }}">
            <alglang-language-verb-forms url="/api/verb-forms?source_id={{ $source->id }}" />
        </alglang-detail-page>

        <alglang-detail-page title="Examples" :count="{{ $source->examples_count }}">
            <alglang-examples url="/api/examples?source_id={{ $source->id }}" />
        </alglang-detail-page>
    </alglang-details>
@endsection
