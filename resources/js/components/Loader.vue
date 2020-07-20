<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 64 64"
    preserveAspectRatio="xMidYMid"
  >
    <rect
      v-for="(_, i) in 9"
      :key="i"
      :x="border+border*(i%3)+size*(i%3)"
      :y="border+border*(Math.floor(i/3))+size*(Math.floor(i/3))"
      :width="size"
      :height="size"
      :fill="color1"
      :class="{ hidden: !order.includes(i) }"
    >
      <animate
        attributeName="fill"
        :values="color2+';'+color1+';'+color1"
        keyTimes="0;0.375;1"
        dur="1s"
        repeatCount="indefinite"
        :begin="order.indexOf(i)*0.125+'s'"
      />
    </rect>
  </svg>
</template>

<script>
export default {
  props: {
    color1: {
      type: String,
      default: '#e2e8f0'
    },

    color2: {
      type: String,
      default: '#2d3748'
    }
  },

  data() {
    return {
      size: 20,
      border: 1,
      order: [0, 1, 2, 5, 8, 7, 6, 3]
    };
  }
};
</script>
