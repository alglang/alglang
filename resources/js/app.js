require('./bootstrap');
const Turbolinks = require('turbolinks');
const TurbolinksAdapter = require('vue-turbolinks');
Turbolinks.start();

window.Vue = require('vue');

Vue.use(TurbolinksAdapter);

Vue.component('alglang-group', require('./components/Group.vue').default);
Vue.component('alglang-language', require('./components/Language.vue').default);
Vue.component('alglang-morpheme', require('./components/Morpheme.vue').default);
Vue.component('alglang-map', require('./components/Map.vue').default);

document.addEventListener('turbolinks:load', () => {
  new Vue({
    el: '#app'
  });
});
