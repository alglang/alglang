<template>
  <alglang-details v-model="morphSlot" :pages="pages" title="Slot details">
    <template v-slot:header>
      <h1 class="text-3xl font-light" :style="{ color: morphSlot.colour }">
        {{ morphSlot.abv }}
      </h1>
    </template>
  </alglang-details>
</template>

<script>
import Details from './Details';
import BasicDetails from './Slot/BasicDetails';

export default {
  props: {
    morphSlot: {
      required: true
    }
  },

  components: {
    'alglang-details': Details
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
