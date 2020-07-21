<template>
  <div>
    <div
      v-if="loading"
      aria-label="Loading"
    >
      <alglang-loader class="w-16 h-16" />
    </div>

    <ul v-else-if="verbForms.length">
      <li
        v-for="verbForm in verbForms"
        :key="verbForm.shape"
      >
        <alglang-verb-form-card :verb-form="verbForm" />
      </li>
    </ul>

    <p v-else>
      No verb forms
    </p>
  </div>
</template>

<script>
import axios from 'axios';

import Loader from '../Loader';
import VerbFormCard from '../VerbFormCard';

export default {
  components: {
    'alglang-loader': Loader,
    'alglang-verb-form-card': VerbFormCard
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
      verbForms: []
    };
  },

  async created() {
    const response = await axios.get(this.url);
    const json = response.data;
    this.verbForms = json.data;
    this.loading = false;
  }
};
</script>
