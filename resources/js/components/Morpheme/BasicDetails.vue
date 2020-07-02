<template>
    <div>
        <alglang-detail-row label="Gloss">
            <ul class="inline">
                <li v-for="gloss of value.glosses" :key="gloss.abv" class="inline-block">
                    <a v-if="gloss.url" class="inline-block" :href="gloss.url">
                        {{ gloss.abv }}
                    </a>
                    <span v-else class="inline-block">
                        {{ gloss.abv }}
                    </span>
                </li>
            </ul>

            <p v-if="glossText" class="inline ml-2">
                ({{ glossText }})
            </p>
        </alglang-detail-row>

        <alglang-detail-row label="Slot">
            <a :href="value.slot.url">
                {{ value.slot.abv }}
            </a>
        </alglang-detail-row>

        <alglang-detail-row v-if="value.description" label="Description">
            <p>
                {{ value.description }}
            </p>
        </alglang-detail-row>
    </div>
</template>

<script>
import DetailRow from '../DetailRow';

export default {
    props: {
        value: {
            required: true
        }
    },

    components: {
        'alglang-detail-row': DetailRow
    },

    computed: {
        glossText() {
            return this.value.glosses.map(gloss => gloss.name || '').join(' ');
        }
    }
};
</script>

<style scoped>
li:not(:last-child):after {
    display: inline-block;
    content: '.'
}
</style>
