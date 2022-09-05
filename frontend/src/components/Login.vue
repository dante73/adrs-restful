<template>
  <div id="main-box">

    <b-row>
      <b-col md="4" offset-md="4">
        <form v-on:submit.prevent="login">

          <b-card class="border-success shadow-lg" fluid>

            <b-card-header class="bg-white">
                <b-img :src="require('../assets/logo_white_mini.png')" width="100%" fluid></b-img>
            </b-card-header>

            <b-card-body>

              <b-container fluid>
                <b-form-group
                        id="input-group-1"
                        label="Usuário"
                        label-for="input-1"
                >
                  <b-form-input
                          id="input-1"
                          v-model="form.login"
                          placeholder="Nome de Usuário"
                  ></b-form-input>
                </b-form-group>
              </b-container>

              <b-container fluid>
                <b-form-group
                        id="input-group-2"
                        label="Senha"
                        label-for="input-2"
                >
                  <b-form-input
                          id="input-2"
                          v-model="form.password"
                          placeholder="Senha de Autenticação"
                          type="password"
                  ></b-form-input>
                </b-form-group>
              </b-container>

              <b-container v-if="infoError">
                <strong>Erro:</strong> <font color="red">Dados inválidos.</font>
              </b-container>

            </b-card-body>

            <b-card-footer>
              <b-row>
                <b-col md="12" align="center">
                  <b-button type="submit" variant="primary">Login</b-button>
                </b-col>
              </b-row>
            </b-card-footer>

          </b-card>
        </form>
      </b-col>
    </b-row>

  </div>
</template>

<script>
  import store from '@/store';
  import ls from 'local-storage';
  import axios from 'axios';

  export default {
    name: 'login',
    data() {
      return {
        infoError: false,
        form: {
          login: '',
          password: ''
        }
      };
    },
    beforeCreate() {
      if (store.state.isLogged) {
        this.$router.push('/');
      }
    },
    methods: {
      login() {
        this.infoError = false;
        axios.post(`/api/login`, this.form)
          .then(response => {
            if (response.data.success) {
              ls('token', response.data.token);
              store.commit('LOGIN_USER');
              this.$router.push('/');
            } else {
              this.infoError = true;
            }
          });
      }
    }
  };
</script>

<style>

    #main-box {
      margin-top: 10%;
    }

</style>