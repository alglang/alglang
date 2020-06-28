<template>
    <div>
        <alglang-map :style="{ height: '30rem' }" :locations="value.languages" />
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
    }
};
</script>
