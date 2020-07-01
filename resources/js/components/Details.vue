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
            <nav class="flex flex-col uppercase bg-gray-200 font-semibold mr-4" style="height: fit-content;">
                <a
                    v-for="{ name }, i in pages"
                    :key="name"
                    class="p-2 whitespace-no-wrap"
                    :class="{
                        'text-gray-700': name !== activePage,
                        'hover:bg-gray-300': name !== activePage,
                        'hover:text-gray-700': name !== activePage,
                        'text-gray-200': name === activePage,
                        'bg-red-700': name === activePage,
                        'hover:text-gray-200': name === activePage
                    }"
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
            activePage: null
        };
    },

    created() {
        const key = window.location.hash ? window.location.hash.substring(1) : this.pages[0].name;
        this.activePage = key;

        this.pages.forEach(({ name, component }) => {
            this.$options.components[name] = component;
        });
    },

    methods: {
        handleClick(clickedPage) {
            this.activePage = clickedPage;
            window.location.hash = `#${clickedPage}`;
        }
    }
};
</script>
