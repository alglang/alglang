<template>
  <section class="bg-white p-6">
    <header class="flex justify-between mb-6">
      <div class="leading-normal w-full md:w-auto text-center md:text-left">
        <h2 class="block text-base uppercase text-gray-600">
          {{ title }}
        </h2>
        <div>
          <slot name="header" />
        </div>
      </div>
    </header>

    <div class="flex flex-wrap md:flex-no-wrap">
      <ul
        role="tablist"
        class="uppercase font-semibold grid md:block grid-cols-2
               md:whitespace-no-wrap mb-4 md:mr-4 w-full md:w-fit"
      >
        <li
          v-for="(page, i) in pages"
          :key="i"
          class="block"
        >
          <component
            :is="page.count === 0 ? 'p' : 'a'"
            :aria-selected="page.isActive"
            class="bg-gray-200 text-xs md:text-base flex justify-between items-center h-full p-2"
            :class="page.count === 0 ?
              'cursor-not-allowed text-gray-500' :
              'text-gray-700 hover:bg-gray-300 hover:text-gray-700'"
            :href="page.isActive ? '' : '#' + page.hash"
            role="tab"
            @click.prevent="visit(page.hash)"
          >
            <div>
              {{ page.title }}
            </div>
            <div
              v-if="page.count !== null"
              class="ml-2 md:ml-4 bg-white shadow-inner text-gray-600 px-1 rounded-full
                     text-xs font-bold"
            >
              {{ page.count }}
            </div>
          </component>
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

    this.pages = this.$children.filter(child => child.$options.name === 'DetailPage');

    if (!this.pages.length) {
      return;
    }

    const hash = window.location.hash.substring(1);
    if (!this.pages.some(page => page.hash === hash)) {
      window.history.replaceState(null, null, `${window.location.pathname}${window.location.search}#${this.pages[0].hash}`);
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
