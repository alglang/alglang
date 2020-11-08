<div>
    <h2 class="py-2 text-lg">
        Vowel inventory
    </h2>
    <x-phoneme-table :items="$model->vowels" col-key="features.backness" row-key="features.height" />

    <h2 class="mt-4 py-2 text-lg">
        Consonant inventory
    </h2>
    <x-phoneme-table :items="$model->consonants" col-key="features.place" row-key="features.manner" />
</div>
