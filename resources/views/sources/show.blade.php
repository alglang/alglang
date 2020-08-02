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
    </alglang-details>
@endsection
