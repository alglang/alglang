<template>
  <form
    method="GET"
    action="/search/verbs/paradigm-results"
    target="_blank"
  >
    <div>
      <label
        for="class-select"
      >
        Class
      </label>
      <select
        id="class-select"
        name="classes[]"
      >
        <option
          v-for="cls in classes"
          :key="cls.abv"
        >
          {{ cls.abv }}
        </option>
      </select>
    </div>

    <div>
      <label
        for="order-select"
      >
        Order
      </label>
      <select
        id="order-select"
        name="orders[]"
      >
        <option
          v-for="order in orders"
          :key="order.name"
        >
          {{ order.name }}
        </option>
      </select>
    </div>

    <div>
      <label
        for="language-select"
      >
        Language
      </label>
      <select
        id="language-select"
        name="languages[]"
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

    <input
      type="hidden"
      name="modes[]"
      value="Indicative"
    />

    <button
      type="submit"
    >
      Search
    </button>
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
