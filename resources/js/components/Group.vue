<template>
    <alglang-details title="Group details" :pages="pages" v-model="group">
        <template v-slot:name>
            {{ group.name }} languages
        </template>
    </alglang-details>
</template>

<script>
import Details from './Details';
import Map from './Map';
import BasicDetails from './Group/BasicDetails.vue';

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
                {
                    name: 'basic-details',
                    component: BasicDetails
                }
            ]
        };
    },

    created() {
        this.$store.commit({
            type: 'setGmapsApiKey',
            key: this.gmapsApiKey
        });
    }
};
</script>
