require('./bootstrap');
const Turbolinks = require('turbolinks');
const TurbolinksAdapter = require('vue-turbolinks');
const Vue = require('vue');

Turbolinks.start();

window.Vue = Vue;

Vue.use(TurbolinksAdapter);

Vue.component('alglang-group', require('./components/Group.vue').default);
Vue.component('alglang-language', require('./components/Language.vue').default);
Vue.component('alglang-morpheme', require('./components/Morpheme.vue').default);
Vue.component('alglang-slot', require('./components/Slot.vue').default);
Vue.component('alglang-gloss', require('./components/Gloss.vue').default);
Vue.component('alglang-map', require('./components/Map.vue').default);

document.addEventListener('turbolinks:load', () => {
  new Vue({ // eslint-disable-line no-new
    el: '#app'
  });
});
