/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
import '../css/app.css';
import $ from 'jquery';
import Vue from 'vue';
import * as VeeValidate from 'vee-validate';
import VueLoaders from 'vue-loaders';
import {
  BootstrapVue,
  IconsPlugin
} from 'bootstrap-vue';
import Login from './components/utilisateur/login';
import Role from './components/admin/role';
import ListUser from './components/admin/utilisateur';
import EditUser from './components/admin/utilisateur/form';
import ShowUser from './components/admin/utilisateur/show';
import EditAccount from './components/utilisateur/editAccount';
import Home from './components/home';
import ExampleForm from './components/example/form';
import SousNavBar from './components/commons/navBar';
import cnsRenderUtils from './plugins/cnsRenderUtils';
import cnsFormUtils from 'plugins/cnsFormUtils';
import i18n from 'plugins/i18n';
import Avatar from 'vue-avatar';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(cnsRenderUtils);
Vue.use(cnsFormUtils);
Vue.use(VueLoaders);

Vue.use(VeeValidate, {
  inject: true,
  events: '', // Automatic validation is desactivated
  fieldsBagName: 'veeFields',
});

new Vue({
  el: '#app',
  i18n,
  components: {
    Role,
    Avatar,
    EditUser,
    ListUser,
    ShowUser,
    Home,
    ExampleForm,
    SousNavBar,
    EditAccount
  }
})
