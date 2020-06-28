<template>
    <alglang-details title="Group details" :pages="pages" v-model="group">
        <template v-slot:name>
            {{ group.name }} languages
        </template>
    </alglang-details>
</template>

<script>
import Details from './Details';
import BasicDetails from './Group/BasicDetails.vue';

export default {
    components: {
        'alglang-details': Details
    },

    props: {
        group: {
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
