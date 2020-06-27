require('./bootstrap');
const Turbolinks = require('turbolinks');
const TurbolinksAdapter = require('vue-turbolinks');
Turbolinks.start();

window.Vue = require('vue');
const Vuex = require('vuex');

Vue.use(Vuex);
Vue.use(TurbolinksAdapter);

const store = new Vuex.Store({
  state: {
    gmapsApiKey: null
  },

  mutations: {
    setGmapsApiKey(state, { key }) {
      state.gmapsApiKey = key;
    }
  }
});

Vue.component('alglang-group', require('./components/Group.vue').default);
Vue.component('alglang-language', require('./components/Language.vue').default);
Vue.component('alglang-map', require('./components/Map.vue').default);

document.addEventListener('turbolinks:load', () => {
  new Vue({
    el: '#app',
    store
  });
});
