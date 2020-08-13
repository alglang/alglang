
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
      v-else-if="nominalForms.length"
      class="flex flex-wrap justify-center md:justify-start"
    >
      <li
        v-for="nominalForm in nominalForms"
        :key="nominalForm.shape"
        class="mr-4"
      >
        <alglang-nominal-form-card :nominal-form="nominalForm" />
      </li>
    </ul>

    <p v-else>
      No nominal forms
    </p>
  </div>
</template>

<script>
import axios from 'axios';

import Loader from './Loader';
import NominalFormCard from './NominalFormCard';

export default {
  components: {
    'alglang-loader': Loader,
    'alglang-nominal-form-card': NominalFormCard
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
      nominalForms: []
    };
  },

  async created() {
    const response = await axios.get(this.url);
    const json = response.data;
    this.nominalForms = json.data;
    this.loading = false;
  }
};
</script>
