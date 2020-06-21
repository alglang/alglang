require('./bootstrap');

window.Vue = require('vue');

Vue.component('alglang-group', require('./components/Group.vue').default);

const app = new Vue({
  el: '#app'
});
