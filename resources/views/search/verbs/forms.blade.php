@extends('layouts.app')

@section('content')
    <section class="bg-white p-6 w-fit m-auto">

        <table>
            <thead class="bg-gray-700 text-gray-300 uppercase text-xs">
                <tr>
                    <th class="px-3 py-2 font-semibold tracking-wide">
                        Language
                    </th>
                    @foreach($structureResults as $results)
                        <td></td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($languages as $language)
                    <tr class="odd:bg-gray-100 even:bg-gray-200">
                        <td class="px-3 py-2 {{ $loop->even ? 'bg-gray-400' : 'bg-gray-300' }} text-gray-700 text-sm tracking-wide uppercase font-semibold">
                            {{ $language->name }}
                        </td>
                        @foreach($structureResults as $results)
                            <td class="px-3 py-2">
                                <ul>
                                    @foreach($results->where('language_id', $language->id) as $result)
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
            </tbody>
        </table>

    </section>
@endsection
