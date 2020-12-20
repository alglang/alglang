<div>
    @if ($vowels->where('is_archiphoneme', false)->count() > 0)
        <h2 class="py-2 text-lg">
            Vowel inventory
        </h2>
        <x-phoneme-table
            class="vowel-inventory"
            :items="$vowels->where('is_archiphoneme', false)"
            col-key="features.backness"
            row-key="features.height"
        />
    @endif

    @if ($consonants->where('is_archiphoneme', false)->count() > 0)
        <h2 class="mt-4 py-2 text-lg">
            Consonant inventory
        </h2>
        <x-phoneme-table
            class="consonant-inventory"
            :items="$consonants->where('is_archiphoneme', false)"
            col-key="features.place"
            row-key="features.manner"
        />
    @endif

    @if ($phonemes->contains('is_archiphoneme', true))
        <h2 class="mt-4 py-2 text-lg">
            Archiphonemes
        </h2>

        <table>
            <tbody class="bg-gray-100">
                @foreach ($phonemes->where('is_archiphoneme', true) as $archiphoneme)
                    <tr>
                        <td class="bg-gray-700 uppercase text-gray-100 text-sm tracking-wide px-3 py-2 font-normal">
                            {{ $archiphoneme->features->place_name }}
                            {{ $archiphoneme->features->manner_name }}
                            {{ $archiphoneme->features->height_name }}
                            {{ $archiphoneme->features->backness_name }}
                        </td>

                        <td>
                            <x-phoneme-cell-single :phoneme="$archiphoneme" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($model->code !== 'PA')
        @if ($vowels->some(fn ($phoneme) => !$phoneme->is_archiphoneme && !$phoneme->parentsFromLanguage('PA')->isEmpty()))
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

        @if ($phonoids->some(fn ($phoneme) => !$phoneme->is_archiphoneme && !$phoneme->parentsFromLanguage('PA')->isEmpty()))
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

        @if ($phonoids->some(fn ($phoneme) => $phoneme->parentsFromLanguage('PA')->some(fn ($parent) => $parent->is_archiphoneme)))
            <h2 class="mt-4 py-2 text-lg">
                Reflexes of Proto-Algonquian archiphonemes
            </h2>

            <table>
                <tbody class="bg-gray-100">
                    @foreach (\App\Models\Language::find('PA')->phonemes->where('is_archiphoneme', true) as $archiphoneme)
                        <tr>
                            <td class="bg-gray-700 uppercase text-gray-100 text-sm tracking-wide px-3 py-2 font-normal">
                                {{ $archiphoneme->features->place_name }}
                                {{ $archiphoneme->features->manner_name }}
                                {{ $archiphoneme->features->height_name }}
                                {{ $archiphoneme->features->backness_name }}
                            </td>

                            <td>
                                <x-phoneme-cell-relation :phoneme="$archiphoneme" :language="$model->code" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif
</div>
