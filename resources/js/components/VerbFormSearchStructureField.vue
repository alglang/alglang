<template>
  <div class="flex lg:flex-col lg:w-fit">
    <label
      :for="forId"
      class="block uppercase flex-1 text-xs font-semibold bg-gray-300
             text-gray-700 p-2 md:px-3 flex justify-end lg:justify-start items-center"
    >{{ label }}</label>

    <div class="w-24 md:w-32 lg:w-auto">
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
    forId: {
      type: String,
      default: ''
    }
  }
};
</script>
