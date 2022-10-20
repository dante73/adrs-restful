<template>
  <div>

    <b-container class="p-0 m-0" fluid v-if="isLogged">

      <!-- Sidebar with logo, menu and company information  -->
      <b-container id="leftContainer">
        <b-sidebar id="sidebar" @change="sidebarChangesVisibility" type="light" no-header shadow>
          <b-img :src="require('./assets/img/logo.png')" fluid></b-img>
          <b-nav class="mb-3">
            <b-nav-item active>
              <b-icon icon="shop"></b-icon>
              <router-link to='/Clientes'> Cadastro de Clientes</router-link>
            </b-nav-item>
            <b-nav-item active>
              <b-icon icon="person-fill"></b-icon>
              <router-link to='/Usuarios'> Cadastro de Usuários</router-link>
            </b-nav-item>
          </b-nav>
          <template #footer>
            <div class="sb-footer py-3 px-3">
              <p class="mr-auto">
                <span class="small">Logado como:<br/></span>
                <span>SmartControl ERP</span>
              </p>
            </div>
          </template>
        </b-sidebar>
      </b-container>

      <!-- Navbar -->
      <b-navbar type="light" toggleable="sm" class="pl-1 py-0 border-bottom border-success shadow-sm" bg-transparent fixed-top>

        <!-- ADRs Logo -->
        <b-navbar-brand href="#">
          <b-img :src="require('./assets/img/logo_white_mini.png')" width="100%"></b-img>
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav class="pb-0 pt-4">

          <!-- Horizontal Menu -->
          <b-navbar-nav>

            <b-nav-item-dropdown text="Gerenciar Dados">
              <b-dropdown-item href="#">
                <b-icon icon="person-fill"></b-icon>
                <router-link to='/Pessoas'> Cadastro de Pessoas</router-link>
              </b-dropdown-item>
              <b-dropdown-divider></b-dropdown-divider>
              <b-dropdown-item href="#">
                <b-icon icon="shop"></b-icon>
                <router-link to='/Clientes'> Cadastro de Clientes</router-link>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <b-icon icon="person-fill"></b-icon>
                <router-link to='/Usuarios'> Cadastro de Usuários</router-link>
              </b-dropdown-item>
              <b-dropdown-divider></b-dropdown-divider>
              <b-dropdown-item href="#">
                <router-link to='/estados'>de Estados da Federação</router-link>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <router-link to='/regioes'>de Regiões do Brasil</router-link>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <router-link to='/cidades'>de Cidades do Brasil *slow*</router-link>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <router-link to='/pessoas'>de Pessoas</router-link>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <router-link to='/users'>de Usuários</router-link>
              </b-dropdown-item>
            </b-nav-item-dropdown>

          </b-navbar-nav>

          <!-- Right aligned nav items -->
          <b-navbar-nav class="ml-auto">

            <!--
            <b-nav-form>
              <b-input-group class="mb-2 mr-2">
                <b-form-input class="ml-sm-2" placeholder="Search"></b-form-input>
                <b-input-group-append is-text>
                  <b-icon icon="search"></b-icon>
                </b-input-group-append>
              </b-input-group>
            </b-nav-form>
            -->

            <b-nav-item-dropdown right>

              <!-- Using 'button-content' slot -->
              <template #button-content>
                <b-icon icon="person-fill"></b-icon>
              </template>

              <b-dropdown-item href="#">Meus Dados</b-dropdown-item>
              <b-dropdown-divider></b-dropdown-divider>
              <b-dropdown-item href="#">
                <router-link to='/logout'>Sair</router-link>
              </b-dropdown-item>

            </b-nav-item-dropdown>

          </b-navbar-nav>
          <!-- End of right aligned nav items -->

          <!--
          <b-button variant="light" v-b-toggle:sidebar>
            <b-icon icon="list" scale="1"></b-icon>
          </b-button>
          -->

        </b-collapse>
      </b-navbar>

      <b-container class="p-0 pt-0" fluid>
        <router-view></router-view>
      </b-container>

    </b-container>

    <b-container class="" fluid v-else>
      <router-view></router-view>
    </b-container>

  </div>
</template>

<script>
  import store from '@/store';

  export default {
    beforeCreate() {
      if ( ! store.state.isLogged) {
        this.$router.push('/login')
                .catch(()=>{
                  console.log('Not logged, push login !')
                });
      }
    },
    computed: {
      isLogged() {
        return store.state.isLogged;
      }
    },
    data() {
      return {
        sidebarVisible: false,
      };
    },
    methods: {
      sidebarChangesVisibility(visible) {
        this.$set(this, 'sidebarVisible', visible);
      },
      rightContainerStyle() {
        return {
          'margin-left': (this.sidebarVisible ? '300px' : 0)
        }
      },
      rightContainerClasses() {
        return {
          'w-100': ! this.sidebarVisible,
          'w-75': this.sidebarVisible
        }
      }
    }
  }
</script>

<style>
  .sb-footer {
    background-color: lightgray;
  }
</style>