<template>
  <ul class="inline">
    <li
      v-for="gloss in value"
      :key="gloss.abv"
      class="inline-block"
    >
      <a
        v-if="gloss.url"
        :href="gloss.url"
        class="inline-block"
      >
        {{ gloss.abv }}
      </a>
      <span
        v-else
        class="inline-block"
      >
        {{ gloss.abv }}
      </span>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    value: {
      type: Array,
      required: true
    }
  }
};
</script>

<style scoped>
li:not(:last-child):after {
    display: inline-block;
    content: '.'
}
</style>
