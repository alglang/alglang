<template>
    <alglang-details title="Group details" :pages="pages">
        <template v-slot:name>
            {{ group.name }} languages
        </template>

        <p>{{ group.description }}</p>

        <alglang-map :style="{ height: '30rem' }" :api-key="gmapsApiKey" :locations="group.languages" />
    </alglang-details>
</template>

<script>
import Details from './Details';
import Map from './Map';

export default {
    components: {
        'alglang-details': Details,
        'alglang-map': Map
    },

    props: {
        group: {
            required: true
        },
        gmapsApiKey: {
            required: true
        }
    },

    data() {
        return {
            pages: [
                { name: 'basic-details' }
            ]
        };
    }
};
</script>
