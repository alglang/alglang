<template>
  <a
    :href="morpheme.url"
    class="block mb-4 p-3 bg-yellow-100 hover:bg-yellow-200 text-center shadow"
    style="width: fit-content; min-width: 12rem;"
  >
    <span class="text-xs font-semibold">
      <a
        :href="morpheme.slot.url"
        :style="{ color: morpheme.slot.colour }"
      >
        {{ morpheme.slot.abv }}
      </a>
    </span>

    <p class="text-xl py-2 text-gray-900 hover:text-gray-900">
      <span v-html="morpheme.formatted_shape" /><!--
   --><span class="text-xs align-super">{{ morpheme.disambiguator + 1 }}</span>
    </p>

    <alglang-gloss-field
      :value="morpheme.glosses"
      class="text-sm text-gray-900 hover:text-gray-900"
      @input="$emit('input', $event)"
    />
  </a>
</template>

<script>
import GlossField from './GlossField';

export default {
  components: {
    'alglang-gloss-field': GlossField
  },

  props: {
    morpheme: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>
.align-super {
  vertical-align: super;
}
</style>
