<template>
  <a
    :href="nominalForm.url"
    class="block mb-4 p-3 bg-yellow-100 hover:bg-yellow-200 text-center shadow"
    style="width: fit-content; min-width: 12rem;"
  >
    <p class="text-gray-600">
      {{ nominalForm.argument_string }}
    </p>
    <p
      class="text-xl py-2 text-gray-900 hover:text-gray-900"
      v-html="nominalForm.formatted_shape"
    />
  </a>
</template>

<script>
export default {
  props: {
    nominalForm: {
      type: Object,
      required: true
    }
  }
};
</script>
