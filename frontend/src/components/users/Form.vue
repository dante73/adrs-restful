<template>
    <div class="container">

        <h3 class='subtitle'>{{ titulo }}</h3>

        <div v-if="localState == 'editando' || localState == 'criando'">

          <div class="field">
            <label class="label">Nome</label>
            <div class="control">
              <input class="input" type="text" placeholder="Nome do Usuário" v-model.trim="form.nome" />
            </div>
          </div>

          <div class="field">
            <label class="label">Email (Login)</label>
            <div class="control has-icons-left has-icons-right">
              <input class="input" type="email" placeholder="e.g. pedro@gmail.com" v-model.trim="form.login" />
              <span class="icon is-small is-left">
                <i class="fas fa-envelope"></i>
              </span>
              <span class="icon is-small is-right">
                <i class="fas fa-exclamation-triangle"></i>
              </span>
            </div>
          </div>

          <div class="field">
            <label class="label">Senha</label>
            <div class="control has-icons-left">
              <input class="input" type="password" placeholder="**********" v-model="form.senha" />
              <span class="icon is-small is-left">
                <i class="fa fa-lock"></i>
              </span>
            </div>
          </div>

          <div class="field is-grouped">
            <div class="control">
              <button class="button is-danger" @click="abandonar">Abandonar</button>
            </div>
            <div class="control">
              <button class="button is-link is-light" @click="gravar">Gravar</button>
            </div>
          </div>

        </div>

        <div v-else-if="localState == 'selecionado' || localState == 'apagando'">

          <div class="field">
            <label class="label">Nome</label>
            <div class="control">
                <span>{{ userObj.nome }}</span>
            </div>
          </div>

          <div class="field">
            <label class="label">Login</label>
            <div class="control">
                <span>{{ userObj.login }}</span>
            </div>
          </div>

          <div v-show="localState == 'selecionado'">

            <div class="field is-grouped">
              <div class="control">
                <button class="button is-primary" @click="criar">Criar</button>
              </div>
              <div class="control">
                <button class="button is-link" @click="editar">Editar</button>
              </div>
              <div class="control">
                <button class="button is-danger" @click="apagar">Apagar</button>
              </div>
            </div>

          </div>

          <div v-show="localState == 'apagando'">

            <div class="field is-grouped">
              <div class="control">
                <button class="button is-danger" @click="abandonar">Abandonar</button>
              </div>
              <div class="control">
                <button class="button is-link" @click="gravar">Confirmar Exclusão</button>
              </div>
            </div>

          </div>

        </div>

        <div v-else-if="! selecionado()">

            <div class="control">
              <button class="button is-primary" @click="criar">Criar</button>
            </div>

        </div>

    </div>
</template>

<script>

import axios from 'axios'
import moment from 'moment'

axios.defaults.baseURL = 'http://localhost:5002/'

export default {
    data() {
        return {
            form: this.user,
            localState: this.state,
            descricao: "Detalhes do Usuário",
        }
    },
    props: {
        userObj: {
            type: Object,
            required: true
        },
        state: {
            type: String,
            required: true
        }
    },
    computed: {
        titulo() {
            let s;
            switch (this.localState) {
                case 'selecionado':
                    s = 'Detalhes do '; break;
                case 'criando':
                    s = 'Criando um novo '; break;
                case 'editando':
                    s = 'Editando dados do '; break;
                case 'apagando':
                    s = 'Apagando dados do '; break;
            }
            s = s + 'Usuário';
            return s;
        },
        selecionado() {
          return this.localState === 'selecionado';
        }
    },
    methods: {
        criar() {
            this.localState = 'criando';
            this.form = Object.assign({}, {});
        },
        editar() {
            this.localState = 'editando';
            this.form = Object.assign({}, this.userObj);
        },
        apagar() {
            this.localState = 'apagando';
            this.form = Object.assign({}, this.userObj);
        },
        abandonar() {
            this.localState = 'selecionado';
            this.form = this.animal;
        },
        async gravar() {
            let r;
            if (this.localState == 'editando') {
                r = await axios.post('/users/save', { user: this.form });
            } else if (this.localState == 'apagando') {
                r = await axios.post('/users/del', { user: this.form });
            } else if (this.localState == 'criando') {
                r = await axios.post('/users/add', { user: this.form });
            }
            this.localState = 'selecionado';
            this.$toasted.show(r.data.message);
            this.$emit('getAll', this.form);
        },
        frontEndDateFormat(data) {
            return moment(data).format("DD/MM/YYYY");
        }
    }
}
</script>
