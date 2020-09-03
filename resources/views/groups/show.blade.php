@extends('layouts.app')

@section('content')
    <alglang-details title="Group details">
        <template v-slot:header>
            <h1 class="text-2xl text-gray-800">
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
                    @livewire('map', ['locations' => $group->languagesWithDescendants()->whereNotNull('position')])

                    <div class="mt-2">
                        <b class="font-semibold">Not shown:</b>

                        <ul class="flex">
                            @foreach($group->languagesWithDescendants()->whereNull('position')->sortBy('name') as $language)
                                @if(!$loop->first)
                                    <span class="mx-1" aria-hidden="true">&#9642;</span>
                                @endif

                                <li>
                                    <x-preview-link :model="$language">
                                        {{ $language->name }}
                                    </x-preview-link>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </alglang-detail-row>

            </div>
        </alglang-detail-page>
    </alglang-details>
@endsection
