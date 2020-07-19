require('jsdom-global')('', {
  url: 'https://alglang.net'
});

const chai = require('chai');
const chaiDom = require('chai-dom');

chai.use(chaiDom);
