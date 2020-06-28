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

        <div v-if="value.children && value.children.length">
            <label>
                Direct children
            </label>
            <div>
                <ul>
                    <li v-for="child of value.children" :key="child.name">
                        <a :href="child.url">
                            {{ child.name }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div v-if="value.position" aria-labelledby="language-location-label"> 
            <span id="language-location-label">
                Location
            </span>
            <alglang-map :style="{ height: '30rem' }" :locations="value.languages" />
        </div>
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
