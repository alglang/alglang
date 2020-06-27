require('./bootstrap');

window.Vue = require('vue');
const Vuex = require('vuex');

Vue.use(Vuex);

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

const app = new Vue({
  el: '#app',
  store
});
