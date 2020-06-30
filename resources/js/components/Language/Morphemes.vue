<template>
    <div>
        <p v-if="loading">Loading...</p>
        <ul v-else>
            <li v-for="morpheme of morphemes" :key="morpheme.stem">
                <a :href="morpheme.url">
                    {{ morpheme.stem }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        value: {
            required: true
        }
    },

    data() {
        return {
            loading: true,
            morphemes: []
        };
    },

    async created() {
        const response = await axios.get(`/languages/tl/morphemes`);
        const json = response.data;
        this.morphemes = json.data;
        this.loading = false;
    }
};
</script>
