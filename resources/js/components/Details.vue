<template>
  <section class="bg-white p-6">
    <header class="flex justify-between mb-4">
      <div class="leading-normal">
        <h2 class="block text-lg uppercase text-gray-600">
          {{ title }}
        </h2>
        <div>
          <slot name="header" />
        </div>
      </div>
    </header>

    <div class="flex">
      <ul
        role="tablist"
        class="flex flex-col uppercase font-semibold mr-4"
      >
        <li
          v-for="(page, i) in pages"
          :key="i"
          class="bg-gray-200"
        >
          <a
            :aria-selected="page.isActive"
            class="block p-2 whitespace-no-wrap text-gray-700 hover:bg-gray-300 hover:text-gray-700"
            :href="page.isActive ? '' : '#' + page.hash"
            role="tab"
            @click.prevent="visit(page.hash)"
          >
            {{ page.title }}
          </a>
        </li>
      </ul>

      <div class="overflow-hidden w-full relative">
        <slot />
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      pages: []
    };
  },

  mounted() {
    window.addEventListener('hashchange', () => {
      this.visit(window.location.hash.substring(1));
    });

    this.pages = this.$children;

    if (!this.pages.length) {
      return;
    }

    const hash = window.location.hash.substring(1);
    if (!this.pages.some(page => page.hash === hash)) {
      window.history.replaceState({ turbolinks: true }, null, `${window.location.pathname}#${this.pages[0].hash}`);
    }

    this.visit(window.location.hash.substring(1));
  },

  methods: {
    visit(hash) {
      if (window.location.hash !== `#${hash}`) {
        window.location.hash = `#${hash}`;
      }

      this.pages.forEach(page => page.setIsActive(hash === page.hash));
    }
  }
};
</script>

<style scoped>
a[aria-selected] {
  background-color: #c53030;  /* bg-red-700 */
  cursor: default;
}

a[aria-selected],
a[aria-selected]:hover {
  color: #edf2f7;  /* text-gray-200 */
}
</style>
