<div>
    @if ($model->vowels->count() > 0)
        <h2 class="py-2 text-lg">
            Vowel inventory
        </h2>
        <x-phoneme-table
            class="vowel-inventory"
            :items="$model->vowels->where('is_archiphoneme', false)"
            col-key="features.backness"
            row-key="features.height"
        />
    @endif

    @if ($model->consonants->count() > 0)
        <h2 class="mt-4 py-2 text-lg">
            Consonant inventory
        </h2>
        <x-phoneme-table
            class="consonant-inventory"
            :items="$model->consonants->where('is_archiphoneme', false)"
            col-key="features.place"
            row-key="features.manner"
        />
    @endif

    @if ($model->phonemes->contains('is_archiphoneme', true))
        <h2 class="mt-4 py-2 text-lg">
            Archiphonemes
        </h2>

        <table>
            <tbody class="bg-gray-100">
                @foreach ($model->phonemes->where('is_archiphoneme', true) as $archiphoneme)
                    <tr>
                        <td class="bg-gray-700 uppercase text-gray-100 text-sm tracking-wide px-3 py-2 font-normal">
                            {{ $archiphoneme->features->place_name }}
                            {{ $archiphoneme->features->manner_name }}
                            {{ $archiphoneme->features->height_name }}
                            {{ $archiphoneme->features->backness_name }}
                        </td>
                        <td class="p-2">
                            <x-preview-link :model="$archiphoneme">
                                {!! $archiphoneme->formatted_shape !!}
                            </x-preview-link>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($model->code !== 'PA')
        @if ($model->vowels->some(fn ($phoneme) => !$phoneme->parentsFromLanguage('PA')->isEmpty()))
            <h2 class="mt-4 py-2 text-lg">
                Reflexes of Proto-Algonquian vowels
            </h2>

            <x-phoneme-table
                class="vowel-reflexes"
                :items="\App\Models\Language::find('PA')->vowels->where('is_archiphoneme', false)"
                col-key="features.backness"
                row-key="features.height"
                cell-component="phoneme-cell-relation"
                :cell-component-props="['language' => $model->code]"
            />
        @endif

        @if (
            $model->consonants->some(fn ($phoneme) => !$phoneme->parentsFromLanguage('PA')->isEmpty()) ||
            $model->clusters->some(fn ($phoneme) => !$phoneme->parentsFromLanguage('PA')->isEmpty())
        )
            <h2 class="mt-4 py-2 text-lg">
                Reflexes of Proto-Algonquian consonants
            </h2>

            <x-phoneme-table
                class="consonant-reflexes"
                :items="\App\Models\Language::find('PA')->consonants->where('is_archiphoneme', false)"
                col-key="features.place"
                row-key="features.manner"
                cell-component="phoneme-cell-relation"
                :cell-component-props="['language' => $model->code]"
            />
        @endif
    @endif
</div>
