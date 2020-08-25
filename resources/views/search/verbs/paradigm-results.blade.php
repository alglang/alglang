@extends('layouts.search-results')

@php
    $headerInfo = $results->map(fn ($result) => [
        'language' => $result->language->name,
        'mode' => $result->structure->mode->name,
        'order' => $result->structure->order->name
    ])->unique()->groupBy(['language', 'order']);
@endphp

@section('content')
    <section class="bg-white p-6 w-fit m-auto">
        <table>
            <thead class="bg-gray-700 text-gray-300 text-sm tracking-wide uppercase">
                <tr>
                    <th></th>
                    <th></th>
                    @foreach($headerInfo as $language => $headerInfoByLanguage)
                        <th class="px-3 py-2 font-medium {{ $loop->odd ? 'bg-gray-800' : '' }}" colspan="{{ $headerInfoByLanguage->flatten(1)->count() }}">
                            {{ $language }}
                        </th>
                    @endforeach
                </tr>
                    <th></th>
                    <th></th>
                    @foreach($headerInfo as $headerInfoByLanguage)
                        @foreach($headerInfoByLanguage as $order => $headerInfoByOrder)
                            <th class="px-3 py-2 font-medium {{ $loop->parent->odd ? 'bg-gray-800' : '' }}" colspan="{{ $headerInfoByOrder->count() }}">
                                {{ $order }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>

                <tr>

                <tr>
                    <th class="px-3 py-2 font-medium">
                        Class
                    </th>
                    <th class="px-3 py-2 font-medium">
                        Features
                    </th>
                    @foreach($headerInfo as $language => $headerInfoByLanguage)
                        @foreach($headerInfoByLanguage as $order => $headerInfoByOrder)
                            @foreach($headerInfoByOrder as $header)
                                <th class="px-3 py-2 font-medium {{ $loop->parent->parent->odd ? 'bg-gray-800' : '' }}">
                                    {{ $header['mode'] }}
                                </th>
                            @endforeach
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($results->groupBy('structure.class_abv') as $classAbv => $resultsByClass)
                    @foreach($resultsByClass->groupBy('structure.feature_string') as $featureString => $resultsByFeatures)
                        <tr class="odd:bg-gray-100 even:bg-gray-200">
                            @if($loop->first)
                                <td class="px-3 py-2 {{ $loop->parent->even ? 'bg-gray-400' : 'bg-gray-300' }} text-gray-700 text-sm tracking-wide uppercase font-semibold" rowspan="{{ $resultsByClass->count() }}">
                                    {{ $classAbv }}
                                </td>
                            @endif

                            <td class="px-3 py-2 {{ $loop->parent->even ? 'bg-gray-400' : 'bg-gray-300' }} text-gray-700 text-sm tracking-wide font-semibold">
                                {{ $featureString }}
                            </td>

                            @foreach($headerInfo->flatten(2) as $header)
                                <td class="px-3 py-2">
                                    <ul>
                                        @foreach($resultsByFeatures->where('language.name', $header['language'])->where('structure.order.name', $header['order'])->where('structure.mode.name', $header['mode']) as $result)
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
            </tbody>
        </table>
    </section>
@endsection
