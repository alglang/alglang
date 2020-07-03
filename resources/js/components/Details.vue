<template>
  <section class="bg-white p-6">
    <header class="flex justify-between mb-4">
      <div class="leading-normal">
        <h2 for="details-title" class="block text-lg uppercase text-gray-600">
          {{ title }}
        </h2>
        <div>
          <slot name="header"></slot>
        </div>
      </div>
    </header>

    <div class="flex">
      <nav
        class="flex flex-col uppercase bg-gray-200 font-semibold mr-4"
        style="height: fit-content;"
      >
        <a
          v-for="{ name } in pages"
          :key="name"
          class="p-2 whitespace-no-wrap"
          :class="{ 'active-nav': name === activePage, 'inactive-nav': name !== activePage }"
          @click="handleClick(name)"
          >
          {{ name.replace('-', ' ') }}
        </a>
      </nav>

      <article class="overflow-hidden w-full relative">
        <component :is="activePage" v-model="value" />
      </article>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    title: {
      required: true
    },

    pages: {
      required: true,
      type: Array
    },

    value: {
      required: true
    }
  },

  data() {
    return {
      hash: ''
    };
  },

  computed: {
    activePage() {
      return this.hash || this.pages[0].name;
    }
  },

  created() {
    this.hash = window.location.hash.substring(1);
    this.pages.forEach(({ name, component }) => {
      this.$options.components[name] = component;
    });
  },

  mounted() {
    window.addEventListener('hashchange', () => {
      this.hash = window.location.hash.substring(1);
    });
  },

  methods: {
    handleClick(clickedPage) {
      window.location.hash = `#${clickedPage}`;
    }
  }
};
</script>
