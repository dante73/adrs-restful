import Vue from 'vue';
import Router from 'vue-router';

// Components
import Login from '../components/app/auth/Login';
import Logout from '../components/app/auth/Logout';

import Pessoas from '../components/pessoas/Index';
import Clientes from '../components/clientes/Index';
import Usuarios from '../components/usuarios/Index';

import Regiao from '../components/localidades/Regiao';
import Estado from '../components/localidades/Estado';
import Cidade from '../components/localidades/Cidade';

import HelloWorld from "@/components/HelloWorld";

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
    },
    {
      path: '/',
      component: Pessoas
    },
    {
      path: '/pessoas',
      component: Pessoas
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
    },
    {
      path: '/helloworld',
      component: HelloWorld
    }
  ]
});