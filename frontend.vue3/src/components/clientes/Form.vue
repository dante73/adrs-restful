<template>
  <div>
    <b-container>

        <b-card no-body>
          <b-card-header class="pt-2 pb-0 px-0">

            <b-container fluid>
              <b-row class="text-center">
                <b-col class="py-0 pl-2 pr-1">
                  <b-form-group id="radio-group-0" label="Pessoa" label-for="radio-0">
                    <b-form-radio-group
                            id="radio-0"
                            v-model="form.pessoafj"
                            :options="$settings.tipos.pessoa"
                            name="radio-btn-pessoa"
                            button-variant="outline-success"
                            buttons
                    ></b-form-radio-group>
                  </b-form-group>
                </b-col>
                <b-col class="py-0 px-1">
                  <b-form-group id="input-group-1" label="ID Cliente" label-for="input-1">
                    <b-form-input
                            id="input-1"
                            v-model="form.idc"
                            v-mask="$settings.masks.idc"
                            placeholder=""
                            class="text-uppercase"
                            required
                    ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col md="1" class="py-0 px-1" v-show="(form.pessoafj === 'J')">
                  <b-form-group id="input-group-2" label="ID Loja" label-for="input-2">
                    <b-form-input
                            id="input-2"
                            v-model="form.idl"
                            v-mask="$settings.masks.idl"
                            placeholder=""
                    ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col class="py-0 px-1">
                  <b-form-group id="radio-group-1" label="Situação" label-for="radio-1">
                    <b-form-radio-group
                            id="radio-1"
                            v-model="form.situacao"
                            :options="$settings.tipos.situacao"
                            name="radio-btn-situacao"
                            button-variant="outline-secondary"
                            buttons
                    ></b-form-radio-group>
                  </b-form-group>
                </b-col>
                <b-col md="4" class="py-0 px-1">
                  <b-form-group id="input-group-3" :label="(form.pessoafj === 'F' ? 'Nome' : 'Nome Fantasia')" label-for="input-3">
                    <b-form-input
                            id="input-3"
                            v-model="form.nome_fantasia"
                            :placeholder="(form.pessoafj === 'F' ? '' : 'Nome pelo qual a empresa é conhecida.')"
                            required
                    ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col class="py-0 pl-1 pr-2" v-if="(form.pessoafj === 'J')">
                  <b-form-group id="radio-group-2" label="Tipo" label-for="radio-2">
                    <b-form-radio-group
                            id="radio-2"
                            v-model="form.tipo"
                            :options="$settings.tipos.tipo_cliente"
                            name="radio-btn-tipo"
                            button-variant="outline-warning"
                            buttons
                    ></b-form-radio-group>
                  </b-form-group>
                </b-col>
                <b-col class="py-0 pl-1 pr-2" v-else>&nbsp;</b-col>
              </b-row>
            </b-container>

          </b-card-header>
          <b-tabs card>

            <b-tab title="Documentação">

              <b-container fluid class="p-0" style="min-height: 190px;">
                <b-row class="text-center" v-if="form.pessoafj === 'J'">
                  <b-col md="4" offset-md="1">
                    <!-- CNPJ -->
                    <b-form-group id="input-group-4" label="CNPJ" label-for="input-4">
                      <b-form-input
                              id="input-4"
                              v-model="form.cnpj"
                              v-mask="$settings.masks.cnpj"
                              placeholder="CNPJ da Empresa"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="6">
                    <!-- Razão Social -->
                    <b-form-group id="input-group-5" label="Razão Social" label-for="input-5">
                      <b-form-input
                              id="input-5"
                              v-model="form.razao_social"
                              class="text-uppercase"
                              placeholder="Razão Social da Empresa"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-row class="text-center" v-else-if="form.pessoafj === 'F'">
                  <b-col md="3" offset-md="4">
                    <!-- CPF -->
                    <b-form-group id="input-group-4" label="CPF" label-for="input-4">
                      <b-form-input
                              id="input-4"
                              v-model="form.cpf"
                              v-mask="$settings.masks.cpf"
                              placeholder="CPF"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>

                <!-- Inscrições estadual e municipal -->
                <b-row class="text-center" v-if="form.pessoafj === 'J'">
                  <b-col md="4" offset-md="1">
                    <b-form-group id="radio-group-3" label="Tipo de Contribuinte" label-for="radio-3">
                      <b-form-radio-group
                              id="radio-3"
                              v-model="form.tipo_contribuinte"
                              :options="$settings.tipos.tipo_contribuinte"
                              name="radio-btn-tipoc"
                              button-variant="outline-secondary"
                              buttons
                      ></b-form-radio-group>
                    </b-form-group>
                  </b-col>
                  <b-col md="3">
                    <b-form-group id="input-group-6" label="Inscrição Estadual" label-for="input-6">
                      <b-form-input
                              id="input-6"
                              v-model="form.inscricao_estadual"
                              placeholder="Inscrição Estadual"
                              maxlength="20"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="3">
                    <b-form-group id="input-group-7" label="Inscrição Municipal" label-for="input-7">
                      <b-form-input
                              id="input-7"
                              v-model="form.inscricao_municipal"
                              placeholder="Inscrição Municipal"
                              maxlength="10"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-row v-else-if="form.pessoafj === 'F'">
                  <b-col class="text-center" md="3" offset-md="4">
                    <b-form-group id="input-group-6" label="RG" label-for="input-6">
                      <b-form-input
                              id="input-6"
                              v-model="form.rg"
                              placeholder="RG"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-container>

            </b-tab>
            <b-tab title="Telefones">

              <b-container fluid class="p-0" style="min-height: 190px;">
                <!-- Telefones Comerciais -->
                <b-row>
                  <b-col md="3" offset-md="3">
                    <b-form-group id="input-group-8" label="Comercial" label-for="input-8">
                      <b-form-input
                              id="input-8"
                              v-model="form.telefone_comercial1"
                              v-mask="$settings.masks.telefone"
                              placeholder=""
                              required
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="3">
                    <b-form-group id="input-group-9" label="Comercial" label-for="input-9">
                      <b-form-input
                              id="input-9"
                              v-model="form.telefone_comercial2"
                              v-mask="$settings.masks.telefone"
                              placeholder=""
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>

                <!-- Telefones Celulares -->
                <b-row>
                  <b-col md="3" offset-md="3">
                    <b-form-group id="input-group-10" label="Celular" label-for="input-10">
                      <b-form-input
                              id="input-10"
                              v-model="form.telefone_celular1"
                              v-mask="$settings.masks.celular"
                              placeholder=""
                              required
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="3">
                    <b-form-group id="input-group-11" label="Celular" label-for="input-11">
                      <b-form-input
                              id="input-11"
                              v-model="form.telefone_celular2"
                              v-mask="$settings.masks.celular"
                              placeholder=""
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-container>

            </b-tab>
            <b-tab title="E-mails">

              <b-container fluid class="p-0" style="min-height: 190px;">
                <!-- Emails empresa e comercial -->
                <b-row class="text-center">
                  <b-col md="4" class="pr-0 pl-2">
                    <b-form-group id="input-group-12" label="Empresa" label-for="input-12" >
                      <b-form-input
                              id="input-12"
                              v-model="form.email_empresa"
                              type="email"
                              placeholder="Da Empresa"
                              required
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="4" class="px-1">
                    <b-form-group id="input-group-13" label="Comercial" label-for="input-13">
                      <b-form-input
                              id="input-13"
                              v-model="form.email_comercial"
                              type="email"
                              placeholder="Do departamento comercial"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="4" class="pr-2 pl-0">
                    <b-form-group id="input-group-14" label="Financeiro" label-for="input-14">
                      <b-form-input
                              id="input-14"
                              v-model="form.email_financeiro"
                              type="email"
                              placeholder="Do departamento financeiro"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>

                <!-- Emails financeiro e diretoria -->
                <b-row class="text-center">
                  <b-col md="4" class="pr-0 pl-2">
                    <b-form-group id="input-group-15" label="Diretoria" label-for="input-15">
                      <b-form-input
                              id="input-15"
                              v-model="form.email_diretoria"
                              type="email"
                              placeholder="Da diretoria"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="4" class="px-1">
                    <b-form-group id="input-group-16" label="Tecnologia" label-for="input-16">
                      <b-form-input
                              id="input-16"
                              v-model="form.email_ti"
                              type="email"
                              placeholder="Do departamento de TI"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col md="4" class="pr-2 pl-0">
                    <b-form-group id="input-group-17" label="Outro" label-for="input-17">
                      <b-form-input
                              id="input-17"
                              v-model="form.email_ti"
                              type="email"
                              placeholder="Outro"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-container>

            </b-tab>
            <b-tab title="Senha">

              <b-container fluid class="p-0" style="min-height: 190px;">
                <b-row class="text-center">
                  <b-col md="4" offset-md="4">
                    <b-form-group id="input-group-18" label="Senha" label-for="input-18">
                      <b-form-input
                              id="input-18"
                              v-model="form.senha_nova"
                              placeholder=""
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-row class="text-center">
                  <b-col md="4" offset-md="4">
                    <b-form-group id="input-group-19" label="Confirme a Senha" label-for="input-19">
                      <b-form-input
                              id="input-19"
                              v-model="form.senha_confirma"
                              placeholder="Confirme a senha"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-container>

            </b-tab>
            <b-tab title="Foto">

              <b-container fluid class="p-0" style="min-height: 190px;">
                <b-row class="text-center">
                  <b-col md="4" offset-md="4" align-center>
                    <b-form-group id="input-group-20" label="Arquivo de Imagem" label-for="input-20" class="pt-5 pb-5">
                      <b-form-input
                              id="input-20"
                              v-model="form.foto"
                              placeholder="Informe a foto"
                      ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
              </b-container>

            </b-tab>
          </b-tabs>

          <b-card-footer>

            <b-row>
              <b-col md="6">
                <b-button @click="saveFormData" variant="outline-danger">Gravar</b-button>
              </b-col>
              <b-col md="6" align="right">
                <b-button @click="dismissFormData" variant="outline-primary">Abandonar</b-button>
              </b-col>
            </b-row>

          </b-card-footer>
        </b-card>

    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: this.clienteObj,
      localState: this.state
    }
  },
  props: {
    clienteObj: {
      type: Object,
      required: false
    },
    state: {
      type: String,
      required: false
    }
  },
  mounted() {
    // Alguns fields que só existem localmente (no form) precisam ser inicializados
    if ( ! this.form.cpf) {
      // O cpf está no campo cnpj, quando estiver editando é preciso setar ele no
      // campo correto em caso de pessoa física :
      if (this.localState == 'criando') {
        this.$set(this.form, 'cpf', "");
      }
      else {
        this.$set(this.form, 'cpf', this.clienteObj.cnpj);
      }
    }
    // Campos para setagem de senha
    if ( ! this.form.senha_nova) {
      this.$set(this.form, 'senha_nova', "");
    }
    if ( ! this.form.senha_confirma) {
      this.$set(this.form, 'senha_confirma', "");
    }
  },
  methods: {
    async saveFormData() {
      console.log(this.form);

      // Valida o formulário
      let v = this.validate();

      // Só posta se estiver tudo ok
      if (v) {
        let r;

        // Faz o post para o backend
        if (this.localState == 'editando') {
          r = await this.$http.post('pessoa/save', {pessoa: this.form});
        } else if (this.localState == 'apagando') {
          r = await this.$http.post('pessoa/del', {pessoa: this.form});
        } else if (this.localState == 'criando') {
          r = await this.$http.post('pessoa/add', {pessoa: this.form});
        }

        if (r && r.status && r.status === 200) {
          this.$bvToast.toast(r.data.message, {
            title: 'Cadastro de Usuários',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'success'
          });

          console.log(r);
          this.$emit('getAll');
        }
      }
    },
    dismissFormData() {
      this.$root.$emit('bv::hide::modal', 'modal-form-1');
    },
    async onSubmit(ev) {
      ev.preventDefault();

      // Valida o formulário
      let v = this.validate();

      // Só posta se estiver tudo ok
      if (v) {
        let r;

        // Faz o post para o backend
        if (this.localState == 'editando') {
          r = await this.$http.post('update.php', { cliente: this.form });
        }
        else if (this.localState == 'apagando') {
          r = await this.$http.post('delete.php', { cliente: this.form });
        }
        else if (this.localState == 'criando') {
          r = await this.$http.post('create.php', { cliente: this.form });
        }
        else {
            // Esta linha só existe para prevenir erro do vuejs de variável criada e não utilizada.
            console.log(r);
        }

        if (r && r.status && r.status === 200) {
          this.$bvToast.toast(r.data.message, {
            title: 'Cadastro de Usuários',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'success'
          });

          console.log(r);
          this.$emit('getAll');
        }
        else {
          this.$bvToast.toast(r.data.message, {
            title: 'Cadastro de Usuários (Falha !)',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'danger'
          });

          console.log(r);
        }
      }
      else {
        return v;
      }
    },
    onReset() {
      this.$root.$emit('bv::hide::modal', 'modal-form-1');
    },
    validate() {
      // Verifica se a senha foi definida e confirmada
      if (this.form.senha_nova.length > 0) {
        if (this.form.senha_nova === this.form.senha_confirma) {
          this.form.senha = this.form.senha_nova;
        }
        else {
          this.$bvToast.toast('Senha informada e confirmação estão diferentes.', {
            title: 'Cadastro de Clientes',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'danger'
          });
          return false;
        }
      }
      else {
        if (this.localState === 'criando') {
          this.$bvToast.toast('É preciso definir a senha ao criar um novo cadastro.', {
            title: 'Cadastro de Clientes',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'danger'
          });
          return false;
        }
      }

      if (this.form.pessoafj === 'J') {
        if ( ! this.form.cnpj.length > 0) {
          this.$bvToast.toast('É preciso informar o CNPJ ao criar um novo cadastro.', {
            title: 'Cadastro de Clientes',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'danger'
          });

          return false;
        }
      }
      else if (this.form.pessoafj === 'F') {
        if ( ! this.form.cpf.length > 0) {
          this.$bvToast.toast('É preciso informar o CPF ao criar um novo cadastro.', {
            title: 'Cadastro de Clientes',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'danger'
          });

          return false;
        }
        else {
          this.form.cnpj = this.form.cpf;
        }
      }

      // Seta data de criação
      this.form.data_criacao = this.moment().format(this.$settings.format.date2db)

      return true;
    },
  },
  watch: {
    clienteObj: function () {
      this.$set(this, 'form', this.clienteObj);
    },
    state: function () {
      this.$set(this, 'localState', this.state);
    }
  }
}
</script>