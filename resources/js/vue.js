require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './stores/global-store';
import Vuelidate from 'vuelidate'

Vue.use(VueRouter);
Vue.use(Vuelidate);

import UserComponent from './components/users/user.vue';
import ProfileComponent from './components/users/profile.vue';
import LoginComponent from './components/users/login.vue';
import LogoutComponent from './components/users/logout.vue';
import WalletComponent from './components/wallets/wallet.vue';
import RegisterComponent from './components/users/register.vue';
//Pagina Inicial
import InitialPageComponent from './components/initial.vue';

const user = Vue.component('user', UserComponent);
const profile = Vue.component('profile', ProfileComponent);
const login = Vue.component('login', LoginComponent);
const logout = Vue.component('logout', LogoutComponent);
const wallet = Vue.component('logout', WalletComponent);
const initial = Vue.component('initial', InitialPageComponent);
const register = Vue.component('register', RegisterComponent);

const routes = [
  { path: '/', redirect: '/initialpage', name: 'root' },
  { path: '/users', component: user , name: 'users'},
  { path: '/profile', component: profile , name: 'profile'},
  { path: '/login', component: login , name: 'login' },
  { path: '/logout', component: logout , name:'logout'},
  { path: '/wallet', component: wallet , name:'wallet'},
  { path: '/initialpage', component: initial, name: 'initialpage' },
  { path: '/register', component: register, name: 'register' },


];

const router = new VueRouter({
  routes:routes
});

router.beforeEach((to, from, next) => {
  if ((to.name == 'profile') || (to.name == 'wallet') || (to.name == 'logout') || (to.name == 'users')) {
      if (!store.state.user) {
          next("/login");
          return;
      }
  }
  next();
});


const app = new Vue({
  router,
  data:{
    player1:undefined,
    player2: undefined,
  },

store,
created() {
    console.log('-----');
    console.log(this.$store.state.user);
    this.$store.commit('loadTokenAndUserFromSession');
    console.log(this.$store.state.user);
}
}).$mount('#app');

