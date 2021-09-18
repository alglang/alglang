@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 leading-normal text-lg">
        <p>
            Interactive verb paradigms are available for the following languages. Click on any language for a list of available paradigms:
        </p>

        <ul class="w-fit mt-2 mb-6">
            @foreach ($languages as $language)
            <li class="px-2 bg-gray-200 md:bg-gray-100 border-l-4 border-red-600">
                <a href="{{ $language->url }}#verb_paradigms">{{ $language->name }}</a>
            </li>
            @endforeach
        </ul>

        <p>
            You can also do a custom search for a particular paradigm or set of paradigms in one or more languages using the search tool below, or try one of the following sample searches:
        </p>

        <ul class="w-fit mt-2 mb-6">
            <li class="px-2 bg-gray-200 md:bg-gray-100 border-l-4 border-red-600">
                AI Conjunct in all languages
            </li>
            <li class="px-2 bg-gray-200 md:bg-gray-100 border-l-4 border-red-600">
                TA Independent in Cree and Ojibwe
            </li>
        </ul>
    </section>
@endsection
