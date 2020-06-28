<template>
    <alglang-details title="Language details" :pages="pages" v-model="language">
        <template v-slot:name>
            {{ language.name }}
        </template>


        <alglang-map
            style="height: 30rem;"
            :locations="[ language ]"
        />
    </alglang-details>
</template>

<script>
import Details from './Details.vue';
import BasicDetails from './Language/BasicDetails.vue';

export default {
    components: {
        'alglang-details': Details
    },

    props: {
        language: {
            required: true
        }
    },

    data() {
        return {
            pages: [
                {
                    name: 'basic-details',
                    component: BasicDetails
                }
            ]
        };
    }
};
</script>
