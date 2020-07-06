<template>
  <div>
    <alglang-detail-row label="Algonquianist code">
      <div class="flex items-center h-8">
        <input
          v-if="mode === 'edit'"
          v-model="value.algo_code"
          class="px-3 py-2 border border-gray-200 shadow-inner"
        />
        <p v-else>
          {{ value.algo_code }}
        </p>
      </div>
    </alglang-detail-row>

    <alglang-detail-row label="Group">
      <div class="flex items-center h-8">
        <select
          v-if="mode === 'edit'"
          v-model="value.group_id"
          class="px-3 py-2 border border-gray-200 shadow-inner"
        >
          <option v-for="group in resources.groups" :key="group.id" :value="group.id" class="py-2">
            {{ group.name }}
          </option>
        </select>
        <p v-else>
          <a :href="value.group.url">
            {{ value.group.name }}
          </a>
        </p>
      </div>
    </alglang-detail-row>

    <alglang-detail-row
      v-if="value.children && value.children.length"
      label="Direct children"
      :disabled="mode === 'edit'"
    >
      <ul>
        <li v-for="child of value.children" :key="child.name">
          <a :href="child.url">
            {{ child.name }}
          </a>
        </li>
      </ul>
    </alglang-detail-row>

    <alglang-detail-row v-if="mode === 'edit' || value.notes" label="Notes">
      <div class="flex items-center">
        <textarea
          v-if="mode === 'edit'"
          v-model="value.notes"
          class="px-3 w-full h-32 border border-gray-200 shadow-inner"
        ></textarea>
        <p v-else>
          {{ value.notes }}
        </p>
      </div>
    </alglang-detail-row>

    <alglang-detail-row v-if="mode === 'edit' || value.position" label="Location">
      <alglang-map v-if="mode === 'edit'" :style="{ height: '30rem' }" v-model="value" />
      <alglang-map v-else :style="{ height: '30rem' }" />
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

  props: {
    value: {
      required: true
    },

    mode: {
      default: 'view'
    },

    resources: {
      default: () => ({ groups: [] })
    }
  }
};
</script>
