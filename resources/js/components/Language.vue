<template>
    <alglang-details title="Language details" :pages="pages">
        <template v-slot:name>
            {{ language.name }}
        </template>

        <div>
            <label>
                Algonquianist code
            </label>
            <div>
                {{ language.algo_code }}
            </div>
        </div>

        <div>
            <label>
                Group
            </label>
            <div>
                {{ language.group.name }}
            </div>
        </div>

        <alglang-map
            style="height: 30rem;"
            :api-key="gmapsApiKey"
            :locations="[ language ]"
            />
    </alglang-details>
</template>

<script>
import Details from './Details.vue';

const pages = [
    { name: 'basic-details' },
    { name: 'datapoints' }
];

export default {
    components: {
        'alglang-details': Details
    },

    props: {
        language: {
            required: true
        },
        gmapsApiKey: {
            required: true
        }
    },

    data() {
        return {
            pages
        };
    }
};
</script>
