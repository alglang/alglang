<template>
  <alglang-details
    v-model="languageData"
    title="Language details"
    :pages="pages"
    :resources="resources"
    :mode="modeState"
    :can-edit="canEdit"
    @modeChange="modeState = $event"
    @save="save"
  >
    <template v-slot:header>
      <div class="flex align-items h-12">
        <input
          v-if="modeState === 'edit'"
          v-model="language.name"
          class="px-2 py-1 text-xl border border-gray-200 shadow-inner"
          placeholder="Name"
          aria-label="Name"
        />
        <h1
          v-else
          class="text-3xl font-light"
        >
          {{ language.name }}
        </h1>
      </div>

      <div>
        <label
          v-if="modeState === 'edit'"
          class="mb-2 p-1 text-sm leading-none"
        >
          Reconstructed
          <input
            v-model="language.reconstructed"
            type="checkbox"
            class="ml-2"
          />
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
import axios from 'axios';

import Details from './Details';
import BasicDetails from './Language/BasicDetails';
import Morphemes from './Language/Morphemes';

export default {
  components: {
    'alglang-details': Details
  },

  props: {
    mode: {
      type: String,
      default: 'view'
    },

    canEdit: {
      type: Boolean,
      default: false
    },

    language: {
      type: Object,
      default: () => ({})
    }
  },

  data() {
    return {
      languageData: {},

      modeState: 'view',

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
          url: '/api/groups'
        },
        {
          key: 'languages',
          url: '/api/languages'
        }
      ]
    };
  },

  created() {
    this.languageData = JSON.parse(JSON.stringify(this.language));
    this.modeState = this.mode;
  },

  methods: {
    save() {
      axios.post('/api/languages', this.languageData);
    }
  }
};
</script>
