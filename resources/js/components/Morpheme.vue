<template>
  <alglang-details
    v-model="morpheme"
    :pages="pages"
    title="Morpheme details"
  >
    <template v-slot:header>
      <h1 class="text-3xl font-light">
        <span>{{ morpheme.shape }}</span><!--
     --><span class="text-base align-super">{{ morpheme.disambiguator + 1 }}</span>
      </h1>
      <p class="mb-2 px-2 py-1 inline text-sm uppercase leading-none bg-gray-300 rounded">
        <a :href="morpheme.language.url">{{ morpheme.language.name }}</a>
      </p>
    </template>
  </alglang-details>
</template>

<script>
import Details from './Details';
import BasicDetails from './Morpheme/BasicDetails';

export default {
  components: {
    'alglang-details': Details
  },

  props: {
    morpheme: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      pages: [{
        name: 'basic-details',
        component: BasicDetails
      }]
    };
  }
};
</script>

<style scoped>
.align-super {
  vertical-align: super;
}
</style>
