<template>
  <div>
    <alglang-detail-row label="Algonquianist code">
      <p>
        {{ value.algo_code }}
      </p>
    </alglang-detail-row>

    <alglang-detail-row label="Group">
      <a :href="value.group.url">
        {{ value.group.name }}
      </a>
    </alglang-detail-row>

    <alglang-detail-row v-if="value.children && value.children.length" label="Direct children">
      <ul>
        <li v-for="child of value.children" :key="child.name">
          <a :href="child.url">
            {{ child.name }}
          </a>
        </li>
      </ul>
    </alglang-detail-row>

    <alglang-detail-row v-if="value.notes" label="Notes">
      <p>
        {{ value.notes }}
      </p>
    </alglang-detail-row>

    <alglang-detail-row v-if="value.position" label="Location">
      <alglang-map :style="{ height: '30rem' }" :locations="[value]" />
    </alglang-detail-row>
  </div>
</template>

<script>
import Map from '../Map';
import DetailRow from '../DetailRow';

export default {
  components: {
    'alglang-map': Map,
    'alglang-detail-row': DetailRow
  },

  props: ['value']
};
</script>
