<template>
    <div>
        <p>{{ value.description }}</p>
        <alglang-map :style="{ height: '30rem' }" :locations="value.languages" />
    </div>
</template>

<script>
import Map from '../Map';

export default {
    components: {
        'alglang-map': Map
    },

    props: ['value'],

    data() {
        return {
            gmapsApiKey: null
        }
    }
};
</script>
