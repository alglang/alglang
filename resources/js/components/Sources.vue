<template>
  <div>
    <div
      v-if="loading"
      aria-label="Loading"
      class="flex justify-center"
    >
      <alglang-loader class="w-16 h-16" />
    </div>

    <ul>
      <li
        v-for="source in sources"
        :key="source.id"
      >
        <a :href="source.url">
          {{ source.short_citation }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

import Loader from './Loader';

export default {
  components: {
    'alglang-loader': Loader
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
      sources: []
    };
  },

  async created() {
    const response = await axios.get(this.url);
    const json = response.data;
    this.sources = json.data;
    this.loading = false;
  }
};
</script>
