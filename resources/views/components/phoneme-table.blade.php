<table {{ $attributes }}>
    <thead>
        <tr
            class="bg-gray-700 text-gray-100
                   {{ $uppercase ? 'uppercase text-sm tracking-wide' : '' }}
                   "
        >
            <td></td>
            @foreach ($colHeaders as $colHeader)
                <th class="px-3 py-2 font-normal">
                    {{ $colHeader->$colAccessor }}
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody class="bg-gray-100">
        @foreach ($rowHeaders as $rowHeader)
            <tr>
                <th class="bg-gray-700 text-gray-100 px-3 py-2 font-normal
                          {{ $uppercase ? 'uppercase text-sm tracking-wide' : '' }}
                          "
                >
                    {{ $rowHeader->$rowAccessor }}
                </th>

                @foreach ($colHeaders as $colHeader)
                    @php
                        $filteredItems = $filterItems($rowHeader, $colHeader);
                    @endphp

                    @if ($filteredItems->count() > 0)
                        <td
                            data-{{ $colName }}="{{ $colHeader->$colAccessor }}"
                            data-{{ $rowName }}="{{ $rowHeader->$rowAccessor }}"
                            class="p-2"
                        >
                            <div class="flex items-center flex-col">
                                @foreach ($filteredItems as $item)
                                    <div>
                                        @component("components.$cellComponent", array_merge([
                                            'phoneme' => $item
                                        ], $cellComponentProps))
                                        @endcomponent
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    @else
                        <td class="p-2"></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
