<template>
    <div>
        <div aria-labelledby="language-algo-code-label">
            <span id="language-algo-code-label">
                Algonquianist code
            </span>
            <div>
                {{ value.algo_code }}
            </div>
        </div>

        <div aria-labelledby="language-group-label">
            <span id="language-group-label">
                Group
            </span>
            <div>
                <a :href="value.group.url">
                    {{ value.group.name }}
                </a>
            </div>
        </div>

        <div v-if="value.children && value.children.length" aria-labelledby="language-direct-children-label">
            <span id="language-direct-children-label">
                Direct children
            </span>
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
