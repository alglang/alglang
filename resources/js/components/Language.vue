<template>
  <alglang-details
    title="Language details"
    :pages="pages"
    v-model="languageData"
    :resources="resources"
    :mode="mode"
  >
    <template v-slot:header>
      <div class="flex align-items h-12">
        <input
          v-if="mode === 'edit'"
          class="px-2 py-1 text-xl border border-gray-200 shadow-inner"
          placeholder="Name"
          aria-label="Name"
          v-model="language.name"
        />
        <h1 v-else class="text-3xl font-light">
          {{ language.name }}
        </h1>
      </div>

      <div>
        <label v-if="mode === 'edit'" class="mb-2 p-1 text-sm leading-none">
          Reconstructed
          <input type="checkbox" v-model="language.reconstructed" class="ml-2" />
        </label>
        <p
          v-else-if="language.reconstructed"
          class="mb-2 p-1 inline text-sm leading-none bg-gray-300 rounded"
        >
          Reconstructed
        </p>
      </div>
    </template>
  </alglang-details>
</template>

<script>
import Details from './Details';
import BasicDetails from './Language/BasicDetails';
import Morphemes from './Language/Morphemes';

export default {
  components: {
    'alglang-details': Details
  },

  props: {
    mode: {
      default: 'view'
    },

    language: {
      default: () => ({})
    }
  },

  data() {
    return {
      languageData: {},

      pages: [
        {
          name: 'basic-details',
          component: BasicDetails
        },
        {
          name: 'morphemes',
          component: Morphemes
        }
      ],

      resources: [
        {
          key: 'groups',
          url: '/groups'
        }
      ]
    };
  },

  created() {
    this.languageData = JSON.parse(JSON.stringify(this.language));
  }
};
</script>
