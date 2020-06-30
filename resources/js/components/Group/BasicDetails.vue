<template>
    <div>
        <alglang-detail-row v-if="value.description" label="Description">
            <p>{{ value.description }}</p>
        </alglang-detail-row>

        <alglang-detail-row v-if="positionedLanguages.length" label="Location">
            <alglang-map :style="{ height: '30rem' }" :locations="positionedLanguages" />
        </alglang-detail-row>
    </div>
</template>

<script>
import Map from '../Map';
import DetailRow from '../DetailRow';

export default {
    components: {
        'alglang-map': Map,
        'alglang-detail-row': DetailRow
    },

    props: ['value'],

    computed: {
        positionedLanguages() {
            return (this.value.languages || []).filter(language => language.position);
        }
    }
};
</script>
