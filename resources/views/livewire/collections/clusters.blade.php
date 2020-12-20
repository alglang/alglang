<div>
    <h2 class="py-2 text-lg">
        Cluster inventory
    </h2>

    <x-phoneme-table
        :items="$clusters"
        col-key="features.secondSegment"
        row-key="features.firstSegment"
        col-accessor="shape"
        row-accessor="shape"
        :uppercase="false"
    />
</div>

@if ($model->code !== 'PA' &&
    (
        $consonants->some(fn ($phoneme) => !$phoneme->parentsFromLanguage('PA')->isEmpty()) ||
        $clusters->some(fn ($phoneme) => !$phoneme->parentsFromLanguage('PA')->isEmpty())
    )
)
    <h2 class="mt-4 py-2 text-lg">
        Reflexes of Proto-Algonquian clusters
    </h2>

    <x-phoneme-table
        class="consonant-reflexes"
        :items="$paClusters"
        col-key="features.secondSegment"
        row-key="features.firstSegment"
        col-accessor="shape"
        row-accessor="shape"
        cell-component="phoneme-cell-relation"
        :cell-component-props="['language' => $model->code]"
        :uppercase="false"
    />
@endif
