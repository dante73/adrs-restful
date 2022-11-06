<template>
  <div>
    <b-container class="p-0 m-0 pl-2 pr-2">
      <b-container fluid>
        <b-row>

          <!-- Avatar -->
          <b-col md="4" class="text-center">
            <b-row class="h-100">
              <b-card-group class="pl-0 pr-2 w-100">
                <b-card no-body>
                  <b-card-header>
                    <small>{{ form.nome || '&nbsp;' }}</small>
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
            <b-container fluid class="p-0 m-0">

              <b-tabs small fill>

                <b-tab title="Principal">
                  <b-card-group>
                    <b-card no-body style="border-top: none;">
                      <b-card-body class="p-0 m-0">

                        <b-container fluid class="p-0 m-0" style="height: 210px;">

                          <b-row class="p-0 pl-1 pt-1 small">
                            <b-col md="4">
                              <b-form-group
                                      id="input-group-birthday"
                                      class="pt-0"
                                      label="Data de Nascimento"
                                      label-for="input-birthday"
                                      label-class="bg-light text-center p-0 m-0"
                              >
                                <b-form-input
                                        id="input-birthday"
                                        v-model="form.nascimento"
                                        type="date"
                                        :placeholder="$settings.format.date"
                                        :readonly="! showEditingControllers()"
                                        autocomplete="off"
                                        required
                                        size="sm"
                                ></b-form-input>
                              </b-form-group>
                            </b-col>
                            <b-col md="4">
                              <b-form-group
                                      id="radio-group-gender"
                                      class="pt-0"
                                      v-if="showEditingControllers()"
                                      label="Gênero"
                                      label-for="radio-gender"
                                      label-class="bg-light text-center p-0 m-0"
                              >
                                <b-form-radio-group
                                        id="radio-gender"
                                        v-model="form.genero"
                                        :options="$settings.tipos.sexo"
                                        name="radio-btn-gender"
                                        button-variant="outline-primary"
                                        buttons
                                        :readonly="showEditingControllers()"
                                        size="sm"
                                        class="w-100"
                                ></b-form-radio-group>
                              </b-form-group>
                              <b-form-group
                                      id="radio-group-gender"
                                      class="pt-0"
                                      v-else
                                      label="Gênero"
                                      label-for="radio-gender"
                                      label-class="bg-light text-center p-0 m-0"
                              >
                                <span class="btn btn-sm bg-info text-light w-100">
                                  {{ form.genero === 'M' ? 'Masculino' : 'Feminino'}}
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
                                      label-class="bg-light text-center p-0 m-0"
                              >
                                <b-form-input
                                        id="input-name"
                                        v-model="form.nome"
                                        :readonly="! showEditingControllers()"
                                        placeholder="Nome da Pessoa"
                                        required
                                        size="sm"
                                ></b-form-input>
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
                                          size="sm"
                                  ></b-form-file>
                                  <b-input-group-append>
                                    <b-button size="sm" @click="postImage()">Upload</b-button>
                                  </b-input-group-append>
                                </b-input-group>
                              </b-form-group>
                            </b-col>
                          </b-row>

                        </b-container>

                        <!-- Toolbar -->
                        <b-row class="p-1 pt-3 small" v-if="showEditingControllers()">
                          <b-col md="12">
                            <b-card-group class="shadow-lg">
                              <b-card no-body class="bg-light">

                                <b-card-body v-if='localState==="criando"' class="p-1 m-1 bg-light">
                                  <b-row>
                                    <b-col md="8">
                                      <b-button
                                              @click="saveFormData"
                                              variant="outline-danger"
                                              size="sm"
                                      >Gravar o Novo Registro</b-button>
                                    </b-col>
                                    <b-col md="4" align="right">
                                      <CloseButtom size="sm" caption="Abandonar" />
                                    </b-col>
                                  </b-row>
                                </b-card-body>

                                <b-card-body v-else-if='localState=="editando"' class="p-1 m-1 bg-light">
                                  <b-row>
                                    <b-col md="8">
                                      <b-button
                                              @click="saveFormData"
                                              variant="outline-danger"
                                              size="sm"
                                      >Gravar Alterações</b-button>
                                    </b-col>
                                    <b-col md="4" align="right">
                                      <CloseButtom size="sm" caption="Abandonar" />
                                    </b-col>
                                  </b-row>
                                </b-card-body>

                                <b-card-body v-else-if='localState=="apagando"' class="p-1 m-1 bg-light">
                                  <b-row>
                                    <b-col md="8">
                                      <b-button
                                              @click="deleteFormData"
                                              variant="outline-danger"
                                              size="sm"
                                      >Apagar</b-button>
                                    </b-col>
                                    <b-col md="4" align="right">
                                      <CloseButtom size="sm" caption="Abandonar" />
                                    </b-col>
                                  </b-row>
                                </b-card-body>

                                <b-card-body v-else class="p-1 m-1 bg-light">
                                  <b-row>
                                    <b-col md="12" align="right">
                                      <CloseButtom size="sm" />
                                    </b-col>
                                  </b-row>
                                </b-card-body>

                              </b-card>
                            </b-card-group>
                          </b-col>
                        </b-row>
                        <!-- End of Toolbar -->

                      </b-card-body>
                    </b-card>
                  </b-card-group>

                </b-tab>

                <!-- Contatos component -->
                <b-tab title="Contatos" active>
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
                <b-tab title="Documentação">
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
                <b-tab title="Endereço">
                  <b-card-group>
                    <b-card no-body style="min-height: 240px; border-top: none;">
                      <b-card-body class="p-0 m-0">
                        <Enderecos :pessoaId="form.id" :pessoaState="localState" />
                      </b-card-body>
                    </b-card>
                  </b-card-group>
                </b-tab>
                <!-- End of enderecos component -->

                <b-tab title="Acesso">
                  <b-card-group>
                    <b-card no-body style="min-height: 240px; border-top: none;">
                      <b-card-body class="p-0 m-0">
                        <Acessos :pessoaId="form.id" :pessoaState="localState" />
                      </b-card-body>
                    </b-card>
                  </b-card-group>
                </b-tab>

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
    CloseButtom,
    Acessos,
    Contatos,
    Documentos,
    Enderecos
  },
  data() {
    return {
      title: "Cadastro de Pessoas",
      model: 'pessoa',
      form: this.pessoaObj,
      localState: this.state,
      avatar: {
        url: '',
        src: '',
      }
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
        let r;

        // Faz o post para o backend
        if (this.localState == 'editando') {
          let id = this.form.id;

          r = await this.$http.post('pessoa/' + id, this.form);
        }
        else if (this.localState == 'criando') {
          r = await this.$http.put('pessoa', this.form);
        }

        if (r && r.status && r.status === 200) {
          this.$bvToast.toast(r.data.message, {
            title: this.title,
            autoHideDelay: this.$settings.config.toastErrorTimeout,
            appendToast: true,
            variant: 'success'
          });

          this.$emit('getAll');
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
      // Faz o post para o backend
      let id = this.form.id;
      let r = await this.$http.delete('pessoa/' + id, this.form);

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

      this.$emit('getAll');
    },
    dismissFormData() {
      this.$root.$emit('bv::hide::modal', 'modal-form-1');
    },
    onReset() {
      this.$root.$emit('bv::hide::modal', 'modal-form-1');
    },
    validate() {
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
      let size = image.files[0].size;

      if (size > this.$settings.config.maxImageSize) {
        this.$bvToast.toast("Imagem escolhida é muito grande!", {
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
        axios.post('pessoa', fd, {
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
            this.$set(this.avatar, 'url', this.$settings.restapi + 'assets/pessoa/' + srci);
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
        });
      }
    },
    showEditingControllers() {
      return (this.localState === 'criando' || this.localState === 'editando');
    },
    getImgUrl() {
      return this.$settings.restapi + 'assets/' + this.model + '/' + this.pessoaObj.imagem;
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