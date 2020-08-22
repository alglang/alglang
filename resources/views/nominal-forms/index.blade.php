@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 leading-relaxed m-auto w-fit">
        <h1 class="text-2xl mb-4 text-center md:text-left">
            Nominal forms
        </h1>

        <p class="mb-4">
            Nominal forms include the forms of nouns, pronouns, and demonstratives. You can view nominal forms in two ways:
        </p>

        <ul class="list-square ml-8 list-inner">
            <li class="mb-4">
                You can <a href="#">navigate to a particular language</a> and then browse a list of its nominal paradigms and nominal forms.

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="particular-language-example">
                    <h2 class="font-semibold inline" id="particular-language-example">
                        Example:
                    </h2>
                    <p class="inline">
                        the <a href="#">nominal paradigms</a> and <a href="#">nominal forms</a> of Proto-Algonquian.
                    </p>
                </aside>
            </li>

            <li class="mb-4">
                You can <a href="#">search for a particular nominal paradigm</a> in one or more languages.

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="particular-language-example">
                    <h2 class="font-semibold inline" id="particular-language-example">
                        Example:
                    </h2>
                    <p class="inline">
                        the <a href="#">personal pronouns</a> of Shawnee, Atikamekw, and Munsee.
                    </p>
                </aside>
            </li>
        </ul>

        <p>
            More information about the presentation and analysis of nominal forms will be added here later.
        </p>
    </section>
@endsection
