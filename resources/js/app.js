require('./bootstrap');
require('lato-font/css/lato-font.css');

const Vue = require('vue');
const VTooltip = require('v-tooltip');

window.Vue = Vue;

Vue.use(VTooltip);

Vue.component('alglang-details', require('./components/Details.vue').default);
Vue.component('alglang-detail-page', require('./components/DetailPage.vue').default);
Vue.component('alglang-detail-row', require('./components/DetailRow.vue').default);
Vue.component('alglang-map', require('./components/Map.vue').default);
Vue.component('alglang-gloss-field', require('./components/GlossField.vue').default);

Vue.component('alglang-sources', require('./components/Sources.vue').default);
Vue.component('alglang-examples', require('./components/Examples.vue').default);
Vue.component('alglang-language-morphemes', require('./components/Morphemes.vue').default);
Vue.component('alglang-language-verb-forms', require('./components/VerbForms.vue').default);
Vue.component('alglang-nominal-forms', require('./components/NominalForms.vue').default);

Vue.component('alglang-verb-form-search', require('./components/VerbFormSearch.vue').default);
Vue.component('alglang-verb-paradigm-search', require('./components/VerbParadigmSearch.vue').default);
Vue.component('alglang-nominal-paradigm-search', require('./components/NominalParadigmSearch.vue').default);

new Vue({ // eslint-disable-line no-new
  el: '#app'
});
