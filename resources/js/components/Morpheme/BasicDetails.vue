<template>
  <div>
    <alglang-detail-row label="Gloss">
      <alglang-gloss-field v-model="value.glosses" />

      <p v-if="glossText" class="inline ml-2">
        ({{ glossText }})
      </p>
    </alglang-detail-row>

    <alglang-detail-row label="Slot">
      <a :href="value.slot.url">
        {{ value.slot.abv }}
      </a>
    </alglang-detail-row>

    <alglang-detail-row v-if="value.description" label="Description">
      <p>
        {{ value.description }}
      </p>
    </alglang-detail-row>
  </div>
</template>

<script>
import DetailRow from '../DetailRow';
import GlossField from '../GlossField';

export default {
  props: {
    value: {
      required: true
    }
  },

  components: {
    'alglang-detail-row': DetailRow,
    'alglang-gloss-field': GlossField
  },

  computed: {
    glossText() {
      return this.value.glosses.map(gloss => gloss.name || '').join(' ');
    }
  }
};
</script>
