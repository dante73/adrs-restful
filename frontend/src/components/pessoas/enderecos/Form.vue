<template>
  <b-container class="pt-2 small bg-light" fluid="xl">

    <b-container class="p-0 m-0" style="height: 210px;" fluid="xl">
      <b-row>
        <b-col md="3" class="p-0 m-0 pl-1">
          <b-form-group
                  id="input-group-cep"
                  label="Cep"
                  label-for="input-cep"
                  label-class="bg-light text-center p-0 m-0">
            <b-form-input
                    id="input-cep"
                    v-model="form.cep"
                    v-mask="$settings.masks.cep"
                    autocomplete="off"
                    required
                    @change="getAddressByCep()"
                    size="sm"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="9" class="p-0 m-0 pl-1 pr-1">
          <b-form-group
                  id="input-group-rua"
                  label="Rua"
                  label-for="input-rua"
                  label-class="bg-light text-center p-0 m-0">
            <b-form-input
                    id="input-rua"
                    v-model="form.rua"
                    autocomplete="off"
                    required
                    size="sm"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col md="4" class="p-0 m-0 pl-1">
          <b-form-group
                  id="input-group-numero"
                  label="Número/Complemento"
                  label-for="input-numero"
                  label-class="bg-light text-center p-0 m-0">
            <b-form-input
                    id="input-numero"
                    v-model="form.numero"
                    autocomplete="off"
                    required
                    size="sm"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="8" class="p-0 m-0 px-1">
          <b-form-group
                  id="input-group-bairro"
                  label="Bairro"
                  label-for="input-bairro"
                  label-class="bg-light text-center p-0 m-0">
            <b-form-input
                    id="input-bairro"
                    v-model="form.bairro"
                  autocomplete="off"
                    required
                    size="sm"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="6" class="p-0 m-0 pl-1">
          <b-form-group
                  id="input-group-estado"
                  label="Estado"
                  label-for="select-estado"
                  label-class="bg-light text-center p-0 m-0">
            <b-form-select
                    id="select-estado"
                    v-model="form.estado"
                    :options="allStates"
                    @change="choseState"
                    size="sm">
              <b-form-select-option :value="null" disabled>-- Selecione --</b-form-select-option>
            </b-form-select>
          </b-form-group>
        </b-col>
        <b-col md="6" class="p-0 m-0 px-1">
          <b-form-group
                  id="input-group-cidade"
                  label="Cidade"
                  label-for="select-cidade"
                  label-class="bg-light text-center p-0 m-0">
            <b-form-select
                    id="select-cidade"
                    v-model="form.cidade"
                    :options="allCities"
                    size="sm">
              <b-form-select-option :value="null" disabled>-- Selecione --</b-form-select-option>
            </b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-container>

    <!-- Toolbar -->
    <b-row class="pt-1 small">
      <b-col md="12" class="p-1 m-0">
        <b-card-group class="shadow-lg">
          <b-card no-body class="bg-light">
            <b-card-body class="p-1 m-1 bg-light">
              <b-row>
                <b-col md="8">
                  <b-button
                          @click="saveFormData"
                          variant="outline-success"
                          size="sm">Gravar o Novo Endereço</b-button>
                </b-col>
                <b-col md="4" align="right">
                  <b-button
                          @click="dismissFormData"
                          variant="warning"
                          size="sm">Cancelar</b-button>
                </b-col>
              </b-row>
            </b-card-body>
          </b-card>
        </b-card-group>
      </b-col>
    </b-row>
    <!-- End of Toolbar -->

  </b-container>
</template>

<script>
  export default {
    data() {
      return {
        title: "Cadastro de Pessoas / Endereços",
        model: 'endereco',
        form: this.enderecoObj,
        localState: this.state,
        allStates: [],
        allCities: []
      }
    },
    props: {
      pessoaId: {
        type: Number,
        required: true
      },
      enderecoObj: {
        type: Object,
        required: false
      },
      state: {
        type: Number,
        required: false
      }
    },
    mounted() {
      this.getAllStates();
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
            this.$set(this.form, 'principal', 0);

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
        return true;
      },
      enableChanges() {
        let st = this.$support.st;
        return (this.localState === st.UPDATING || this.localState === st.CREATING);
      },
      dismissFormData() {
        this.$emit('cancel-state');
      },
      choseState() {
        this.getAllCities();
      },
      async getAllStates() {
        let allStates = await this.$support.getAllStates();

        this.$set(this, 'allStates', allStates);
      },
      async getAllCities() {
        let sid = this.form.estado;
        let allCities = await this.$support.getAllCitiesByState(sid);

        this.$set(this, 'allCities', allCities);
      },
      async getAddressByCep() {
        let cep = this.form.cep.replace(/\D/, '');

        if (this.localState === this.$support.st.CREATING && cep.match(/\d{8}/)) {
          this.$set(this.form, 'rua', 'Buscando informação...');
          this.$set(this.form, 'bairro', 'Buscando informação...');

          let address = await this.$viaCep.buscarCep(cep);

          if ("erro" in address) {
            this.$set(this.form, 'rua', '');
            this.$set(this.form, 'bairro', '');

            this.$bvToast.toast('Cep não encontrado !', {
              title: this.title,
              autoHideDelay: this.$settings.config.toastErrorTimeout,
              appendToast: true,
              variant: 'danger'
            });

            this.$set(this.form, 'cep', '');

            // Move o foco para o complemento/número
            document.getElementById('input-cep').focus();

            return false;
          }

          this.$set(this.form, 'rua', address.logradouro);
          this.$set(this.form, 'bairro', address.bairro);

          // Seleciona o Estado
          let state = this.$support.findIndexByKeyValue(this.allStates, 'short', address.uf);

          this.$set(this.form, 'estado', this.allStates[state].value);

          // Seleciona a Cidade
          await this.getAllCities();

          let city = this.$support.findIndexByKeyValue(this.allCities, 'text', address.localidade);

          this.$set(this.form, 'cidade', this.allCities[city].value);

          // Move o foco para o complemento/número
          document.getElementById('input-numero').focus();
        }
      }
    },
    watch: {
      enderecoObj: function () {
        this.$set(this, 'form', this.enderecoObj);

        /* When changing, need to take city data on select */
        let e = this.form.estado;
        if (typeof(e) === "number" && e !== 0) {
          this.getAllCities();
        }
      },
      state: function () {
        this.$set(this, 'localState', this.state);
      }
    }
}
</script>