import Vue from 'vue';
import Router from 'vue-router';

// Components
import Login from '../components/Login';
import Logout from '../components/Logout';

//import Clientes from '../components/clientes/Index';
//import Usuarios from '../components/usuarios/Index';

//import Regiao from '../components/Regiao';
//import Estado from '../components/Estado';
//import Cidade from '../components/Cidade';

Vue.use(Router);

export default new Router({
  mode: 'history',
  linkActiveClass: 'active',
  routes: [
    {
      path: '/login',
      component: Login
    },
    {
      path: '/logout',
      component: Logout
    }
 /*   {
      path: '/',
      component: Clientes
    },
    {
      path: '/clientes',
      component: Clientes
    },
    {
      path: '/usuarios',
      component: Usuarios
    },
    {
      path: '/regioes',
      component: Regiao
    },
    {
      path: '/estados',
      component: Estado
    },
    {
      path: '/cidades',
      component: Cidade
    }
  */
  ]
});