<template>
    <div>
        <h2>Group details</h2>
        <h1>{{ group.name }}</h1>
        <p>{{ group.description }}</p>

        <alglang-map :style="{ height: '30rem' }" :api-key="apiKey" :locations="group.languages" />
    </div>
</template>

<script>
export default {
    props: {
        'group': {
            required: true
        },
        'apiKey': {
            required: true
        }
    }
};
</script>
