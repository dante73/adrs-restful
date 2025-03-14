import Vue from 'vue';
import Router from 'vue-router';

// Components
import Login from '@/components/app/auth/Login';
import Logout from '@/components/app/auth/Logout';

import Pessoas from '@/components/pessoas/Index';
import Clientes from '@/components/clientes/Index';
import Usuarios from '@/components/usuarios/Index';

import Regiao from '@/components/localidades/Regiao';
import Estado from '@/components/localidades/Estado';
import Cidade from '@/components/localidades/Cidade';

import HelloWorld from "@/components/HelloWorld";

Vue.use(Router);

export default new Router({
  mode: 'history',
  linkActiveClass: 'active',
  routes: [
    {
      name: 'Entrar',
      path: '/login',
      component: Login
    },
    {
      name: 'Sair',
      path: '/logout',
      component: Logout
    },
    {
      name: 'Home',
      path: '/',
      component: Pessoas
    },
    {
      name: 'Cadastro de Pessoas',
      path: '/pessoas',
      component: Pessoas
    },
    {
      name: 'Cadastro de Clientes',
      path: '/clientes',
      component: Clientes
    },
    {
      name: 'Cadastro de Usuários',
      path: '/usuarios',
      component: Usuarios
    },
    {
      name: 'Tabela de Regiões',
      path: '/regioes',
      component: Regiao
    },
    {
      name: 'Tabela de Estados',
      path: '/estados',
      component: Estado
    },
    {
      name: 'Tabela de Cidades',
      path: '/cidades',
      component: Cidade
    },
    {
      name: 'Hello World',
      path: '/helloworld',
      component: HelloWorld
    }
  ]
});