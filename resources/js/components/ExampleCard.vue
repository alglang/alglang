<template>
  <a
    :href="example.url"
    class="block mb-4 p-3 bg-yellow-100 hover:bg-yellow-200 text-center shadow"
    style="width: fit-content; min-width: 12rem;"
  >
    <a
      :href="example.form.url"
      class="text-gray-600"
    >
      {{ example.form.shape }}
    </a>

    <p class="text-xl py-2 text-gray-900 hover:text-gray-900">
      {{ example.shape }}
    </p>

    <p class="text-gray-800 hover:text-gray-800">
      {{ example.translation }}
    </p>
  </a>
</template>

<script>
export default {
  props: {
    example: {
      type: Object,
      required: true
    }
  }
};
</script>
