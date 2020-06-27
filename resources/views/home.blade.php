@extends('layouts.app')

@section('content')
    <section class="bg-white p-6">
        <h1 class="text-2xl mb-6">Database of Algonquian Language Structures</h1>

        <p class="mb-4">
            This database provides information about the sounds and grammar of the <a href="{{ route('groups.show', ['group' => 'algonquian']) }}">Algonquian languages</a>, focusing on the following areas:
        </p>

        <ul class="border-l-4 leading-relaxed pl-1 ml-2 mb-4 border-red-600">
            <li>
                <a href="{{ route('structural-survey') }}" class="uppercase">Structural survey</a>: maps showing the patterning of various features across the Algonquian family
            </li>
            <li>
                <a href="{{ route('verbs') }}" class="uppercase">Verb forms</a> and <a href="#" class="uppercase">nominal forms</a> annotated with glosses, allomorphy, examples, cognates, and historical derivation
            </li>
            <li>
                <a href="{{ route('nominals') }}" class="uppercase">Phonology</a>: phoneme inventories, clusters, synchronic and diachronic rules, and sound changes
            </li>
            <li>
                <a href="{{ route('bibliography') }}" class="uppercase">Bibliography</a>: a filterable bibliography of Algonquian linguistics
            </li>
        </ul>

        <p class="mb-8">
            To start using the database, click on any of the links above, or try doing a <a href="{{ route('search.verbs.paradigm') }}">verb paradigm search</a>, or click a language on the map below.</p>
        </p>

        <alglang-map
            style="height: 30rem;"
            api-key="{{ config('services.gmaps.key') }}"
            :locations="{{ $languages }}"
        />
    </section>
@endsection
