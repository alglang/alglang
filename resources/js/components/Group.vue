<template>
  <alglang-details
    v-model="group"
    :pages="pages"
    title="Group details"
  >
    <template v-slot:header>
      <h1 class="text-3xl font-light">
        {{ group.name }} languages
      </h1>
    </template>
  </alglang-details>
</template>

<script>
import Details from './Details';
import BasicDetails from './Group/BasicDetails';

export default {
  components: {
    'alglang-details': Details
  },

  props: {
    group: {
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
