@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 leading-normal">
        <h1 class="text-2xl mb-8">Database of Algonquian Language Structures</h1>

        <p class="mb-6 text-lg">
            This database provides information about the sounds and grammar of the <a href="{{ route('groups.show', ['group' => 'algonquian']) }}">Algonquian languages</a>, focusing on the following areas:
        </p>

        <ul class="mb-8">
            <li class="mb-4 md:mb-0 p-2 md:p-1 md:pl-2 border-l-4 border-red-600 bg-gray-200 md:bg-gray-100">
                <a href="{{ route('structural-survey') }}" class="uppercase">Structural survey</a>: maps showing the patterning of various features across the Algonquian family
            </li>
            <li class="mb-4 md:mb-0 p-2 md:p-1 md:pl-2 border-l-4 border-red-600 bg-gray-200 md:bg-gray-100">
                <a href="{{ route('verb-forms') }}" class="uppercase">Verb forms</a> and <a href="{{ route('nominal-forms') }}" class="uppercase">nominal forms</a> annotated with glosses, allomorphy, examples, cognates, and historical derivation
            </li>
            <li class="mb-4 md:mb-0 p-2 md:p-1 md:pl-2 border-l-4 border-red-600 bg-gray-200 md:bg-gray-100">
                <a href="{{ route('phonology') }}" class="uppercase">Phonology</a>: phoneme inventories, clusters, synchronic and diachronic rules, and sound changes
            </li>
            <li class="p-2 md:p-1 md:pl-2 border-l-4 border-red-600 bg-gray-200 md:bg-gray-100">
                <a href="{{ route('bibliography') }}" class="uppercase">Bibliography</a>: a filterable bibliography of Algonquian linguistics
            </li>
        </ul>

        <p class="mb-8 text-lg">
            To start using the database, click on any of the links above, or try doing a <a href="{{ route('search.verbs.paradigm') }}">verb paradigm search</a>, or click a language on the map below.</p>
        </p>

        <alglang-map
            style="height: 30rem;"
            :locations="{{ $languages }}"
        />
    </section>
@endsection
