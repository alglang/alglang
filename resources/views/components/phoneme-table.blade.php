<table>
    <thead>
        <tr class="bg-gray-700 uppercase text-gray-100 text-sm tracking-wide">
            <td></td>
            @foreach ($colHeaders as $colHeader)
                <th class="px-3 py-2 font-normal">
                    {{ $colHeader->name }}
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody class="bg-gray-100">
        @foreach ($rowHeaders as $rowHeader)
            <tr>
                <th class="bg-gray-700 uppercase text-gray-100 text-sm tracking-wide px-3 py-2 font-normal">
                    {{ $rowHeader->name }}
                </th>

                @foreach ($colHeaders as $colHeader)
                    @php
                        $filteredItems = $filterItems($rowHeader, $colHeader);
                    @endphp

                    @if ($filteredItems->count() > 0)
                        <td
                            data-{{ $colName }}="{{ $colHeader->name }}"
                            data-{{ $rowName }}="{{ $rowHeader->name }}"
                            class="p-2"
                        >
                            <div class="flex justify-around">
                                @foreach ($filteredItems as $item)
                                    <div>
                                        <x-preview-link :model="$item">
                                            {!! $item->formatted_shape !!}
                                        </x-preview-link>
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
