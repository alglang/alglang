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
      v-else-if="examples.length"
      class=""
    >
      <li
        v-for="example of examples"
        :key="example.id"
        class="mr-4"
      >
        <alglang-example-card :example="example" />
      </li>
    </ul>
    <p v-else>
      No morphemes
    </p>
  </div>
</template>

<script>
import axios from 'axios';

import ExampleCard from './ExampleCard';
import Loader from './Loader';

export default {
  components: {
    'alglang-example-card': ExampleCard,
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
      examples: []
    };
  },

  async created() {
    const response = await axios.get(this.url);
    const json = response.data;
    this.examples = json.data;
    this.loading = false;
  }
};
</script>
