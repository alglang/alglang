@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 leading-normal">
        <p class="mb-6 text-lg">
            Welcome to <strong>alglang.net</strong>, a source for information about the sounds and grammar of the <a href="{{ route('groups.show', ['group' => 'algonquian']) }}">Algonquian languages</a>. Our goal is to make the findings of the academic literature on these topics more accessible to all interested audiences. Information is available in the following areas:
        </p>

        <ul class="mb-8">
            <li class="mb-4 md:mb-0 p-2 md:p-1 md:pl-2 border-l-4 border-red-600 bg-gray-200 md:bg-gray-100">
                <a href="{{ route('verb-forms') }}" class="uppercase">Interactive verb paradigms</a>: searchable verb charts for a subset of languages, annotated with glosses, examples, and historical information
            </li>
            <li class="mb-4 md:mb-0 p-2 md:p-1 md:pl-2 border-l-4 border-red-600 bg-gray-200 md:bg-gray-100">
                <a href="{{ route('phonology') }}" class="uppercase">Structural survey</a>: fact sheets on particular points of sound structure and grammatical structure across the Algonquian languages
            </li>
            <li class="p-2 md:p-1 md:pl-2 border-l-4 border-red-600 bg-gray-200 md:bg-gray-100">
                <a href="{{ route('bibliography') }}" class="uppercase">Bibliography</a>: a filterable bibliography of Algonquian linguistics
            </li>
        </ul>

        <p class="mb-8 text-lg">
            The interactive verb paradigms currently include the languages shown on the map below, which are only a subset of the languages in the Algonquian family. Click any language for more information.
        </p>

        @livewire('map', ['locations' => $languages])
    </section>
@endsection
