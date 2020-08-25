@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 leading-relaxed m-auto w-fit">
        <h1 class="text-2xl mb-4 text-center md:text-left">
            Verb forms
        </h1>

        <p class="mb-4">
            The database contains hundreds of verb forms, which you can access in various ways:
        </p>

        <ul class="list-square ml-8 list-inner">
            <li class="mb-4">
                If you're interested in a <b>particular language</b>, <a href="{{ route('languages.index') }}">navigate to that language</a> and then browse a list of its verb paradigms and verb forms.

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="particular-language-example">
                    <h2 class="font-semibold inline" id="particular-language-example">
                        Example:
                    </h2>
                    <p class="inline">
                        the <a href="{{ route('languages.show', ['language' => 'Sh']) }}#verb-paradigms">verb paradigms</a> and <a href="{{ route('languages.show', ['language' => 'Sh']) }}#verb-forms">verb forms</a> of Shawnee.
                    </p>
                </aside>
            </li>

            <li class="mb-4">
                If you're looking for a <b>particular verb form</b> in one or more languages, <a href="{{ route('search.verbs.forms') }}">search for a verb form</a>.

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="particular-verb-form-example">
                    <h2 class="font-semibold inline" id="particular-verb-form-example">
                        Example:
                    </h2>
                    <p class="inline">
                        the <a href="{{ route('search.verbs.form-results', [
                            'structures' => [
                                [
                                    'subject_persons' => ['1'],
                                    'subject_numbers' => [3],
                                    'classes' => ['AI'],
                                    'orders' => ['Conjunct'],
                                    'modes' => ['Indicative'],
                                    'primary_object' => false,
                                    'secondary_object' => false,
                                    'negative' => false,
                                    'diminutive' => false
                                ]
                            ]
                        ]) }}" target="_blank">1p AI Conjunct Indicative form</a> in all available languages.
                    </p>
                </aside>
            </li>

            <li class="mb-4">
                If you're looking for a <b>particular verb paradigm</b> in one or more languages, such as the AI Conjunct, <a href="{{ route('search.verbs.paradigms') }}">search for a verb paradigm</a>. Basic and advanced search options are available.

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="particular-verb-paradigm-example">
                    <h2 class="font-semibold inline" id="particular-verb-paradigm-example">
                        Example:
                    </h2>
                    <p class="inline">
                        the complete <a href="{{ route('search.verbs.paradigm-results', [
                            'languages' => ['Sh', 'Mes'],
                            'classes' => ['AI'],
                            'orders' => ['Conjunct'],
                            'modes' => ['Indicative'],
                        ]) }}" target="_blank">AI Conjunct Indicative paradigm</a> in Shawnee and Meskwaki.
                    </p>
                </aside>
            </li>
        </ul>

        <p>
            More information about how verb forms are classified and presented will be added here later.
        </p>
@endsection
