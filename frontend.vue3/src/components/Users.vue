<template>

    <div class="container">
        <div class="columns">

            <div class="column is-half">

                <h3 class='subtitle'>Lista de Usuários</h3>

                <div class="container" v-if="users.length > 0">

                    <div class='columns' :key="users._id" v-for="user in users">
                      <div class='column is-half'>
                          <span>{{ user.nome }}</span>
                      </div>
                      <div class='column'>
                        <div class="control">
                          <button class="button is-primary" @click="detalhes(user)">Detalhes</button>
                        </div>
                      </div>
                    </div>

                </div>
                <p v-else>Reunindo todos os usuários em uma lista...</p>

            </div>

            <div class="column" v-if="state == 'selecionado'">
                <UserForm @getAll="getAll" :state="state" :userObj="selecionado" />
            </div>

        </div>
    </div>

</template>

<script>

import axios from 'axios'

import UserForm from './users/Form.vue'

axios.defaults.baseURL = 'http://localhost:5002/'

export default {
    components: {
        UserForm
    },
    data() {
        return {
            users: [],
            state: '',
            selecionado: undefined
        } 
    },
    mounted() {
        this.getAll()
    },
    methods: {
        async getAll(user) {
            let r1 = await axios.get('users/all');
            this.users = r1.data;
            //
            if (user) {
                this.detalhes(user)
            }
        },
        voltar() {
            if (this.$route.back) {
                this.$route.back();
            }
        },
        detalhes(user) {
            this.state = 'selecionado';
            this.selecionado = user;
        },
        criar() {
            this.state = 'criando';
            this.selecionado = Object.assign({}, {});
        },
        editar() {
            this.state = 'editando';
        },
        apagar() {
            this.state = 'apagando';
        }
    }
}
</script>
