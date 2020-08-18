@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 leading-relaxed m-auto w-fit">
        <h1 class="text-2xl mb-4 text-center md:text-left">
            Phonology
        </h1>

        <p class="mb-4">
            You can view information about the synchronic and diachronic phonology of a language by <a href="#">navigating to the language</a> and choosing one of the following links in the side menu:
        </p>

        <ul class="list-square ml-8 list-inner">
            <li class="mb-4">
                <b>Phonemes</b> to view the synchronic vowel and consonant inventories as well as the diachronic correspondences with Proto-Algonquian.

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="phonemes-example">
                    <h2 class="font-semibold inline" id="phonemes-example">
                        Example:
                    </h2>
                    <p class="inline">
                        <a href="#">Plains Cree phonemes</a>
                    </p>
                </aside>
            </li>

            <li class="mb-4">
                <b>Clusters</b> to view the set of primary consonant clusters in the language (i.e. the synchronic clusters that reflect Proto-Algonquian clusters) and their correspondences with the clusters of Proto-Algonquian.

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="clusters-example">
                    <h2 class="font-semibold inline" id="clusters-example">
                        Example:
                    </h2>
                    <p class="inline">
                        <a href="#">Plains Cree clusters</a>
                    </p>
                </aside>
            </li>

            <li class="mb-4">
                <b>Rules</b> to view a list of synchronic and diachronic phonological rules. (We're still working on refining the rules component.)

                <aside class="bg-yellow-200 mt-2 p-3 w-fit" aria-labelledby="clusters-example">
                    <h2 class="font-semibold inline" id="clusters-example">
                        Example:
                    </h2>
                    <p class="inline">
                        <a href="#">Plains Cree rules</a>
                    </p>
                </aside>
            </li>
        </ul>
    </section>
@endsection
