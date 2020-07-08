<template>
  <div
    :aria-labelledby="ariaId"
    :class="{ 'bg-gray-300': disabled, disabled: disabled }"
    class="detail-row p-2 mb-2 flex items-center"
  >
    <h3
      :id="ariaId"
      :class="{ 'text-gray-700': !disabled, 'text-gray-500': disabled }"
      class="inline-block w-64 uppercase"
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
    },

    disabled: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    ariaId() {
      return `${this.label.toLowerCase().replace(' ', '-')}-detail-row`;
    }
  }
};
</script>

<style>
.detail-row.disabled a {
  pointer-events: none;
  color: #718096;
}
</style>
