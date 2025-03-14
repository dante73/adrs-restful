<template>
  <div>
    <b-container class="p-0 m-0 pl-2 pr-2">
      <b-container fluid="xl">
        <b-row>

          <!-- Avatar -->
          <b-col md="4" class="text-center">
            <b-row class="h-100">
              <b-card-group class="pl-0 pr-2 w-100">
                <b-card no-body>
                  <b-card-header class="small">
                    {{ form.nome || '&nbsp;' }}
                  </b-card-header>
                  <b-card-body>
                    <b-avatar id="avatar-foto" :src="avatar.url" text="Foto" size="144px"></b-avatar>
                  </b-card-body>
                  <b-card-footer class="small">
                    {{ $support.yearsBetween(form.nascimento, new Date()) }} anos de idade
                  </b-card-footer>
                </b-card>
              </b-card-group>
            </b-row>
          </b-col>
          <!-- End of avatar -->

          <b-col md="8" class="p-0 m-0">
            <b-container class="p-0 m-0 bg-light" fluid>
              <b-tabs
                      v-model="tabIndex"
                      nav-class="bg-white rounded text-white font-weight-bold"
                      nav-wrapper-class="danger"
                      card fill pills small>

                <b-tab title="Principal" class="m-0 p-0">
                  <b-card-group>
                    <b-card no-body style="min-height: 240px; border-top: none;">
                      <b-card-body class="p-0 m-0">

                        <!-- Dados principais -->
                        <b-container class="p-0 m-0 px-1 pt-1 h-100" fluid>
                          <b-container class="p-0 m-0 bg-light rounded" style="min-height: 221px;">
                            <b-row class="p-0 px-1 small">
                              <b-col md="4" class="pr-1">
                                <b-form-group
                                        id="input-group-birthday"
                                        class="pt-0"
                                        label="Data de Nascimento"
                                        label-for="input-birthday"
                                        label-class="bg-light text-center p-0 m-0">
                                  <b-form-input
                                          id="input-birthday"
                                          v-model="form.nascimento"
                                          type="date"
                                          :placeholder="$settings.format.date"
                                          :readonly="! showEditingControllers()"
                                          autocomplete="off"
                                          required
                                          size="sm"></b-form-input>
                                </b-form-group>
                              </b-col>
                              <b-col md="8" class="pl-1">
                                <b-form-group
                                        id="radio-group-gender"
                                        class="pt-0"
                                        v-if="showEditingControllers()"
                                        label="Gênero"
                                        label-for="radio-gender"
                                        label-class="bg-light text-center p-0 m-0">
                                  <b-form-radio-group
                                          id="radio-gender"
                                          v-model="form.genero"
                                          :options="$settings.tipos.sexo"
                                          name="radio-btn-gender"
                                          button-variant="outline-primary"
                                          buttons
                                          :readonly="showEditingControllers()"
                                          size="sm"
                                          class="w-100"></b-form-radio-group>
                                </b-form-group>
                                <b-form-group
                                        id="radio-group-gender"
                                        class="pt-0"
                                        v-else
                                        label="Gênero"
                                        label-for="radio-gender"
                                        label-class="bg-light text-center p-0 m-0">
                                <span class="btn btn-sm bg-info text-light w-100">
                                  {{ $support.typeTextByKey($settings.tipos.sexo, form.genero) }}
                                </span>
                                </b-form-group>
                              </b-col>
                            </b-row>

                            <b-row class="p-0 px-1 small">
                              <b-col md="12">
                                <b-form-group
                                        id="input-group-name"
                                        class="pt-0"
                                        label="Nome"
                                        label-for="input-name"
                                        label-class="bg-light text-center p-0 m-0">
                                  <b-form-input
                                          id="input-name"
                                          v-model="form.nome"
                                          :readonly="! showEditingControllers()"
                                          placeholder="Nome da Pessoa"
                                          autocomplete="off"
                                          required
                                          size="sm"></b-form-input>
                                </b-form-group>
                              </b-col>
                            </b-row>

                            <b-row class="p-0 px-1 small" v-if="showEditingControllers()">
                              <b-col md="12">
                                <b-form-group
                                        id="input-group-file"
                                        class="pt-0"
                                        label="Arquivo de Foto"
                                        label-for="input-file"
                                        label-class="bg-light text-center p-0 m-0">
                                  <b-input-group>
                                    <b-form-file
                                            id="input-file"
                                            v-model="form.file"
                                            :state="Boolean(form.file) && validateImage()"
                                            placeholder="Escolha um arquivo de foto..."
                                            drop-placeholder="Solte o arquivo aqui..."
                                            size="sm"></b-form-file>
                                    <b-input-group-append>
                                      <b-button size="sm" @click="postImage()">Enviar</b-button>
                                    </b-input-group-append>
                                  </b-input-group>
                                </b-form-group>
                              </b-col>
                            </b-row>

                          </b-container>

                          <b-container class="p-0 m-0 pt-1">
                            <!-- Toolbar -->
                            <b-row class="p-0 pb-1 small" v-if="showEditingControllers() || localState === $support.st.DELETING">
                              <b-col md="12">
                                <b-card-group class="shadow-lg">
                                  <b-card no-body class="bg-light">

                                    <b-card-body v-if='localState === $support.st.CREATING' class="p-1 m-1 bg-light">
                                      <b-row>
                                        <b-col md="8">
                                          <b-button
                                                  @click="saveFormData"
                                                  variant="outline-danger"
                                                  size="sm"
                                                  :disabled="! hasChanged"
                                          >Gravar o Novo Registro</b-button>
                                        </b-col>
                                        <b-col md="4" class="text-right">
                                          <CloseButtom size="sm" caption="Abandonar" />
                                        </b-col>
                                      </b-row>
                                    </b-card-body>

                                    <b-card-body v-else-if='localState === $support.st.UPDATING' class="p-1 m-1 bg-light">
                                      <b-row>
                                        <b-col md="8">
                                          <b-button
                                                  @click="saveFormData"
                                                  variant="outline-danger"
                                                  size="sm"
                                                  :disabled="! hasChanged"
                                          >Gravar Alterações</b-button>
                                        </b-col>
                                        <b-col md="4" class="text-right">
                                          <CloseButtom size="sm" caption="Abandonar" />
                                        </b-col>
                                      </b-row>
                                    </b-card-body>

                                    <b-card-body v-else-if='localState === $support.st.DELETING' class="p-1 m-1 bg-light">
                                      <b-row>
                                        <b-col md="6">
                                          <b-button
                                                  @click="deleteFormData"
                                                  variant="outline-danger"
                                                  size="sm">Apagar</b-button>
                                        </b-col>
                                        <b-col md="6" class="text-right">
                                          <CloseButtom size="sm" caption="Abandonar Operação" />
                                        </b-col>
                                      </b-row>
                                    </b-card-body>

                                    <b-card-body v-else class="p-1 m-1 bg-light">
                                      <b-row>
                                        <b-col md="12" class="text-right">
                                          <CloseButtom size="sm" />
                                        </b-col>
                                      </b-row>
                                    </b-card-body>

                                  </b-card>
                                </b-card-group>
                              </b-col>
                            </b-row>
                            <!-- End of Toolbar -->

                            <!--
                            Bloco só para efeito estético.
                            Estava dando diferença na borda dentro do tab principal, que encolhia quando o "v-if" acima
                            é verdadeiro.
                            -->
                            <b-row v-else>
                              <b-col md="1" class="p-0 m-0 pt-2 pl-1">
                                <b-container>
                                  <b-row>&nbsp;</b-row>
                                </b-container>
                              </b-col>
                            </b-row>
                            <!-- End -->

                          </b-container>

                        </b-container>
                        <!-- End of Dados Principais -->

                      </b-card-body>
                    </b-card>
                  </b-card-group>
                </b-tab>

                <!-- Contatos component -->
                <b-tab title="Contatos" :disabled="localState === $support.st.CREATING" class="m-0 p-0">
                  <b-card-group>
                    <b-card no-body style="min-height: 240px; border-top: none;">
                      <b-card-body class="p-0 m-0">
                        <Contatos :pessoaId="form.id" :pessoaState="localState" />
                      </b-card-body>
                    </b-card>
                  </b-card-group>
                </b-tab>
                <!-- End of contatos component -->

                <!-- Documentos component -->
                <b-tab title="Documentação" :disabled="localState === $support.st.CREATING" class="m-0 p-0">
                  <b-card-group>
                    <b-card no-body style="min-height: 240px; border-top: none;">
                      <b-card-body class="p-0 m-0">
                        <Documentos :pessoaId="form.id" :pessoaState="localState" />
                      </b-card-body>
                    </b-card>
                  </b-card-group>
                </b-tab>
                <!-- End of documentos component -->

                <!-- Enderecos component -->
                <b-tab title="Endereço" :disabled="localState === $support.st.CREATING" class="m-0 p-0">
                  <b-card-group>
                    <b-card no-body style="min-height: 240px; border-top: none;">
                      <b-card-body class="p-0 m-0">
                        <Enderecos :pessoaId="form.id" :pessoaState="localState" />
                      </b-card-body>
                    </b-card>
                  </b-card-group>
                </b-tab>
                <!-- End of enderecos component -->

                <!-- Acessos component -->
                <b-tab title="Acesso" :disabled="localState === $support.st.CREATING" class="m-0 p-0">
                  <b-card-group>
                    <b-card no-body style="min-height: 240px; border-top: none;">
                      <b-card-body class="p-0 m-0">
                        <Acessos :pessoaId="form.id" :pessoaState="localState" />
                      </b-card-body>
                    </b-card>
                  </b-card-group>
                </b-tab>
                <!-- End of acessos component -->

              </b-tabs>
            </b-container>
          </b-col>

        </b-row>
      </b-container>
    </b-container>
  </div>
</template>

<script>
  import axios from 'axios';

  import Acessos from './acessos/Index';
  import Contatos from './contatos/Index';
  import Documentos from './documentos/Index';
  import Enderecos from './enderecos/Index';

  import CloseButtom from "@/components/app/frontpage/CloseButtom";

  export default {
    name: 'Index',
    components: {
      Acessos,
      Contatos,
      Documentos,
      Enderecos,
      CloseButtom
    },
    data() {
      return {
        title: "Cadastro de Pessoas",
        model: 'pessoa',
        form: this.pessoaObj,
        saveForm: Object.assign({}, this.pessoaObj),
        localState: this.state,
        avatar: {
          url: '',
          src: ''
        },
        tabIndex: (this.state === this.$support.st.CREATING || this.state === this.$support.st.DELETING ? 0 : 1)
      }
    },
    props: {
      pessoaObj: {
        type: Object,
        required: false
      },
      state: {
        type: Number,
        required: false
      }
    },
    mounted() {
      /**
       * Carrega dados do avatar quando abre o modal
       */
      if (this.pessoaObj.imagem) {
        this.$set(this.avatar, 'src', this.pessoaObj.imagem);
        this.$set(this.avatar, 'url', this.getImgUrl());
      }
    },
    methods: {
      async saveFormData() {
        // Valida o formulário
        let v = this.validate();

        // Só posta se estiver tudo ok
        if (v) {
          let st = this.$support.st;
          let r;

          // Faz o envio para o backend
          if (this.localState === st.UPDATING) {
            let id = this.form.id;

            r = await this.$http.put(this.model + '/' + id, this.form);
          }
          else if (this.localState === st.CREATING) {
            r = await this.$http.post(this.model, this.form);
          }

          if (r && r.status && r.status === 200) {
            this.$bvToast.toast(r.data.message, {
              title: this.title,
              autoHideDelay: this.$settings.config.toastErrorTimeout,
              appendToast: true,
              variant: 'success'
            });

            if (this.localState === st.UPDATING) {
              this.$emit('get-all-function');

              this.$root.$emit('bv::hide::modal', 'modal-form-1');
            }
            else if (this.localState === st.CREATING) {
              // Retorna o registro criado na base de dados.
              let returnedNewRecord = r.data.data.data;

              // Atribuí o elemento gravado na rest e retornado ao Form.
              this.$emit('update:pessoaObj', returnedNewRecord);
              this.$set(this, 'form', returnedNewRecord);

              // Mantém a janela aberta e a operação passa a ser de edição
              await this.$set(this, 'localState', st.UPDATING);

              // Muda para tab de contatos
              this.$set(this, 'tabIndex', 1);

              // Atualiza tabela com dados criados
              this.$emit('get-all-function');
            }
          }
          else {
            this.$bvToast.toast(r.data, {
              title: this.title,
              autoHideDelay: this.$settings.config.toastErrorTimeout,
              appendToast: true,
              variant: 'danger'
            });
          }
        }
      },
      async deleteFormData() {
        // Faz o delete para o backend
        let id = this.form.id;
        let r = await this.$http.delete(this.model + '/' + id, this.form);

        if (r && r.status && r.status === 200) {

          if (r.data.status === 'error') {
            this.$bvToast.toast(r.data.data, {
              title: this.title,
              autoHideDelay: this.$settings.config.toastErrorTimeout,
              appendToast: true,
              variant: 'danger'
            });
          }
          else {
            this.$bvToast.toast(r.data.data.text, {
              title: this.title,
              autoHideDelay: this.$settings.config.toastErrorTimeout,
              appendToast: true,
              variant: 'success'
            });
          }
        }
        else {
          this.$bvToast.toast(r.data, {
            title: this.title,
            autoHideDelay: this.$settings.config.toastErrorTimeout,
            appendToast: true,
            variant: 'danger'
          });
        }

        this.$emit('get-all-function');
        this.$root.$emit('bv::hide::modal', 'modal-form-1');
      },
      dismissFormData() {
        this.$emit('get-all-function');
        this.$root.$emit('bv::hide::modal', 'modal-form-1');
      },
      onReset() {
        this.$root.$emit('bv::hide::modal', 'modal-form-1');
      },
      validate() {
        if ( ! this.form.nascimento || this.form.nascimento.empty) {
          this.$bvToast.toast("Informe a data de nascimento da pessoa !", {
            title: this.title,
            autoHideDelay: this.$settings.config.toastErrorTimeout,
            appendToast: true,
            variant: 'danger'
          });

          document.getElementById('input-birthday').focus();

          return false;
        }

        if ( ! this.form.genero || this.form.genero.empty) {
          this.$bvToast.toast("Informe o gênero da pessoa !", {
            title: this.title,
            autoHideDelay: this.$settings.config.toastErrorTimeout,
            appendToast: true,
            variant: 'danger'
          });

          document.getElementById('radio-gender').focus();

          return false;
        }

        if ( ! this.form.nome || this.form.nome.empty) {
          this.$bvToast.toast("Informe o nome da pessoa !", {
            title: this.title,
            autoHideDelay: this.$settings.config.toastErrorTimeout,
            appendToast: true,
            variant: 'danger'
          });

          document.getElementById('input-name').focus();

          return false;
        }

        /**
         * Se tiver sido feito o upload de imagem, atribui valor ao form para posterior gravação no banco.
         */
        if (this.avatar.src.length > 0) {
          this.form.imagem = this.avatar.src;
        }

        return true;
      },
      validateImage() {
        /**
         * Verifica se a imagem foi definida
         * @type {HTMLElement}
         */
        let image = document.getElementById('input-file');

        if ( ! image) {
          return false;
        }

        /**
         * Verifica se o tamanho da imagem está dentro do permitido
         */
        let max = this.$settings.config.maxImageSize;
        let size = image.files[0].size;

        if (size > max) {
          this.$bvToast.toast(
                  "Imagem escolhida é muito grande!\n\nO tamanho máximo permitido é de \"" + max + "\"",
                  {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          return false;
        }

        /**
         * Estando tudo ok, retorna positivo
         */
        return true;
      },
      postImage() {
        /**
         * Envia a imagem usando o axios
         */
        if (this.validateImage()) {
          let image = document.getElementById('input-file');

          /**
           * Monta um formulário para envio
           *
           * @type {FormData}
           */
          let fd = new FormData();

          /**
           * Anexa a imagem ao formulário
           */
          fd.append("imagem", image.files[0]);

          /**
           * Envia por POST e multipart/form-data
           */
          axios.post(this.model, fd, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }).then(response => {
            /**
             * Sucesso no envio
             */
            if (response.status === 200) {
              let srci = response.data.filename;

              this.$set(this.avatar, 'src', srci);
              this.$set(this.avatar, 'url', this.$settings.restapi + 'assets/' + this.model + '/' + srci);
            }
            else {
              /**
               * Falhou
               */
              this.$bvToast.toast(response.data, {
                title: this.title,
                autoHideDelay: this.$settings.config.toastErrorTimeout,
                appendToast: true,
                variant: 'danger'
              });
            }
          }, (reason => {
            console.log(reason);
          }));
        }
      },
      showEditingControllers() {
        let st = this.$support.st;
        return (this.localState === st.CREATING || this.localState === st.UPDATING);
      },
      getImgUrl() {
        return this.$settings.restapi + 'assets/' + this.model + '/' + this.pessoaObj.imagem;
      }
    },
    computed: {
      hasChanged: function() {
        return Object.keys(this.form).some(field => this.form[field] !== this.saveForm[field]);
      }
    },
    watch: {
      pessoaObj: function () {
        this.$set(this, 'form', this.pessoaObj);
      },
      state: function () {
        this.$set(this, 'localState', this.state);
      }
    }
  }
</script>