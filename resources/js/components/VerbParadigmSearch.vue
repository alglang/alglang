<template>
  <form
    method="GET"
    action="/search/verbs/paradigm-results"
    target="_blank"
  >
    <div>
      <div class="flex">
        <div class="bg-gray-700 text-gray-200 w-full flex items-center justify-end">
          <label
            for="class-select"
            class="mx-2 uppercase text-xs font-semibold"
          >
            Class
          </label>
        </div>
        <select
          id="class-select"
          name="classes[]"
          class="form-select rounded-none w-56 flex-shrink-0"
        >
          <option
            v-for="cls in classes"
            :key="cls.abv"
          >
            {{ cls.abv }}
          </option>
        </select>
      </div>

      <div class="flex">
        <div class="bg-gray-700 text-gray-200 w-full flex items-center justify-end">
          <label
            for="order-select"
            class="mx-2 uppercase text-xs font-semibold"
          >
            Order
          </label>
        </div>
        <select
          id="order-select"
          name="orders[]"
          class="form-select rounded-none w-56 flex-shrink-0"
        >
          <option
            v-for="order in orders"
            :key="order.name"
          >
            {{ order.name }}
          </option>
        </select>
      </div>

      <div class="flex">
        <div class="bg-gray-700 text-gray-200 w-full flex items-center justify-end">
          <label
            for="language-select"
            class="mx-2 uppercase text-xs font-semibold"
          >
            Language
          </label>
        </div>
        <select
          id="language-select"
          name="languages[]"
          class="form-select rounded-none w-56 flex-shrink-0"
        >
          <option
            v-for="language in languages"
            :key="language.code"
            :value="language.code"
          >
            {{ language.name }}
          </option>
        </select>
      </div>
    </div>

    <input
      type="hidden"
      name="modes[]"
      value="Indicative"
    />

    <div class="flex justify-end mt-4">
      <button
        class="px-3 py-2 shadow uppercase text-lg text-gray-700 bg-yellow-400 hover:bg-yellow-500
               focus:outline-none focus:shadow-outline"
        type="submit"
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

    classes: {
      type: Array,
      required: true
    },

    orders: {
      type: Array,
      required: true
    }
  }
};
</script>
