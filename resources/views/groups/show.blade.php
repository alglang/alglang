@extends('layouts.app')

@section('content')
    <section class="bg-white p-6">
        <header class="flex justify-between mb-4">
            <div class="leading-normal">
                <h2 class="block text-lg uppercase text-gray-600">
                    Group details
                </h2>
                <div>
                    <h1 class="text-3xl font-light">
                        {{ $group->name }} languages
                    </h1>
                </div>
            </div>
        </header>

        <div class="flex">
            <nav
                class="flex flex-col uppercase bg-gray-200 font-semibold mr-4"
                style="height: fit-content;"
            >
                <a class="p-2 whitespace-no-wrap cursor-default text-gray-200 bg-red-700">
                    Basic details
                </a>
            </nav>

            <article class="overflow-hidden w-full relative">
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
            </article>
        </div>
    </section>
@endsection
