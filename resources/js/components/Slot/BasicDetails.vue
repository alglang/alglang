<template>
  <div>
    <alglang-detail-row label="Full name">
      <p>
        {{ value.name }}
      </p>
    </alglang-detail-row>

    <alglang-detail-row
      v-if="value.description"
      label="Description"
    >
      <div v-html="value.description" />
    </alglang-detail-row>
  </div>
</template>

<script>
import DetailRow from '../DetailRow';

export default {
  components: {
    'alglang-detail-row': DetailRow
  },

  props: {
    value: {
      type: Object,
      required: true
    }
  }
};
</script>
