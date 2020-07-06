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

      <div v-if="loading">
        Loading...
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
        <component
          :is="activePage"
          :value="value"
          :mode="mode"
          :resources="repositories"
          @input="$emit('input', $event)"
        />
      </article>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

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
    },

    mode: {
      default: 'view'
    },

    resources: {
      default: () => []
    }
  },

  data() {
    return {
      loading: false,
      repositories: {},
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

    if (this.mode === 'edit') {
      this.loadResources();
    }
  },

  mounted() {
    window.addEventListener('hashchange', () => {
      this.hash = window.location.hash.substring(1);
    });
  },

  methods: {
    handleClick(clickedPage) {
      window.location.hash = `#${clickedPage}`;
    },

    loadResources() {
      this.loading = true;

      const promises = this.resources.map(this.loadResource);

      Promise.all(promises).finally(() => { this.loading = false; });
    },

    async loadResource({ key, url }) {
      const response = await axios.get(url);
      this.$set(this.repositories, key, response.data.data);
    }
  }
};
</script>
