@extends('layouts.search-results')

@section('content')
    <section class="bg-white p-6 w-fit m-auto">
        <table>
            <thead class="bg-gray-700 text-gray-300 text-sm tracking-wide uppercase">
                <tr>
                    <th class="px-3 py-2 font-medium">
                        Metaparadigm
                    </th>
                    <th class="px-3 py-2 font-medium">
                        Paradigm
                    </th>
                    <th class="px-3 py-2 font-medium">
                        Features
                    </th>
                        @foreach($results->pluck('language.name')->unique() as $languageName)
                        <th class="px-3 py-2 font-medium">
                            {{ $languageName }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($results->groupBy('structure.paradigm.type.meta_type') as $metatype => $resultsByMetaType)
                    @foreach($resultsByMetaType->groupBy('structure.paradigm.name') as $paradigm => $resultsByParadigm)
                        @foreach($resultsByParadigm->groupBy('structure.feature_string') as $features => $resultsByFeatures)
                            <tr class="odd:bg-gray-100 even:bg-gray-200">
                                @if($loop->first && $loop->parent->first)
                                    <td class="px-3 py-3 {{ $loop->parent->parent->even ? 'bg-gray-400' : 'bg-gray-300' }} text-gray-700 text-sm tracking-wide uppercase font-semibold" rowspan="{{ $resultsByMetaType->count() }}">
                                        {{ Str::plural($metatype) }}
                                    </td>
                                @endif

                                @if($loop->first)
                                    <td class="px-3 py-2 {{ $loop->parent->parent->even ? 'bg-gray-400' : 'bg-gray-300' }} text-gray-700 text-sm tracking-wide uppercase font-semibold" rowspan="{{ $resultsByParadigm->count() }}">
                                        {{ $paradigm }}
                                    </td>
                                @endif

                                <td class="px-3 py-2">
                                    {{ $features }}
                                </td>
                                @foreach($resultsByFeatures->groupBy('language_code') as $results)
                                    <td class="px-3 py-2">
                                        <ul>
                                            @foreach($results as $result)
                                                <li>
                                                    <x-preview-link :model="$result" class="text-gray-800">
                                                        {{ $result->shape }}
                                                    </x-preview-link>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
