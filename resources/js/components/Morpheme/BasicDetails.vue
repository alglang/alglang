<template>
  <div>
    <alglang-detail-row label="Gloss">
      <alglang-gloss-field v-model="value.glosses" />

      <p
        v-if="glossText"
        class="inline ml-2"
      >
        ({{ glossText }})
      </p>
    </alglang-detail-row>

    <alglang-detail-row label="Slot">
      <a
        :href="value.slot.url"
        :style="{ color: value.slot.colour }"
      >
        {{ value.slot.abv }}
      </a>
    </alglang-detail-row>

    <alglang-detail-row
      v-if="value.allomorphy_notes"
      label="Allomorphy"
    >
      <div v-html="value.allomorphy_notes" />
    </alglang-detail-row>

    <alglang-detail-row
      v-if="value.historical_notes"
      label="Historical notes"
    >
      <div v-html="value.historical_notes" />
    </alglang-detail-row>

    <alglang-detail-row
      v-if="value.private_notes"
      label="Private notes"
    >
      <div v-html="value.private_notes" />
    </alglang-detail-row>
  </div>
</template>

<script>
import DetailRow from '../DetailRow';
import GlossField from '../GlossField';

export default {
  components: {
    'alglang-detail-row': DetailRow,
    'alglang-gloss-field': GlossField
  },

  props: {
    value: {
      type: Object,
      required: true
    }
  },

  computed: {
    glossText() {
      return this.value.glosses.map(gloss => gloss.name || '').join(' ');
    }
  }
};
</script>
