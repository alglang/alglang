<template>
    <alglang-details title="Language details" :pages="pages" v-model="language">
        <template v-slot:header>
            <h1 class="text-3xl font-light">
                {{ language.name }}
            </h1>
            <p v-if="language.reconstructed" class="mb-2 p-1 inline text-sm leading-none bg-gray-300 rounded">
                Reconstructed
            </p>
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
import Morphemes from './Language/Morphemes';

export default {
    components: {
        'alglang-details': Details,
        'alglang-morphemes': Morphemes
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
                },
                {
                    name: 'morphemes',
                    component: Morphemes
                }
            ]
        };
    }
};
</script>
