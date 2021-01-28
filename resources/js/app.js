import { App, plugin } from '@inertiajs/inertia-vue';
import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

import '../scss/app.scss';

Vue.use(plugin);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

const el = document.getElementById('app');

new Vue({
  render: (h) => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      // eslint-disable-next-line global-require, import/no-dynamic-require
      resolveComponent: (name) => require(`./Pages/${name}`).default,
    },
  }),
}).$mount(el);
