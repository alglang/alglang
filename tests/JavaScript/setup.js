require('jsdom-global')('', {
  url: 'https://alglang.net'
});

window.Turbolinks = {
  visit: hash => {
    window.location.hash = hash;
  }
};

const chai = require('chai');
const chaiDom = require('chai-dom');

chai.use(chaiDom);

// Make sure JSDom doesn't crash and burn when dealing with SVGs
const createElementNSOrig = global.document.createElementNS;
global.document.createElementNS = function (namespaceURI, qualifiedName, ...args) {
  if (namespaceURI === 'http://www.w3.org/2000/svg' && qualifiedName === 'svg') {
    const element = createElementNSOrig.apply(this, [namespaceURI, qualifiedName, ...args]);
    element.createSVGRect = () => {};
    return element;
  }
  return createElementNSOrig.apply(this, [namespaceURI, qualifiedName, ...args]);
};
