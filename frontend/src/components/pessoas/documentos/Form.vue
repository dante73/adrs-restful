<template>
  <b-container class="small" fluid="xl">
    <b-row>

        <b-col md="12" class="p-0 m-0">
            <b-input-group id="input-group-tipo" class="text-center p-0 m-0">
                <b-input-group-prepend class="pr-1">
                    <b-form-select
                            id="select-tipo-documento"
                            v-model="form.tipo"
                            :options="$settings.tipos.documentos"
                            @change="typeValueChanges()"
                            size="sm"
                    >
                        <b-form-select-option :value="null" disabled>-- Selecione --</b-form-select-option>
                    </b-form-select>
                </b-input-group-prepend>
                <b-form-input
                        id="input-id"
                        v-model="form.valor"
                        v-mask="maskByType()"
                        :readonly="! enableChanges()"
                        autocomplete="off"
                        required
                        size="sm"
                ></b-form-input>
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
        title: "Cadastro de Pessoas / Documentos",
        model: 'documento',
        form: this.documentoObj,
        localState: this.state,
      }
    },
    props: {
      pessoaId: {
        type: Number,
        required: true
      },
      documentoObj: {
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
        /**
         * Se estiverem vazios não faz nada, só retorna negativo
         */
        if (this.form.tipo === "") {
          this.$bvToast.toast(
                  'Tipo de documento inválido!', {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          document.getElementById('select-tipo-documento').focus();

          return false;
        }
        else if (this.form.valor === "") {
          this.$bvToast.toast(
                  'Identificação de documento inválido!', {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          document.getElementById('input-id').focus();

          return false;
        }

        return true;
      },
      enableChanges() {
        let st = this.$support.st;
        return (this.localState === st.UPDATING || this.localState === st.CREATING);
      },
      maskByType() {
        let r = "";
        switch (this.form.tipo) {
          case 'CPF':
            r = this.$settings.masks.cpf;
            break;
          case 'CNPJ':
            r = this.$settings.masks.cnpj;
            break;
          case 'RG':
            r = this.$settings.masks.rg;
            break;
        }
        return r;
      },
      dismissFormData() {
        this.$set(this.form, 'tipo', '');
        this.$set(this.form, 'valor', '');

        document.getElementById('select-tipo-documento').focus();

        this.$emit('cancel-state');
      },
      typeValueChanges() {
        this.$set(this.form, 'valor', '');

        document.getElementById('input-id').focus();
      }
    },
    watch: {
      documentoObj: function () {
        this.$set(this, 'form', this.documentoObj);
      },
      state: function () {
        this.$set(this, 'localState', this.state);
      }
    }
  }
</script>