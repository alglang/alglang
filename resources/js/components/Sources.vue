<template>
  <div>
    <input
      v-model="filter"
      aria-label="Filter"
      class="border border-gray-700 py-1 px-2 text-lg w-full"
      @input="onUpdate"
    />

    <button
      aria-label="Previous"
      :disabled="page > 0"
      @click="prevPage"
    >
      Previous
    </button>

    <button
      aria-label="Next"
      :disabled="!hasMoreSources"
      @click="nextPage"
    >
      Next
    </button>

    <div
      v-if="loading"
      class="flex justify-center"
    >
      <alglang-loader
        aria-label="Loading"
        class="w-16 h-16 absolute mt-12"
      />
    </div>

    <ul
      ref="sources"
      class="mt-4 flex flex-col flex-wrap content-start
             h-full leading-relaxed text-lg"
      :style="{height: height}"
    >
      <li
        v-for="source in visibleSources"
        :key="source.id"
        class="mr-12"
      >
        <a :href="source.url">
          {{ source.short_citation }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

import Loader from './Loader';

export default {
  components: {
    'alglang-loader': Loader
  },

  props: {
    url: {
      type: String,
      required: true
    },

    perPage: {
      type: Number,
      default: 200
    }
  },

  data() {
    return {
      loading: true,
      maxPerPage: 200,
      page: 0,
      nextUrl: null,
      height: '100vh',
      filter: '',
      sources: []
    };
  },

  computed: {
    filteredSources() {
      return this.sources.filter(
        source => source.short_citation.toLowerCase().includes(this.filter.toLowerCase())
      );
    },

    visibleSources() {
      return this.filteredSources.slice(
        this.computedPerPage * this.page,
        this.computedPerPage * (this.page + 1)
      );
    },

    computedPerPage() {
      return Math.min(this.perPage, this.maxPerPage);
    },

    hasMoreSources() {
      return this.nextUrl || this.filteredSources.length > this.computedPerPage * (this.page + 1);
    }
  },

  created() {
    this.nextUrl = this.url;
    this.maxPerPage = this.perPage;
  },

  async mounted() {
    window.addEventListener('resize', () => {
      this.maxPerPage = this.perPage;
      this.resizeWindow();
      this.$nextTick(this.shrink);
    });

    await this.loadSources();
    this.resizeWindow();
  },

  methods: {
    nextPage() {
      if (this.hasMoreSources && !this.loading) {
        this.page += 1;
        this.onUpdate();
      }
    },

    prevPage() {
      if (this.page > 0) {
        this.page -= 1;
      }
    },

    async onUpdate() {
      if (this.visibleSources.length < this.computedPerPage && this.nextUrl && !this.loading) {
        this.loadSources();
      }
    },

    async loadSources() {
      if (!this.nextUrl) {
        return;
      }

      this.loading = true;
      const response = await axios.get(this.nextUrl);
      const json = response.data;
      this.nextUrl = json.links.next;
      this.sources = this.sources.concat(json.data);
      this.loading = false;

      this.$nextTick(this.shrink);
    },

    resizeWindow() {
      const viewportHeight = window.innerHeight;
      const sourcesTop = this.$refs.sources.offsetTop;
      this.height = `${viewportHeight - sourcesTop}px`;
    },

    shrink() {
      if (this.$refs.sources.scrollWidth > this.$refs.sources.clientWidth) {
        this.maxPerPage -= 1;
        this.$nextTick(this.shrink);
      }
    }
  }
};
</script>
