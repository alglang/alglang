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

        <alglang-map :style="{ height: '30rem' }" :locations="value.languages" />
    </div>
</template>

<script>
import Map from '../Map';

export default {
    components: {
        'alglang-map': Map
    },

    props: ['value']
};
</script>
