<template>
    <div>
        <div>
            <label>
                Algonquianist code
            </label>
            <div>
                {{ value.algo_code }}
            </div>
        </div>

        <div>
            <label>
                Group
            </label>
            <div>
                <a :href="value.group.url">
                    {{ value.group.name }}
                </a>
            </div>
        </div>

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
