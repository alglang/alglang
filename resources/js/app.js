import Vue from 'vue';
import 'livewire-vue';

require('./bootstrap');
require('lato-font/css/lato-font.css');

window.Vue = Vue;

function dispatchMediaSize(mqls) {
  const size = Object.entries(mqls).find(([_, mql]) => mql.matches)[0];

  if (window.livewire) {
    window.livewire.emit('resize', size);
  }
}

const mqls = {
  xl: window.matchMedia('(min-width: 1280px)'),
  lg: window.matchMedia('(min-width: 1024px)'),
  md: window.matchMedia('(min-width: 768px)'),
  sm: window.matchMedia('(min-width: 640px)'),
  xs: window.matchMedia('(min-width: 0px)')
};

Object.values(mqls).forEach(mql => mql.addListener(() => dispatchMediaSize(mqls)));
dispatchMediaSize(mqls);
