<template>
  <div class="container">

      <div class="columns">
        <div class="column">&nbsp;</div>
        <div class="column">
          <div class="container">
            
            <section class="hero is-halfheight">

              <div class="hero-head">&nbsp;</div>

              <div class="hero-body">

                  <form class="box" v-on:submit.prevent="logar">

                    <div class="field">
                      <img src="@/assets/logo_white_mini.png" width="102">
                    </div>

                    <div class="field">
                      <label class="label">Usuário</label>
                      <div class="control has-icons-left">
                        <input v-model="form.login" type="email" class="input" placeholder="e.g. pedro@gmail.com" required autofocus>
                        <span class="icon is-small is-left">
                          <i class="fa fa-envelope"></i>
                        </span>
                      </div>
                    </div>

                    <div class="field">
                      <label class="label">Senha</label>
                      <div class="control has-icons-left">
                        <input v-model="form.password" type="password" class="input" placeholder="**********" required>
                        <span class="icon is-small is-left">
                          <i class="fa fa-lock"></i>
                        </span>
                      </div>
                    </div>

                    <div class="field">
                      <div class="columns">
                        <div class="column">&nbsp;</div>
                        <div class="column has-text-centered">
                          <button class="button">
                            Login
                          </button>
                        </div>
                        <div class="column">&nbsp;</div>
                      </div>
                    </div>

                    <div class="field" v-if="infoError">
                      <strong>Erro:</strong> <font color="red">Dados inválidos.</font>
                    </div>

                  </form>

                </div>

              <div class="hero-foot">&nbsp;</div>
              
            </section>

          </div>
        </div>
        <div class="column">&nbsp;</div>
      </div>

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
    logar() {
      this.infoError = false;

        //.post(`${process.env.API_ENV}/api/login`, this.form)
      axios
        .post(`/api/login`, this.form)
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

.box {
  width: 400px;
}
  
</style>
