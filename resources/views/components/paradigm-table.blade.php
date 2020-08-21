<div>
    <table>
        <thead class="bg-gray-800 text-gray-300 uppercase font-medium tracking-wider text-xs">
            <tr>
                <th class="px-4 py-2">Features</th>
                <th class="px-4 py-2">Form</th>
            </tr>
        </thead>
        <tbody>
            @foreach($forms as $form)
                <tr class="odd:bg-gray-100 even:bg-gray-200">
                    <td class="px-4 py-3">
                        {{ $form->structure->feature_string }}
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <x-preview-link :model="$form">
                                {{ $form->shape }}
                            </x-preview-link>
                        </div>

                        @if ($form->morphemes->count() > 0)
                            <div class="mt-3">
                                <x-morpheme-table :morphemes="$form->morphemes" />
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
