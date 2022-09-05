<template>
  <div class="container">
    <div class="panel">
      <div class='panel-heading'>{{ titulo }}</div>
      <div class="panel-block">
        <div class="container">

          <div v-if="localState == 'editando' || localState == 'criando'">

            <div class="columns is-full">
              <div class="column is-5">
                <div class="field">
                  <label class="label">Pessoa</label>
                  <div class="control">
                    <label for="" class="radio">
                      <input type="radio" v-model="form.pessoaFJ" name="pessoaFJ" value="0" selected> Física
                    </label>
                    <label for="" class="radio">
                      <input type="radio" v-model="form.pessoaFJ" name="pessoaFJ" value="1"> Jurídica
                    </label>
                  </div>
                </div>
              </div>
              <div class="column is-7" v-if="form.pessoaFJ == 0">
                <div class="field is-pulled-right">
                  <label class="label">Gênero</label>
                  <div class="control">
                    <label for="" class="radio">
                      <input type="radio" v-model="form.genero" name="genero" value="0" selected> Masculino
                    </label>
                    <label for="" class="radio">
                      <input type="radio" v-model="form.genero" name="genero" value="1"> Feminino
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label" v-if="form.pessoaFJ == 0">Nome Completo</label>
              <label class="label" v-else-if="form.pessoaFJ == 1">Razão Social</label>
              <div class="control">
                <input class="input" v-if="form.pessoaFJ == 0" type="text" placeholder="Nome completo da pessoa" v-model.trim="form.nome" />
                <input class="input" v-else-if="form.pessoaFJ == 1" type="text" placeholder="Razão Social" v-model.trim="form.nome" />
              </div>
            </div>

            <div class="columns is-full">
              <div class="column is-5">
                <div class="field">
                  <label class="label" v-if="form.pessoaFJ == 0">Data de Nascimento</label>
                  <label class="label" v-else-if="form.pessoaFJ == 1">Data de Abertura</label>
                  <div class="control">
                    <b-datepicker
                            v-model="form.nascimento"
                            :show-week-number="showWeekNumber"
                            locale="pt-BR"
                            placeholder="Clique para selecionar data..."
                            icon="calendar-today"
                            trap-focus>
                    </b-datepicker>
                  </div>
                </div>
              </div>
              <div class="column is-7">
                <div class="field">
                  <label class="label" v-if="form.pessoaFJ == 0">CPF</label>
                  <label class="label" v-else-if="form.pessoaFJ == 1">CNPJ</label>
                  <div class="control">
                    <input class="input" v-if="form.pessoaFJ == 0" type="text"
                           placeholder="Cadastro Pessoa Física" v-mask="$config.maskCPF" v-model.trim="form.codigoPessoa" />
                    <input class="input" v-else-if="form.pessoaFJ == 1" type="text"
                           placeholder="Cadastro Nacional Pessoa Jurídica" v-mask="$config.maskCNPJ" v-model.trim="form.codigoPessoa" />
                  </div>
                </div>
              </div>
            </div>

            <div class="field">
              <div class="columns is-full">
                <div class="column is-half">
                  <div class="control">
                    <button class="button is-danger" @click="abandonar">Abandonar</button>
                  </div>
                </div>
                <div class="column is-half">
                  <div class="control">
                    <button class="button is-pulled-right is-link is-light" @click="gravar">Gravar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="localState == 'selecionado' || localState == 'apagando'">

            <div class="columns is-full">
              <div class="column is-5">
                <div class="tag is-medium is-info">
                  <span v-if="form.pessoaFJ == 0">Pessoa Física</span>
                  <span v-else-if="form.pessoaFJ == 1">Pessoa Jurídica</span>
                </div>
              </div>
              <div class="column is-7" v-if="form.pessoaFJ == 0">
                <div class="field is-pulled-right">
                  <div class="tag is-medium is-info">
                    <span v-if="form.genero == 0">Gênero Masculino</span>
                    <span v-else-if="form.genero == 1">Gênero Feminino</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label" v-if="form.pessoaFJ == 0">Nome Completo</label>
              <label class="label" v-else-if="form.pessoaFJ == 1">Razão Social</label>
              <div class="tag is-medium">
                  <span>{{ form.nome }}</span>
              </div>
            </div>

            <div class="columns is-full">
              <div class="column is-5">
                <div class="field">
                  <label class="label" v-if="form.pessoaFJ == 0">Data de Nascimento</label>
                  <label class="label" v-else-if="form.pessoaFJ == 1">Data de Abertura</label>
                  <div class="tag is-medium">
                    <span>{{ moment(form.nascimento).format($config.formatDate) }}</span>
                  </div>
                </div>
              </div>
              <div class="column is-7">
                <div class="field">
                  <label class="label" v-if="form.pessoaFJ == 0">CPF</label>
                  <label class="label" v-else-if="form.pessoaFJ == 1">CNPJ</label>
                  <div class="tag is-medium">
                    <span v-if="form.pessoaFJ == 0">{{ $adrs_f.formatCPF(form.codigoPessoa.toString()) }}</span>
                    <span v-else-if="form.pessoaFJ == 1">{{ $adrs_f.formatCNPJ(form.codigoPessoa.toString()) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="tabs is-centered is-boxed is-small">
              <ul>
                <li class="is-active">
                  <a>
                    <span class="icon is-small"><i class="fas fa-user-friends" aria-hidden="true"></i></span>
                    <span>Contato</span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="icon is-small"><i class="fas fa-building" aria-hidden="true"></i></span>
                    <span>Endereço</span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="icon is-small"><i class="fas fa-sign-in-alt" aria-hidden="true"></i></span>
                    <span>Login</span>
                  </a>
                </li>
                <li>
                  <a>
                    <span class="icon is-small"><i class="far fa-file-alt" aria-hidden="true"></i></span>
                    <span>Documents</span>
                  </a>
                </li>
              </ul>
            </div>

            <div v-show="localState == 'selecionado'">
              <div class="field">
                <div class="columns is-full">
                  <div class="column is-half">
                    <button class="button is-primary" @click="criar">Criar Novo</button>
                  </div>
                  <div class="column is-half">
                    <div class="buttons is-pulled-right">
                      <button class="button is-link" @click="editar">Editar</button>
                      <button class="button is-danger" @click="apagar">Apagar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-show="localState == 'apagando'">
              <div class="field">
                <div class="columns is-full">
                  <div class="column is-half">
                    <div class="control">
                      <button class="button is-danger" @click="abandonar">Abandonar</button>
                    </div>
                  </div>
                  <div class="column is-half">
                    <div class="control">
                      <button class="button is-pulled-right is-danger is-outlined" @click="gravar">Confirmar Exclusão</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div v-else>

              <div class="control">
                <button class="button is-primary" @click="criar">Criar Novo</button>
              </div>

          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>

const
  emptyForm = {
    nome: '',
    pessoaFJ: 0,
    codigoPessoa: 0,
    genero: 0,
    nascimento: new Date()
  };

export default {
    data() {
      return {
          form: this.pessoaObj,
          localState: this.state,
          descricao: "Detalhes da Pessoa",
      }
    },
    props: {
        pessoaObj: {
            type: Object,
            required: false
        },
        state: {
            type: String,
            required: false
        }
    },
    computed: {
        titulo() {
            let s;
            if ( ! this.form) {
              s = 'Cadastro de Pessoas';
            }
            else {
              switch (this.localState) {
                  case 'selecionado':
                      s = 'Detalhes da '; break;
                  case 'criando':
                      s = 'Criando uma nova '; break;
                  case 'editando':
                      s = 'Editando dados da '; break;
                  case 'apagando':
                      s = 'Apagando dados da '; break;
              }
              s = s + 'Pessoa';
            }
            return s;
        },
        selecionado() {
          return this.localState === 'selecionado';
        },
        showWeekNumber() {
          return true;
        }
    },
    mounted() {
      //this.$toasted.show("", {type: 'success', duration: 2000});
    },
    methods: {
        criar() {
            this.$set(this, 'localState', 'criando');
            this.$set(this, 'form', Object.assign({}, emptyForm));
        },
        editar() {
            this.$set(this, 'localState', 'editando');
            this.$set(this, 'form', Object.assign({}, this.pessoaObj));
        },
        apagar() {
            this.$set(this, 'localState', 'apagando');
            this.$set(this, 'form', Object.assign({}, this.pessoaObj));
        },
        abandonar() {
          this.$set(this, 'localState', 'selecionado');
          this.$set(this, 'form', this.pessoaObj);
        },
        async gravar() {
            let r;
            // Limpa o campo cpf/cnpj de pontos, traços e barra.
            this.$set(this.form, 'codigoPessoa',
                    this.form.codigoPessoa.replace(/[./\-]/g, ''));
            // Faz o post para o backend
            if (this.localState == 'editando') {
                r = await this.$http.post('/pessoa/save', { pessoa: this.form });
            }
            else if (this.localState == 'apagando') {
                r = await this.$http.post('/pessoa/del', { pessoa: this.form });
            }
            else if (this.localState == 'criando') {
                r = await this.$http.post('/pessoa/add', { pessoa: this.form });
            }
            this.$set(this, 'localState', 'selecionado');
            this.$toasted.show(r.data.message);
            this.$emit('getAll', this.form);
        }
    },
    watch: {
        pessoaObj: function () {
            this.$set(this, 'form', this.pessoaObj);
            this.$set(this.form, 'nascimento',
                    new Date(Date.parse(this.form.nascimento)));
        },
        state: function () {
            this.$set(this, 'localState', this.state);
        }
    }
}
</script>