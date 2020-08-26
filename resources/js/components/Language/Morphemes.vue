<template>
  <div>
    <div
      v-if="loading"
      aria-label="Loading"
      class="flex justify-center"
    >
      <alglang-loader class="w-16 h-16" />
    </div>
    <ul
      v-else-if="morphemes.length"
      class="flex flex-wrap justify-center md:justify-start"
    >
      <li
        v-for="morpheme of morphemes"
        :key="morpheme.id"
        class="mr-4"
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
import Loader from '../Loader';

export default {
  components: {
    'alglang-loader': Loader,
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
