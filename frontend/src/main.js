/**
 * @name main.js
 * @goal Parametrizar o Vue e dependências para operações em ADRs Systems.
 * @package br.com.adrs.erp
 * @version 0.0.1
 * @since $ 12/07/2021 $ 14:27:43 $
 * @author Daniel <daniel@antunesbr.com>
 */

import Vue from 'vue';
import App from './App.vue';

// System settings to use on whole system
import settings from './services/settings';
import support from './services/support';

Vue.prototype.$settings = settings;
Vue.prototype.$support = support;

// System http global communicator
import http from './services/http.js';

Vue.prototype.$http = http;

// Moment is the datetime javascript lib
import moment from 'moment/moment';
import locale from 'moment/locale/pt-br';

moment.updateLocale(settings.locale, locale);

Vue.prototype.moment = moment;

// Setup bootstrap vue framework
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

Vue.use(BootstrapVue, { locale: settings.locale });
Vue.use(IconsPlugin);

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

// Vue mask to mask input fields
import VueMask from 'v-mask';

Vue.use(VueMask);

// Json Web Token
import VueJWT from 'vuejs-jwt';

Vue.use(VueJWT);

// Router
import router from './router'

// Set environment
Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')