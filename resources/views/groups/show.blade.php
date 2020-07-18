@extends('layouts.app')

@section('content')
    <alglang-details title="Group details">
        <template v-slot:header>
            <h1 class="text-3xl font-light">
                {{ $group->name }} languages
            </h1>
        </template>

        <alglang-detail-page title="Basic details">
            <div>
                @if($group->description)
                    <alglang-detail-row label="Description">
                        <p>{{ $group->description }}</p>
                    </alglang-detail-row>
                @endif

                <alglang-detail-row label="Languages">
                    <alglang-map style="height: 300px" :locations="{{ $group->languages }}" />
                </alglang-detail-row>
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
