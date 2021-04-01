import Vue from 'vue';
import { App, plugin } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress';
import Layout from './Shared/Layouts/Layout.vue';
import vuetify from './plugins/vuetify';

import '../scss/app.scss';
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css';
import './bootstrap';

// set up inertia
Vue.use(plugin);

// set up progress indicators
InertiaProgress.init();

// set up ziggy routes
Vue.mixin({ methods: { route } });

const el = document.getElementById('app');

new Vue({
  vuetify,
  render: (h) => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: (name) => {
        // eslint-disable-next-line global-require, import/no-dynamic-require
        const page = require(`./Pages/${name}`).default;
        page.layout = page.layout === undefined ? Layout : page.layout;
        return page;
      },
    },
  }),
}).$mount(el);
