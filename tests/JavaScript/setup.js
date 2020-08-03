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
