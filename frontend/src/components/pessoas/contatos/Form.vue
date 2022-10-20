<template>
  <b-container fluid class="small">

    <b-row>
      <b-col md="4" class="p-0 m-0">
        <b-form-group id="input-group-tipo" class="text-center p-0 m-0">
          <b-form-select
                  id="select-tipo"
                  v-model="form.tipo"
                  :options="$settings.tipos.contatos"
                  @change="typeValueChanges()"
                  size="sm"
          >
            <b-form-select-option :value="null" disabled>-- Selecione --</b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>

      <b-col md="7" class="p-0 m-0 pl-1">
        <b-form-group id="input-group-valor" class="text-center p-0 m-0">
          <b-form-input
                  id="input-valor"
                  v-model="form.valor"
                  v-mask="maskByType()"
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
        title: "Cadastro de Pessoas / Contatos",
        model: 'contato',
        form: this.contatoObj,
        localState: this.state,
      }
    },
    props: {
      pessoaId: {
        type: Number,
        required: true
      },
      contatoObj: {
        type: Object,
        required: false
      },
      state: {
        type: String,
        required: false
      }
    },
    mounted() {
        console.log(this.form);
        console.log(this.contatoObj);
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

            r = await this.$http.post('contato/' + id, this.form);
          }
          else if (this.localState == 'criando') {
            this.$set(this.form, 'pessoa', this.pessoaId);

            r = await this.$http.put('contato', this.form);
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
        // Faz o post para o backend
        let id = this.form.id;
        let r = await this.$http.delete('contato/' + id, this.form);

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
        let t = document.getElementById('select-tipo').value;
        let v = document.getElementById('input-valor').value;

        /**
         * Se estiverem vazios não faz nada, só retorna negativo
         */
        if (t === "") {
          this.$bvToast.toast(
                  'Meio de contato inválido!', {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          document.getElementById('select-tipo').focus();

          return false;
        }
        else if (v === "") {
          this.$bvToast.toast(
                  'Identificação do contato inválido!', {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          document.getElementById('input-valor').focus();

          return false;
        }

        /**
         * Seleciona uma expressão regular de validação, de acordo com o tipo de contato
         */
        let regexp;
        switch (t) {
          case 'C':
            regexp = this.$settings.regexp.valida_celular;
            v = v.replace(this.$settings.regexp.limpa_telefone, '');
            break;
          case 'F':
            regexp = this.$settings.regexp.valida_telefone;
            v = v.replace(this.$settings.regexp.limpa_telefone, '');
            break;
          case 'E':
            regexp = this.$settings.regexp.valida_email;
            break;
        }

        /**
         * Testa a expressão com o valor e retorna negativo se não passar
         */
        if (regexp && ! regexp.test(v)) {
          let text = this.$support.typeTextByKey(this.$settings.tipos.contatos, t);

          this.$bvToast.toast(
                  text + ' inválido!', {
                    title: this.title,
                    autoHideDelay: this.$settings.config.toastErrorTimeout,
                    appendToast: true,
                    variant: 'danger'
                  });

          return false;
        }

        return true;
      },
      enableChanges() {
        return (this.localState === 'criando' || this.localState === 'editando');
      },
      maskByType() {
        let r = "";
        switch (this.form.tipo) {
          case 'F':
            r = this.$settings.masks.telefone;
            break;
          case 'C':
            r = this.$settings.masks.celular;
            break;
        }
        return r;
      },
      dismissFormData() {
        this.$set(this.form, 'tipo', '');
        this.$set(this.form, 'valor', '');

        document.getElementById('select-tipo').focus();

        this.$emit('cancelState');
      },
      typeValueChanges() {
        this.$set(this.form, 'valor', '');

        document.getElementById('input-valor').focus();
      }
    },
    watch: {
      contatoObj: function () {
        this.$set(this, 'form', this.contatoObj);
      },
      state: function () {
        this.$set(this, 'localState', this.state);
      }
    }
  }
</script>