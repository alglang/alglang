<template>
    <section class="bg-white p-6">
        <h1>{{ language.name }} ({{ language.algo_code }})</h1>
        <h2>A {{ language.group.name }} language</h2>

        <alglang-map
            style="height: 30rem;"
            :api-key="gmapsApiKey"
            :locations="[ language ]"
        />
    </section>
</template>

<script>
export default {
    props: {
        language: {
            required: true
        },
        gmapsApiKey: {
            required: true
        }
    }
};
</script>
