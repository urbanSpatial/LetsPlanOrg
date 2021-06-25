import Vue from 'vue';
import 'leaflet/dist/leaflet.css';
import { Icon } from 'leaflet';
import { App, plugin } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress';
import Layout from './Shared/Layouts/Layout.vue';
import vuetify from './plugins/vuetify';
import store from './store';

import '../scss/app.scss';
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css';
import '../scss/overrides.scss';
import './bootstrap';

// set up inertia
Vue.use(plugin);

// set up progress indicators
InertiaProgress.init();

// set up ziggy routes
Vue.mixin({ methods: { route } });

const el = document.getElementById('app');

const iconRetinaUrl = require('leaflet/dist/images/marker-icon-2x.png');
const iconUrl = require('leaflet/dist/images/marker-icon.png');
const shadowUrl = require('leaflet/dist/images/marker-shadow.png');

// eslint-disable-next-line no-underscore-dangle
delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
  iconRetinaUrl,
  iconUrl,
  shadowUrl,
});

new Vue({
  vuetify,
  store,
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
