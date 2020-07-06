<template>
  <div
    :aria-labelledby="ariaId"
    class="p-2 mb-2 flex items-center"
  >
    <h3
      :id="ariaId"
      class="inline-block w-64 uppercase text-gray-700"
    >
      {{ label }}
    </h3>
    <div class="inline-block w-full">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: true
    }
  },

  computed: {
    ariaId() {
      return `${this.label.toLowerCase().replace(' ', '-')}-detail-row`;
    }
  }
};
</script>
