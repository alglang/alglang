<template>
    <div>
        <alglang-map :style="{ height: '30rem' }" :api-key="gmapsApiKey" :locations="value.languages" />
    </div>
</template>

<script>
export default {
    name: 'basic-details',

    props: ['value'],

    data() {
        return {
            gmapsApiKey: null
        }
    },

    created() {
        this.gmapsApiKey = this.$store.state.gmapsApiKey;
    }
};
</script>
