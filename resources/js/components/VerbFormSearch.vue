<template>
  <form
    class="flex flex-wrap"
    method="GET"
    action="/search/verbs/forms/results"
    target="_blank"
  >
    <div class="mr-8 mb-4">
      <p
        id="language-select-label"
        class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2"
      >
        Languages
      </p>
      <fieldset
        aria-labelledby="language-select-label"
        class="overflow-auto p-2 border border-gray-300"
      >
        <label
          v-for="language in languages"
          :key="language.code"
          class="flex items-center mb-2 last:mb-0"
        >
          <input
            type="checkbox"
            name="languages[]"
            :value="language.code"
            class="form-checkbox rounded-none text-blue-400"
          />
          <span class="ml-1">
            {{ language.name }}
          </span>
        </label>
      </fieldset>
    </div>

    <div>
      <ul aria-label="Structure queries">
        <li>
          <alglang-structure-panel
            v-for="i in numStructureQueries"
            :key="i"
            :prefix="'structures['+(i-1)+']'"
            aria-label="Structure query"
            :classes="classes"
            :modes="modes"
            :orders="orders"
            :features="features"
          />
        </li>
      </ul>

      <div class="flex justify-end">
        <button
          type="button"
          class="bg-gray-300 text-gray-700 hover:bg-blue-200 mr-3
                 focus:outline-none focus:shadow-outline"
          :disabled="numStructureQueries <= 1"
          aria-label="Remove structure query"
          @click="removeStructureQuery"
        >
          <svg
            class="w-8"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
              clip-rule="evenodd"
            />
          </svg>
        </button>
        <button
          type="button"
          class="bg-gray-300 text-gray-700 hover:bg-blue-200 mr-3
                 focus:outline-none focus:shadow-outline"
          aria-label="Add structure query"
          @click="addStructureQuery"
        >
          <svg
            class="w-8"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1
                 1 0 011-1z"
              clip-rule="evenodd"
            />
          </svg>
        </button>
        <button
          aria-label="Search button"
          class="px-3 py-2 shadow uppercase text-lg text-gray-700 bg-yellow-400 hover:bg-yellow-500
                 focus:outline-none focus:shadow-outline"
        >
          Search
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import VerbFormSearchStructure from './VerbFormSearchStructure';

export default {
  components: {
    'alglang-structure-panel': VerbFormSearchStructure
  },

  props: {
    languages: {
      type: Array,
      required: true
    },

    classes: {
      type: Array,
      required: true
    },

    orders: {
      type: Array,
      required: true
    },

    modes: {
      type: Array,
      required: true
    },

    features: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      numStructureQueries: 1
    };
  },

  methods: {
    addStructureQuery() {
      this.numStructureQueries += 1;
    },

    removeStructureQuery() {
      if (this.numStructureQueries > 1) {
        this.numStructureQueries -= 1;
      }
    }
  }
};
</script>
