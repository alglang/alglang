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
                @if($group->parent)
                    <alglang-detail-row label="Parent">
                        <p>
                            <x-preview-link :model="$group->parent">
                                {{ $group->parent->name }}
                            </x-preview-link>
                        </p>
                    </alglang-detail-row>
                @endif

                @if($group->children->count() > 0)
                    <alglang-detail-row label="Children">
                        <ul>
                            @foreach($group->children as $child)
                                <li>
                                    <x-preview-link :model="$child">
                                        {{ $child->name }}
                                    </x-preview-link>
                                </li>
                            @endforeach
                        </ul>
                    </alglang-detail-row>
                @endif

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
