<div>
    <h2 class="py-2 text-lg">
        Cluster inventory
    </h2>

    <x-phoneme-table
        :items="$model->clusters"
        col-key="features.secondSegment"
        row-key="features.firstSegment"
        col-accessor="shape"
        row-accessor="shape"
        :uppercase="false"
    />
</div>
