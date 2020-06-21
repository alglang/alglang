<template>
    <div>
        <h2>Group details</h2>
        <h1>{{ group.name }}</h1>
        <p>{{ group.description }}</p>
    </div>
</template>

<script>
export default {
    props: {
        'group': {
            required: true
        }
    }
};
</script>
