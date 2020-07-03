<template>
  <a
    :href="morpheme.url"
    class="block mb-4 p-3 bg-yellow-100 hover:bg-yellow-200 text-center shadow"
    style="width: fit-content; min-width: 12rem;"
  >
    <span class="text-xs font-semibold">
      <a :href="morpheme.slot.url">{{ morpheme.slot.abv }}</a>
    </span>

    <p class="text-xl py-2 text-gray-900 hover:text-gray-900">
      <span>{{ morpheme.shape }}</span><!--
   --><span class="text-xs align-super">{{ morpheme.disambiguator + 1 }}</span>
    </p>

    <alglang-gloss-field
      class="text-sm text-gray-900 hover:text-gray-900"
      v-model="morpheme.glosses"
    />
  </a>
</template>

<script>
import GlossField from './GlossField';

export default {
  props: {
    morpheme: {
      required: true
    }
  },

  components: {
    'alglang-gloss-field': GlossField
  }
};
</script>

<style scoped>
.align-super {
  vertical-align: super;
}
</style>
