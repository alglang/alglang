@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 w-fit m-auto">

        <table>
            <thead class="bg-gray-700 text-gray-300 text-sm tracking-wide">
                <tr>
                    <th class="px-3 py-2 font-medium">
                        Language
                    </th>
                    @foreach($columns as $column)
                        <th class="px-3 py-2 font-medium">
                            {{ $column['query']->feature_string }}
                            {{ $column['query']->class_abv }}
                            {{ $column['query']->order_name }}
                            {{ $column['query']->mode_name }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($languages as $language)
                    <tr class="odd:bg-gray-100 even:bg-gray-200">
                        <td class="px-3 py-2 {{ $loop->even ? 'bg-gray-400' : 'bg-gray-300' }} text-gray-700 text-sm tracking-wide uppercase font-semibold">
                            {{ $language->name }}
                        </td>
                        @foreach($columns as $column)
                            <td class="px-3 py-2">
                                <ul>
                                    @foreach($column['results']->where('language_id', $language->id) as $result)
                                        <li>
                                            <x-preview-link :model="$result" class="text-gray-800">
                                                {{ $result->shape }}
                                            </x-preview-link>

                                            @if(!$column['query']->matchesStructure($result->structure))
                                                <span class="ml-2 bg-yellow-200">
                                                    ({{ $result->structure->feature_string }})
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    </section>
@endsection
