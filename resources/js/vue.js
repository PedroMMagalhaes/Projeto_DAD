require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import UserComponent from './components/user.vue';
import ProfileComponent from './components/profile.vue';
import LoginComponent from './components/login.vue';
import LogoutComponent from './components/logout.vue';

//Pagina Inicial
import InitialPageComponent from './components/initial.vue';

const user = Vue.component('user', UserComponent);
const profile = Vue.component('profile', ProfileComponent);
const login = Vue.component('login', LoginComponent);
const logout = Vue.component('logout', LogoutComponent);
const initial = Vue.component('initial', InitialPageComponent);

const routes = [
  { path: '/', redirect: '/users' },
  { path: '/users', component: user },
  { path: '/profile', component: profile },
  { path: '/login', component: login },
  { path: '/logout', component: logout },
  { path: '/initialpage', component: initial },
];

const router = new VueRouter({
  routes:routes
});

const app = new Vue({
  router,
  data:{
    player1:undefined,
    player2: undefined,
  }
}).$mount('#app');

