require('./bootstrap');
require('lato-font/css/lato-font.css');

const Turbolinks = require('turbolinks');
const TurbolinksAdapter = require('vue-turbolinks');
const Vue = require('vue');
const VTooltip = require('v-tooltip');

Turbolinks.start();

window.Vue = Vue;
window.Turbolinks = Turbolinks;

Vue.use(TurbolinksAdapter);
Vue.use(VTooltip);

Vue.component('alglang-details', require('./components/Details.vue').default);
Vue.component('alglang-detail-page', require('./components/DetailPage.vue').default);
Vue.component('alglang-detail-row', require('./components/DetailRow.vue').default);
Vue.component('alglang-map', require('./components/Map.vue').default);
Vue.component('alglang-gloss-field', require('./components/GlossField.vue').default);

Vue.component('alglang-sources', require('./components/Sources.vue').default);
Vue.component('alglang-examples', require('./components/Examples.vue').default);
Vue.component('alglang-language-morphemes', require('./components/Language/Morphemes.vue').default);
Vue.component('alglang-language-verb-forms', require('./components/Language/VerbForms.vue').default);
Vue.component('alglang-nominal-forms', require('./components/NominalForms.vue').default);

Vue.component('alglang-verb-form-search', require('./components/VerbFormSearch.vue').default);
Vue.component('alglang-nominal-paradigm-search', require('./components/NominalParadigmSearch.vue').default);

document.addEventListener('turbolinks:load', () => {
  new Vue({ // eslint-disable-line no-new
    el: '#app'
  });
});

/**
 * Monkey-patch Turbolinks to handle 403, 404, and 500 responses the same as success responses
 *
 * From https://github.com/turbolinks/turbolinks/issues/179#issuecomment-289287888
 */
Turbolinks.HttpRequest.prototype.requestLoaded = function () {
  return this.endRequest(() => {
    const code = this.xhr.status;
    if ((code >= 200 && code <= 300) || code === 403 || code === 404 || code === 500) {
      this.delegate.requestCompletedWithResponse(this.xhr.responseText, this.xhr.getResponseHeader('Turbolinks-Location'));
    } else {
      this.failed = true;
      this.delegate.requestFailedWithStatusCode(code, this.xhr.responseText);
    }
  });
};

/**
 * Force the browser to do a full reload on the next request if it hits a JavaScript error
 *
 * From https://github.com/turbolinks/turbolinks/issues/277#issuecomment-317424899
 */

let jsErrorOcurred = false;

window.addEventListener('error', () => { jsErrorOcurred = true; });

document.addEventListener('turbolinks:before-visit', event => {
  // If a JavaScript error has occurred, cancel the next TurboLinks request and force
  // a regular one instead
  if (jsErrorOcurred) {
    event.preventDefault();
    window.location.href = event.data.url;
  }
});

/**
 * Force the page to reload when navigating with history
 *
 * From https://github.com/turbolinks/turbolinks/issues/413#issuecomment-419518885
 */
(function () {
  const reloadWithTurbolinks = (function () {
    let scrollPosition;

    function reload() {
      scrollPosition = [window.scrollX, window.scrollY];
      Turbolinks.visit(window.location.toString(), { action: 'replace' });
    }

    window.addEventListener('turbolinks:load', function () {
      if (scrollPosition) {
        window.scrollTo(window, scrollPosition);
        scrollPosition = null;
      }
    });

    return reload;
  }());

  window.addEventListener('popstate', function () {
    requestAnimationFrame(reloadWithTurbolinks);
  });
}());
