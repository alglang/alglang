require('./bootstrap');
const Turbolinks = require('turbolinks');
const TurbolinksAdapter = require('vue-turbolinks');
const Vue = require('vue');

Turbolinks.start();

window.Vue = Vue;
window.Turbolinks = Turbolinks;

Vue.use(TurbolinksAdapter);

Vue.component('alglang-details', require('./components/Details.vue').default);
Vue.component('alglang-detail-page', require('./components/DetailPage.vue').default);
Vue.component('alglang-detail-row', require('./components/DetailRow.vue').default);
Vue.component('alglang-map', require('./components/Map.vue').default);

Vue.component('alglang-language-morphemes', require('./components/Language/Morphemes.vue').default);

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
