<template>
  <alglang-details
    v-model="gloss"
    :pages="pages"
    title="Gloss details"
  >
    <template v-slot:header>
      <h1 class="text-3xl font-light">
        {{ gloss.abv }}
      </h1>
    </template>
  </alglang-details>
</template>

<script>
import Details from './Details';
import BasicDetails from './Gloss/BasicDetails';

export default {
  components: {
    'alglang-details': Details
  },

  props: {
    gloss: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      pages: [
        {
          name: 'basic-details',
          component: BasicDetails
        }
      ]
    };
  }
};
</script>
