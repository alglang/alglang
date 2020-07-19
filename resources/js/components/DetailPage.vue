<template>
  <div v-if="isActive">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'DetailPage',

  props: {
    title: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      isActive: false
    };
  },

  computed: {
    hash() {
      return this.title.toLowerCase().replace(' ', '-');
    }
  },

  methods: {
    setIsActive(value) {
      this.isActive = value;
    }
  }
};
</script>
