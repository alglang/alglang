<template>
  <div>
    <p v-if="loading">Loading...</p>
    <ul v-else>
      <li v-for="morpheme of morphemes" :key="morpheme.shape">
        <a :href="morpheme.url">
          {{ morpheme.shape }}
        </a>

        <span>
          (<a :href="morpheme.slot.url">{{ morpheme.slot.abv }}</a>)
        </span>

        <ul>
          <li v-for="gloss in morpheme.glosses" :key="gloss.abv">
            <a v-if="gloss.url" :href="gloss.url">
              {{ gloss.abv }}
            </a>
            <span v-else>
              {{ gloss.abv }}
            </span>
          </li>
        </ul>
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
    const response = await axios.get(`${this.value.url}/morphemes`);
    const json = response.data;
    this.morphemes = json.data;
    this.loading = false;
  }
};
</script>
