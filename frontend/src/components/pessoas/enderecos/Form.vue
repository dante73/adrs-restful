<template>
  <b-container fluid class="pt-2 small">

    <b-container fluid class="p-0 m-0" style="height: 210px;">
      <b-row>
        <b-col md="3" class="p-0 m-0 pl-1">
          <b-form-group
                  id="input-group-cep"
                  label="Cep"
                  label-for="input-cep"
                  label-class="bg-light text-center p-0 m-0"
          >
            <b-form-input
                    id="input-cep"
                    v-model="form.cep"
                    v-mask="$settings.masks.cep"
                    autocomplete="off"
                    required
                    size="sm"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="9" class="p-0 m-0 pl-1 pr-1">
          <b-form-group
                  id="input-group-rua"
                  label="Rua"
                  label-for="input-rua"
                  label-class="bg-light text-center p-0 m-0"
          >
            <b-form-input
                    id="input-rua"
                    v-model="form.rua"
                    autocomplete="off"
                    required
                    size="sm"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col md="6" class="p-0 m-0 pl-1">
          <b-form-group
                  id="input-group-numero"
                  label="Número/Complemento"
                  label-for="input-numero"
                  label-class="bg-light text-center p-0 m-0"
          >
            <b-form-input
                    id="input-numero"
                    v-model="form.numero"
                    autocomplete="off"
                    required
                    size="sm"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="6" class="p-0 m-0 px-1">
          <b-form-group
                  id="input-group-bairro"
                  label="Bairro"
                  label-for="input-bairro"
                  label-class="bg-light text-center p-0 m-0"
          >
            <b-form-input
                    id="input-bairro"
                    v-model="form.bairro"
                    autocomplete="off"
                    required
                    size="sm"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="6" class="p-0 m-0 pl-1">
          <b-form-group
                  id="input-group-estado"
                  label="Estado"
                  label-for="select-estado"
                  label-class="bg-light text-center p-0 m-0"
          >
            <b-form-select
                    id="select-estado"
                    v-model="form.estado"
                    :options="allStates"
                    @change="choseState"
                    size="sm"
            >
              <b-form-select-option :value="null" disabled>-- Selecione --</b-form-select-option>
            </b-form-select>
          </b-form-group>
        </b-col>
        <b-col md="6" class="p-0 m-0 px-1">
          <b-form-group
                  id="input-group-cidade"
                  label="Cidade"
                  label-for="select-cidade"
                  label-class="bg-light text-center p-0 m-0"
          >
            <b-form-select
                    id="select-cidade"
                    v-model="form.cidade"
                    :options="allCities"
                    size="sm"
            >
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
                          size="sm"
                  >Gravar o Novo Endereço</b-button>
                </b-col>
                <b-col md="4" align="right">
                  <b-button
                          @click="dismissFormData"
                          variant="warning"
                          size="sm"
                  >Cancelar</b-button>
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
      type: String,
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
        if (this.localState == 'editando') {
          let id = this.form.id;

          r = await this.$http.put('endereco/' + id, this.form);
        }
        else if (this.localState == 'criando') {
          this.$set(this.form, 'pessoa', this.pessoaId);
          this.$set(this.form, 'principal', 0);

          r = await this.$http.post('endereco', this.form);
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
      let r = await this.$http.delete('endereco/' + id, this.form);

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
      return true;
    },
    enableChanges() {
      return (this.localState === 'criando' || this.localState === 'editando');
    },
    dismissFormData() {
      this.$set(this.form, 'cep', '');
      this.$set(this.form, 'rua', '');
      this.$set(this.form, 'numero', '');
      this.$set(this.form, 'bairro', '');
      this.$set(this.form, 'estado', 0);
      this.$set(this.form, 'cidade', 0);

      document.getElementById('input-cep').focus();

      this.$emit('cancelState');
    },
    choseState() {
      this.getAllCities();
    },
    async getAllStates() {
      let s = await this.$http.get('state');

      this.$set(this, 'allStates', s.data.map(elm => {
          return { text: elm.name, value: elm.id };
      }))
    },
    async getAllCities() {
      let sid = document.getElementById('select-estado').value;
      let s = await this.$http.get('city/loadAllByStateId/' + sid);

      this.$set(this, 'allCities', s.data.map(elm => {
        return { text: elm.name, value: elm.id };
      }))
    }
  },
  watch: {
    enderecoObj: function () {
      this.$set(this, 'form', this.enderecoObj);
    },
    state: function () {
      this.$set(this, 'localState', this.state);
    }
  }
}
</script>