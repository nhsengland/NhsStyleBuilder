// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import App from './App';
import vSelect from 'vue-select';
import VueCkeditor from 'vue-ckeditor2';
import VueClipboard from 'vue-clipboard2';
import draggable from 'vuedraggable';
import Tooltip from 'vue-directive-tooltip';
import 'vue-directive-tooltip/css/index.css';

Vue.config.productionTip = false;

Vue.component('v-select', vSelect);
Vue.component('draggable', draggable);
Vue.use(VueCkeditor);
Vue.use(VueClipboard);
Vue.use(Tooltip);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  template: '<App/>',
  components: { App }
});