<template>
  <div class="container">
    <div class="columns">
      <div class="column is-5">
        <div class="panel" style="width:100%">

          <div class="container" v-if="pessoas.length > 0">
            <div class="panel-heading">Lista de Pessoas Cadastradas</div>
            <div class="panel-block">
              <div class="container">

                <div class='columns' :key="pessoa._id" v-for="pessoa in pessoas">
                  <div class='column is-half'>
                    <span class="is-small">{{ pessoa.nome }}</span>
                  </div>
                  <div class='column'>
                    <div class="control">
                      <button class="button is-pulled-right is-primary is-outlined is-small" @click="detalhes(pessoa)">
                        Ver detalhes
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="container" v-else-if="carregando">
            <h3 class='subtitle'>Lista de Pessoas</h3>
            <article>
              Reunindo todas as pessoas cadastradas em uma lista, por favor aguarde...
            </article>
          </div>

          <div class="container" v-else-if="pessoas.length === 0">
            <article>
              <h3 class='subtitle'>Lista de Pessoas</h3>
              <p>Não há registros na base de dados.</p>
            </article>
          </div>

        </div>
      </div>

      <div class="column is-7">
        <PessoaForm @getAll="getAll" :state="state" :pessoaObj="selecionado" />
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios';
import PessoaForm from './Form.vue';

axios.defaults.baseURL = 'http://localhost:5002/';

export default {
    components: {
        PessoaForm
    },
    data() {
        return {
            pessoas: [],
            state: '',
            selecionado: undefined,
            carregando: true
        } 
    },
    mounted() {
        this.getAll()
    },
    methods: {
        async getAll(pessoa) {
            let r1 = await this.$http.get('pessoa/all');
            this.$set(this, 'pessoas', r1.data);
            this.$set(this, 'carregando', false);
            //
            if (pessoa) {
                this.detalhes(pessoa)
            }
        },
        voltar() {
            if (this.$route.back) {
                this.$route.back();
            }
        },
        detalhes(pessoa) {
          this.$set(this, 'selecionado', pessoa);
          this.$set(this, 'state', 'selecionado');
        },
        criar() {
          this.$set(this, 'state', 'criando');
          this.$set(this, 'selecionado', Object.assign({}, {}));
        },
        editar() {
          this.$set(this, 'state', 'editando');
        },
        apagar() {
          this.$set(this, 'state', 'apagando');
        }
    }
};
</script>