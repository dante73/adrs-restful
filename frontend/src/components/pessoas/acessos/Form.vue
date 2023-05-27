<template>
  <b-container class="small" fluid="xl">
    <b-row>

      <b-col md="12" class="p-0 m-0">
        <b-input-group id="input-group-tipo" class="text-center p-0 m-0">
          <b-input-group-prepend class="pr-1">
            <b-form-input
                    id="input-chave"
                    v-model="form.chave"
                    :readonly="! enableChanges()"
                    autocomplete="off"
                    required
                    size="sm"></b-form-input>
          </b-input-group-prepend>
          <b-form-input
                  id="input-senha"
                  v-model="form.senha"
                  :readonly="! enableChanges()"
                  autocomplete="off"
                  required
                  size="sm"></b-form-input>
          <b-input-group-append v-if='localState === $support.st.UPDATING || localState === $support.st.CREATING'>
            <b-button variant="outline-success" size="sm">
              <b-icon-check-circle @click="saveFormData"></b-icon-check-circle>
            </b-button>
            <b-button variant="outline-danger" size="sm">
              <b-icon-arrow-counterclockwise @click="dismissFormData"></b-icon-arrow-counterclockwise>
            </b-button>
          </b-input-group-append>
        </b-input-group>
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
        type: Number,
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
          if (this.localState === this.$support.st.UPDATING) {
            let id = this.form.id;

            r = await this.$http.put(this.model + '/' + id, this.form);
          }
          else if (this.localState === this.$support.st.CREATING) {
            this.$set(this.form, 'pessoa', this.pessoaId);

            r = await this.$http.post(this.model, this.form);
          }

          if (r && r.status && r.status === 200) {
            this.$bvToast.toast(r.data.message, {
              title: this.title,
              autoHideDelay: this.$settings.config.toastErrorTimeout,
              appendToast: true,
              variant: 'success'
            });

            this.$emit('get-all-function');

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
              autoHideDelay: this.$settings.config.toastSuccessTimeout,
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
        let st = this.$support.st;
        return (this.localState === st.UPDATING || this.localState === st.CREATING);
      },
      dismissFormData() {
        document.getElementById('input-chave').focus();

        this.$emit('cancel-state');
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