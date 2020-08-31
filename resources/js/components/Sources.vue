<template>
  <div>
    <div class="flex justify-between">
      <div class="relative flex min-w-0 text-lg ">
        <span class="text-gray-600">
          <svg
            class="absolute w-6 m-1 stroke-current"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V6.58579C21 6.851 20.8946
                 7.10536 20.7071 7.29289L14.2929 13.7071C14.1054 13.8946 14 14.149 14 14.4142V17L10
                 21V14.4142C10 14.149 9.89464 13.8946 9.70711 13.7071L3.29289 7.29289C3.10536
                 7.10536 3 6.851 3 6.58579V4Z"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </span>
        <input
          v-model="filter"
          aria-label="Filter"
          class="py-1 pl-8 pr-2 min-w-0 bg-gray-100 shadow-inner bg-transparent
                 focus:outline-none focus:shadow-outline"
          @input="onUpdate"
        />
      </div>

      <div class="flex flex-none">
        <button
          aria-label="Previous"
          class="text-blue-600 hover:text-red-600 disabled:text-gray-500 disabled:cursor-default"
          :disabled="!canNavigatePrev"
          @click="prevPage"
        >
          <svg
            class="w-8 stroke-current"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M15 19L8 12L15 5"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </button>

        <button
          aria-label="Next"
          class="text-blue-600 hover:text-red-600 disabled:text-gray-500 disabled:cursor-default"
          :disabled="!canNavigateNext"
          @click="nextPage"
        >
          <svg
            class="w-8 stroke-current"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M9 5L16 12L9 19"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </button>
      </div>
    </div>

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
    },

    canNavigateNext() {
      return this.hasMoreSources && !this.loading;
    },

    canNavigatePrev() {
      return this.page > 0;
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
      if (this.canNavigateNext) {
        this.page += 1;
        this.onUpdate();
      }
    },

    prevPage() {
      if (this.canNavigatePrev) {
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
