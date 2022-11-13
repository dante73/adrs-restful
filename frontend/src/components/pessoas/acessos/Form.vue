<template>
  <b-container fluid class="small">
    <b-row>

      <b-col md="5" class="p-0 m-0">
        <b-form-group id="input-group-chave" class="text-center p-0 m-0">
          <b-form-input
                  id="input-chave"
                  v-model="form.chave"
                  :readonly="! enableChanges()"
                  autocomplete="off"
                  required
                  size="sm"
          ></b-form-input>
        </b-form-group>
      </b-col>

      <b-col md="6" class="p-0 m-0 pl-1">
        <b-form-group id="input-group-senha" class="text-center p-0 m-0">
          <b-form-input
                  id="input-senha"
                  v-model="form.senha"
                  :readonly="! enableChanges()"
                  autocomplete="off"
                  required
                  size="sm"
          ></b-form-input>
        </b-form-group>
      </b-col>

      <b-col md="1" class="p-0 m-0 pt-2 pl-1">
        <b-container v-if='localState=="criando" || localState=="editando"'>
          <b-row>
            <b-col class="p-0 m-0 pl-1">
              <b-icon-check-circle @click="saveFormData" class="btn btn-large text-success p-0 m-0"></b-icon-check-circle>
            </b-col>
            <b-col class="p-0 m-0 pl-1">
              <b-icon-arrow-counterclockwise @click="dismissFormData" class="btn btn-large text-danger p-0 m-0"></b-icon-arrow-counterclockwise>
            </b-col>
          </b-row>
        </b-container>
      </b-col>

    </b-row>
  </b-container>
</template>

<script>
  export default {
    data() {
      return {
        title: "Cadastro de Pessoas / Acessos",
        model: 'acesso',
        form: this.acessoObj,
        localState: this.state,
      }
    },
    props: {
      pessoaId: {
        type: Number,
        required: true
      },
      acessoObj: {
        type: Object,
        required: false
      },
      state: {
        type: String,
        required: false
      }
    },
    methods: {
      async saveFormData() {
        // Valida o formulário
        let v = this.validate();

        // Só posta se estiver tudo ok
        if (v) {
          let r;

          // Faz o envio para o backend
          if (this.localState == 'editando') {
            let id = this.form.id;

            r = await this.$http.put('acesso/' + id, this.form);
          }
          else if (this.localState == 'criando') {
            this.$set(this.form, 'pessoa', this.pessoaId);

            r = await this.$http.post('acesso', this.form);
          }

          if (r && r.status && r.status === 200) {
            this.$bvToast.toast(r.data.message, {
              title: this.title,
              autoHideDelay: this.$settings.config.toastErrorTimeout,
              appendToast: true,
              variant: 'success'
            });

            this.$emit('getAll');

            this.dismissFormData();
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
        let r = await this.$http.delete('acesso/' + id, this.form);

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
              autoHideDelay: this.$settings.config.toastSucessTimeout,
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
      validate() {
        let c = this.form.chave;
        let s = this.form.senha;

        /**
         * Se estiverem vazios não faz nada, só retorna negativo
         */
        if (c === "") {
          this.$bvToast.toast(
                  'Chave de acesso inválida!', {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          document.getElementById('input-chave').focus();

          return false;
        }
        else if (s === "") {
          this.$bvToast.toast(
                  'Senha de acesso inválida!', {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          document.getElementById('input-senha').focus();

          return false;
        }

        return true;
      },
      enableChanges() {
        return (this.localState === 'criando' || this.localState === 'editando');
      },
      dismissFormData() {
        this.$set(this.form, 'chave', '');
        this.$set(this.form, 'senha', '');

        document.getElementById('input-chave').focus();

        this.$emit('cancelState');
      }
    },
    watch: {
      acessoObj: function () {
        this.$set(this, 'form', this.acessoObj);
      },
      state: function () {
        this.$set(this, 'localState', this.state);
      }
    }
  }
</script>