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
                    <td
                        data-{{ $colName }}="{{ $colHeader->name }}"
                        data-{{ $rowName }}="{{ $rowHeader->name }}"
                        class="p-2"
                    >
                        <div class="flex justify-around">
                            @foreach ($filterItems($rowHeader, $colHeader) as $item)
                                <div>
                                    <x-preview-link :model="$item">
                                        {!! $item->formatted_shape !!}
                                    </x-preview-link>
                                </div>
                            @endforeach
                        </div>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
