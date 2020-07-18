<template>
  <div>
    <div
      v-if="loading"
      aria-label="Loading"
    >
      Loading...
    </div>
    <ul v-else-if="morphemes.length">
      <li
        v-for="morpheme of morphemes"
        :key="morpheme.shape"
      >
        <alglang-morpheme-card :morpheme="morpheme" />
      </li>
    </ul>
    <p v-else>
      No morphemes
    </p>
  </div>
</template>

<script>
import axios from 'axios';
import MorphemeCard from '../MorphemeCard';

export default {
  components: {
    'alglang-morpheme-card': MorphemeCard
  },

  props: {
    url: {
      type: String,
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
    const response = await axios.get(this.url);
    const json = response.data;
    this.morphemes = json.data;
    this.loading = false;
  }
};
</script>
