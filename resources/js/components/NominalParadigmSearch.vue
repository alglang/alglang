<template>
  <form
    method="GET"
    action="/search/nominals/paradigms/results"
    class="w-full md:w-fit"
  >
    <div class="flex flex-col md:flex-row">
      <div class="mx-6 md:ml-0 md:mr-4 mb-4 md:mb-0">
        <legend
          id="language-select-label"
          class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2 w-full"
        >
          Languages
        </legend>
        <fieldset
          aria-labelledby="language-select-label"
          class="overflow-auto p-2 border border-gray-300 h-56"
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

      <div class="mx-6 md:mx-0">
        <legend
          id="paradigm-select-label"
          class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2 w-full"
        >
          Paradigms
        </legend>
        <fieldset
          aria-labelledby="paradigm-select-label"
          class="overflow-auto p-2 border border-gray-300 h-56"
        >
          <label
            v-for="paradigm in paradigmTypes"
            :key="paradigm.name"
            class="flex items-center mb-2 last:mb-0"
          >
            <input
              type="checkbox"
              name="paradigm_types[]"
              :value="paradigm.name"
              class="form-checkbox rounded-none text-blue-400"
            />
            <span class="ml-1">
              {{ paradigm.name }}
            </span>
          </label>
        </fieldset>
      </div>
    </div>

    <div class="flex justify-end mt-4">
      <button
        aria-label="Search button"
        class="px-3 py-2 shadow uppercase text-lg text-gray-700 bg-yellow-400 hover:bg-yellow-500
        focus:outline-none focus:shadow-outline"
      >
        Search
      </button>
    </div>
  </form>
</template>

<script>
export default {
  props: {
    languages: {
      type: Array,
      required: true
    },
    paradigmTypes: {
      type: Array,
      required: true
    }
  }
};
</script>
