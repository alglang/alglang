<template>
  <div>
    <p v-if="loading">
      Loading...
    </p>
    <ul v-else>
      <li
        v-for="morpheme of morphemes"
        :key="morpheme.shape"
      >
        <alglang-morpheme-card :morpheme="morpheme" />
      </li>
    </ul>
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
    value: {
      type: Object,
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
