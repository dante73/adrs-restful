<template>
  <div>
    <b-container class="p-0 m-0 pl-2 pr-2">

      <b-card-group>
        <b-card no-body>
          <b-card-body>

            <b-container fluid>
              <b-row class="w-100 h-100">
                <b-col md="3" class="">
                  <b-avatar id="avatar-foto" :src="avatar.url" text="Foto" size="144px"></b-avatar>
                </b-col>
                <b-col md="9">
                  <b-container fluid>
                    <b-row>
                      <b-col md="7">
                        <b-form-group id="radio-group-gender" label="Gênero" label-for="radio-gender">
                          <b-form-radio-group
                                  id="radio-gender"
                                  v-model="form.genero"
                                  :options="$settings.tipos.sexo"
                                  name="radio-btn-gender"
                                  button-variant="outline-primary"
                                  buttons
                          ></b-form-radio-group>
                        </b-form-group>
                      </b-col>
                      <b-col md="5">
                        <b-form-group id="input-group-birthday" label="Data de Nascimento" label-for="input-birthday">
                          <b-form-input
                                  id="input-birthday"
                                  v-model="form.nascimento"
                                  type="date"
                                  :placeholder="$settings.format.date"
                                  :readonly="enableChanges()"
                                  autocomplete="off"
                                  required
                          ></b-form-input>
                        </b-form-group>
                      </b-col>
                    </b-row>
                    <b-row>
                      <b-col md="12">
                        <b-form-group id="input-group-name" label="Nome" label-for="input-name">
                          <b-form-input
                                  id="input-name"
                                  v-model="form.nome"
                                  :readonly="enableChanges()"
                                  placeholder="Nome da Pessoa"
                                  required
                          ></b-form-input>
                        </b-form-group>
                      </b-col>
                    </b-row>
                    <b-row v-if=" ! enableChanges()">
                      <b-col md="12">
                        <b-form-group id="input-group-file" label="Arquivo de Foto" label-for="input-file">
                          <b-input-group>
                            <b-form-file
                                    id="input-file"
                                    v-model="form.file"
                                    :state="Boolean(form.file) && validateImage()"
                                    placeholder="Escolha um arquivo de foto..."
                                    drop-placeholder="Drop file here..."
                            ></b-form-file>
                            <b-input-group-append>
                              <b-button @click="postImage()">Upload</b-button>
                            </b-input-group-append>
                          </b-input-group>
                        </b-form-group>
                      </b-col>
                    </b-row>
                  </b-container>
                </b-col>
              </b-row>
            </b-container>

          </b-card-body>

          <b-card-footer v-if='localState==="criando"'>

            <b-row>
              <b-col md="6">
                <b-button @click="saveFormData" variant="outline-danger">Gravar Novo Registro</b-button>
              </b-col>
              <b-col md="6" align="right">
                <b-button @click="dismissFormData" variant="primary">Abandonar Operação</b-button>
              </b-col>
            </b-row>

          </b-card-footer>

          <b-card-footer v-else-if='localState=="editando"'>

            <b-row>
              <b-col md="6">
                <b-button @click="saveFormData" variant="outline-danger">Gravar Alterações</b-button>
              </b-col>
              <b-col md="6" align="right">
                <b-button @click="dismissFormData" variant="primary">Abandonar Operação</b-button>
              </b-col>
            </b-row>

          </b-card-footer>

          <b-card-footer v-else-if='localState=="apagando"'>

            <b-row>
              <b-col md="6">
                <b-button @click="deleteFormData" variant="outline-danger">Apagar</b-button>
              </b-col>
              <b-col md="6" align="right">
                <b-button @click="dismissFormData" variant="primary">Abandonar Operação</b-button>
              </b-col>
            </b-row>

          </b-card-footer>

          <b-card-footer v-else>

            <b-row>
              <b-col md="12" align="right">
                <b-button @click="dismissFormData" variant="primary">Fechar Janela</b-button>
              </b-col>
           </b-row>

          </b-card-footer>

        </b-card>
      </b-card-group>

    </b-container>
  </div>
</template>

<script>
import axios from 'axios';

export default {
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
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'success'
          });

          this.$emit('getAll');
        }
        else {
          this.$bvToast.toast(r.data, {
            title: this.title,
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'danger'
          });
        }
      }
    },
    async deleteFormData() {
      let r;

      // Faz o post para o backend
      let id = this.form.id;

      r = await this.$http.delete('pessoa/' + id, this.form);

      if (r && r.status && r.status === 200) {
        this.$bvToast.toast(r.data.message, {
          title: this.title,
          autoHideDelay: 5000,
          appendToast: true,
          variant: 'success'
        });

        this.$emit('getAll');
      }
      else {
        this.$bvToast.toast(r.data, {
          title: this.title,
          autoHideDelay: 5000,
          appendToast: true,
          variant: 'danger'
        });
      }
    },
    dismissFormData() {
      this.$root.$emit('bv::hide::modal', 'modal-form-1');
    },
    onReset() {
      this.$root.$emit('bv::hide::modal', 'modal-form-1');
    },
    validate() {
      if (this.avatar.src.length > 0) {
        this.form.imagem = this.avatar.src;
      }
      return true;
    },
    validateImage() {
      let image = document.getElementById('input-file');

      if ( ! image) {
        return false;
      }

      let size = image.files[0].size;

      if (size > this.$settings.maxImageSize) {
        this.$bvToast.toast("Imagem escolhida é muito grande!", {
          title: this.title,
          autoHideDelay: 5000,
          appendToast: true,
          variant: 'danger'
        });

        return false;
      }

      return true;
    },
    postImage() {
      let image = document.getElementById('input-file');
      let fd = new FormData();

      fd.append("imagem", image.files[0]);

      axios.post('pessoa', fd, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        if (response.status === 200) {
          let srci = response.data.filename;

          this.$set(this.avatar, 'src', srci);
          this.$set(this.avatar, 'url', this.$settings.restapi + 'assets/pessoa/' + srci);
        }
      });
    },
    enableChanges() {
      return ! (this.localState === 'criando' || this.localState === 'editando');
    },
    avatarImgUploadButtonColor() {
      return (this.validateImage() ? 'success' : 'danger');
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