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
                <div
                    aria-labelledby="description-detail-row-title"
                    class="p-2 mb-2 flex items-center"
                >
                    <h3
                        id="description-detail-row-title"
                        class="inline-block w-64 uppercase"
                    >
                        Description
                    </h3>
                    <div class="inline-block w-full">
                        <p>
                            {{ $group->description }}
                        </p>
                    </div>
                </div>

                <div
                    aria-labelledby="languages-detail-row-title"
                    class="p-2 mb-2 flex items-center"
                >
                    <h3
                        id="languages-detail-row-title"
                        class="inline-block w-64 uppercase"
                    >
                        Languages
                    </h3>
                    <div class="inline-block w-full">
                        <alglang-map style="height: 300px" :locations="{{ $group->languages }}" />
                    </div>
                </div>
            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
