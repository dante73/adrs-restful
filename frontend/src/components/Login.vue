<template>
  <b-container fluid id="main-box">

    <b-row>
      <b-col md="4" offset-md="4">

        <form v-on:submit.prevent="login">

          <b-card class="border-success shadow-lg" fluid>
            <b-card-header class="bg-white">
                <b-img :src="require('../assets/img/logo_white_mini.png')" width="100%" fluid></b-img>
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
                <strong>Erro:</strong> <font color="red">{{ message }}</font>
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

  </b-container>
</template>

<script>
  import store from '@/store';
  import ls from 'local-storage';
  import AES from 'crypto-js/aes';

  export default {
    name: 'login',
    data() {
      return {
        infoError: false,
        message: '',
        form: {
          login: '',
          encrypted: '',
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
      async login() {
        /* Hide message, if visible */
        if (this.infoError) {
          this.$set(this, 'infoError', false);
        }

        let p = this.form.password;
        let mk = this.$settings.magicKey;

        /* Encrypt login and password before send */
        this.$set(this.form,'encrypted', AES.encrypt(p, mk).toString());

        console.log(this.form.encrypted);

        console.log(AES.decrypt(this.form.encrypted, mk).toString());

        this.$set(this.form,'encrypted', 'U2FsdGVkX1/v/Ky046eypLI7ttdn71AlevxQKKSr6SA=');

        /* Authenticate using backend api */
        let response = await this.$http.post('acesso/authenticate', this.form);

        if (response.data.status === 'success' && response.data.data && response.data.data.authenticated) {
          this.$set(this, 'infoError', true);
          this.$set(this, 'message', 'Autenticado');

          /**
           *  Armazena o token em local-storage.
           *  O ls atua como "setter" quando o segundo valor é informado.
           */
          ls('token', response.data.data.token);

          console.log(response.data.data.token);

          /* Chama a mutation em store (através do commit ?) */
          store.commit('LOGIN_USER');

          /* Desvia para raiz */
          this.$router.push('/');
        }
        else if (response.data.status === 'error') {
          /* Em caso de erro, mostra a mensagem retornada */
          this.$set(this, 'infoError', true);
          this.$set(this, 'message', response.data.data);
        }
      }
    }
  };
</script>

<style>

    #main-box {
      margin-top: 10%;
    }

</style>