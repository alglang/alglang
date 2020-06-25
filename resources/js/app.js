require('./bootstrap');

window.Vue = require('vue');

Vue.component('alglang-group', require('./components/Group.vue').default);
Vue.component('alglang-map', require('./components/Map.vue').default);

const app = new Vue({
  el: '#app'
});
