<template>
  <div>

    <b-form @submit="onSubmit" @reset="onReset">
      <b-container fluid="md">
        <b-card no-body>

          <!-- Topo do Formulário, acima das tabs -->
          <b-card-header>
            <b-row>

              <!-- Id Cliente -->
              <b-col md="1">
                <b-form-group id="input-group-01" label="Idc" label-for="input-01">
                  <b-form-input
                          id="input-01"
                          v-model="form.idc"
                          v-mask="$settings.masks.idc"
                          placeholder="Informe o ID Cliente"
                          class="text-uppercase"
                          required
                  ></b-form-input>
                </b-form-group>
              </b-col>

              <!-- Id Loja -->
              <b-col md="1">
                <b-form-group id="input-group-02" label="Loja" label-for="input-02">
                  <b-form-input
                          id="input-02"
                          v-model="form.idl"
                          v-mask="'###'"
                          placeholder="Id Loja"
                          required
                  ></b-form-input>
                </b-form-group>
              </b-col>

              <!-- Id Grupo -->
              <b-col md="1">
                <b-form-group id="input-group-03" label="Grupo" label-for="input-03">
                  <b-form-input
                          id="input-03"
                          v-model="form.idg"
                          placeholder="Grupo"
                          required
                  ></b-form-input>
                </b-form-group>
              </b-col>

              <!-- Acesso Ativo/Inativo -->
              <b-col md="2">
                <b-form-group id="radio-group-1" label="Acesso" label-for="radio-1" class="float-md-right">
                  <b-form-radio-group
                          id="radio-1"
                          v-model="form.acesso"
                          :options="$settings.tipos.acesso"
                          name="radio-btn-acesso"
                          button-variant="outline-primary"
                          buttons
                  ></b-form-radio-group>
                </b-form-group>
              </b-col>

              <!-- Nome do Usuário -->
              <b-col md="4">
                <b-form-group id="input-group-1" label="Nome do Usuário" label-for="input-1">
                  <b-form-input
                          id="input-1"
                          v-model="form.nome"
                          placeholder="Informe Nome"
                          required
                  ></b-form-input>
                </b-form-group>
              </b-col>

              <!-- Sexo -->
              <b-col md="3">
                <b-form-group id="radio-group-2" label="Sexo" label-for="radio-2" class="float-md-right">
                  <b-form-radio-group
                          id="radio-2"
                          v-model="form.sexo"
                          :options="$settings.tipos.sexo"
                          name="radio-btn-sexo"
                          button-variant="outline-primary"
                          buttons
                  ></b-form-radio-group>
                </b-form-group>
              </b-col>

            </b-row>
          </b-card-header>

          <!-- Tabs por Subject -->
          <b-tabs card>

            <b-tab title="Documentação">

              <!-- RG -->
              <b-row>
                <b-col md="4" offset-md="2">
                  <b-form-group id="input-group-2" label="RG" label-for="input-2">
                    <b-form-input
                            id="input-2"
                            v-model="form.rg"
                            v-mask="$settings.masks.rg"
                            placeholder="Informe o RG"
                            required
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

              <!-- CPF -->
              <b-row>
                <b-col md="4" offset-md="2">
                  <b-form-group id="input-group-3" label="CPF" label-for="input-3">
                    <b-form-input
                            id="input-3"
                            v-model="form.cpf"
                            v-mask="$settings.masks.cpf"
                            placeholder="Informe o CPF"
                            required
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

              <!-- Data de Nascimento -->
              <b-row>
                <b-col md="4" offset-md="2">
                  <b-form-group id="input-datepicker-1" label="Data de Nascimento" label-for="datepicker-1">
                    <b-form-datepicker
                            id="datepicker-1"
                            v-model="form.datanasc"
                            class="mb-2"
                    ></b-form-datepicker>
                  </b-form-group>
                </b-col>
              </b-row>

            </b-tab>

            <b-tab title="Senha">

              <!-- Senha Nova -->
              <b-row>
                <b-col md="4" offset-md="4" class="pt-2 pb-2">
                  <b-form-group
                          id="input-group-4"
                          label="Informe a Senha"
                          label-for="input-4"
                          description="Só informe a senha se quiser modificá-la."
                  >
                    <b-form-input
                            id="input-4"
                            v-model="form.senha_nova"
                            placeholder="Informe a senha"
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

              <!-- Confirmação da Senha -->
              <b-row>
                <b-col md="4" offset-md="4" class="pb-2 pt-2">
                  <b-form-group
                          id="input-group-5"
                          label="Confirme a Senha"
                          label-for="input-5"
                          description="Só confirme a senha se quiser modificá-la."
                  >
                    <b-form-input
                            id="input-5"
                            v-model="form.senha_confirma"
                            placeholder="Confirme a senha"
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

            </b-tab>

            <b-tab title="Contatos">

              <!-- Email -->
              <b-row>
                <b-col md="6" offset-md="3">
                  <b-form-group
                          id="input-group-13"
                          label="Email"
                          label-for="input-13"
                          description="Nunca compartilhamos seu email com ninguém."
                  ><b-form-input
                          id="input-13"
                          v-model="form.email"
                          type="email"
                          placeholder="Email de Contato"
                          required
                  ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

              <!-- Telefone Fixo -->
              <b-row>
                <b-col md="3" offset-md="3">
                  <b-form-group id="input-group-14" label="Telefone Fixo" label-for="input-14">
                    <b-form-input
                            id="input-14"
                            v-model="form.telfixo"
                            v-mask="$settings.masks.telefone"
                            placeholder="Telefone fixo"
                            required
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

              <!-- Telefones Celulares -->
              <b-row>
                <b-col md="3" offset-md="3">
                  <b-form-group id="input-group-15" label="Celular" label-for="input-15">
                    <b-form-input
                            id="input-15"
                            v-model="form.telcel1"
                            v-mask="$settings.masks.celular"
                            placeholder="Telefone celular"
                            required
                    ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col md="3">
                  <b-form-group id="input-group-16" label="Celular" label-for="input-16">
                    <b-form-input
                            id="input-16"
                            v-model="form.telcel2"
                            v-mask="$settings.masks.celular"
                            placeholder="Telefone Celular"
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

            </b-tab>

            <b-tab title="Endereço">

              <b-row>

                <!-- Endereço -->
                <b-col md="4" offset-md="2">
                  <b-form-group
                          id="input-group-6"
                          label="Logradouro"
                          label-for="input-6"
                  ><b-form-input
                          id="input-6"
                          v-model="form.logradouro"
                          placeholder="Nome da Rua"
                          required
                  ></b-form-input>
                  </b-form-group>
                </b-col>

                <!-- Número -->
                <b-col md="2">
                  <b-form-group
                          id="input-group-7"
                          label="Número"
                          label-for="input-7"
                  ><b-form-input
                          id="input-7"
                          v-model="form.numero"
                          placeholder="Número"
                          required
                  ></b-form-input>
                  </b-form-group>
                </b-col>

                <!-- Complemento -->
                <b-col md="2">
                  <b-form-group
                          id="input-group-8"
                          label="Complemento"
                          label-for="input-8"
                  ><b-form-input
                          id="input-8"
                          v-model="form.complemento"
                          placeholder="Complemento"
                  ></b-form-input>
                  </b-form-group>
                </b-col>

              </b-row>

              <b-row>

                <!-- Bairro -->
                <b-col md="4" offset-md="2">
                  <b-form-group
                          id="input-group-9"
                          label="Bairro"
                          label-for="input-9"
                  ><b-form-input
                          id="input-9"
                          v-model="form.bairro"
                          placeholder="Bairro"
                  ></b-form-input>
                  </b-form-group>
                </b-col>

                <!-- Cidade -->
                <b-col md="3">
                  <b-form-group
                          id="input-group-10"
                          label="Cidade"
                          label-for="input-10"
                  ><b-form-input
                          id="input-10"
                          v-model="form.cidade"
                          placeholder="Cidade"
                  ></b-form-input>
                  </b-form-group>
                </b-col>

                <!-- Estado -->
                <b-col md="1">
                  <b-form-group
                          id="input-group-11"
                          label="Estado"
                          label-for="input-11"
                  ><b-form-input
                          id="input-11"
                          v-model="form.uf"
                          placeholder="Estado"
                  ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

              <!-- CEP -->
              <b-row>
                <b-col md="2" offset-md="2">
                  <b-form-group
                          id="input-group-12"
                          label="Cep"
                          label-for="input-12"
                  ><b-form-input
                          id="input-12"
                          v-model="form.cep"
                          v-mask="$settings.masks.cep"
                          placeholder="Cep"
                  ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

            </b-tab>

            <!-- Foto -->
            <b-tab title="Foto">
              <b-row>
                <b-col md="4" offset-md="4" align-center>
                  <b-form-group id="input-group-17" label="Nome da Foto" label-for="input-17" class="py-5">
                    <b-form-input
                            id="input-17"
                            v-model="form.foto"
                            placeholder="Informe a Foto"
                            required
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-tab>

            <!-- Extras -->
            <b-tab title="Extras">

              <!-- Campo e Valor Extra 1 -->
              <b-row>
                <b-col md="4" offset-md="2">
                  <b-form-group id="input-group-18" label="Campo Extra I" label-for="input-18" class="py-2">
                    <b-form-input
                            id="input-18"
                            v-model="form.extrac1"
                            placeholder="Campo Extra I"
                    ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col md="4">
                  <b-form-group id="input-group-19" label="Valor Extra I" label-for="input-19" class="py-2">
                    <b-form-input
                            id="input-19"
                            v-model="form.extrav1"
                            placeholder="Valor Extra I"
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

              <!-- Campo e Valor Extra 2 -->
              <b-row>
                <b-col md="4" offset-md="2">
                  <b-form-group id="input-group-20" label="Campo Extra II" label-for="input-20" class="py-2">
                    <b-form-input
                            id="input-20"
                            v-model="form.extrac2"
                            placeholder="Campo Extra II"
                    ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col md="4">
                  <b-form-group id="input-group-20" label="Valor Extra II" label-for="input-20" class="py-2">
                    <b-form-input
                            id="input-20"
                            v-model="form.extrav2"
                            placeholder="Valor Extra II"
                    ></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>

            </b-tab>

          </b-tabs>

          <b-card-footer>
            <b-row>
              <b-col md="5" offset-md="1">
                <b-button type="submit" variant="primary">Gravar</b-button>
              </b-col>
              <b-col md="5" align="right">
                <b-button type="reset" variant="danger">Abandonar</b-button>
              </b-col>
            </b-row>
          </b-card-footer>

        </b-card>
      </b-container>
    </b-form>

  </div>
</template>

<script>
export default {
  data() {
    return {
      form: this.usuarioObj,
      localState: this.state
    }
  },
  props: {
    usuarioObj: {
      type: Object,
      required: false
    },
    state: {
      type: String,
      required: false
    }
  },
  mounted() {
    // Alguns fields que só existem localmente precisam ser inicializados
    if ( ! this.form.senha_nova) {
      this.$set(this.form, 'senha_nova', "");
    }
    if ( ! this.form.senha_confirma) {
      this.$set(this.form, 'senha_confirma', "");
    }
  },
  methods: {
    async onSubmit(ev) {
      ev.preventDefault();

      // Valida o formulário
      let v = this.validated();

      // Só posta se estiver tudo ok
      if (v) {
        let r;

        // Faz o post para o backend
        if (this.localState == 'editando') {
          r = await this.$http.post('update.php', { usuario: this.form });
        }
        else if (this.localState == 'apagando') {
          r = await this.$http.post('delete.php', { usuario: this.form });
        }
        else if (this.localState == 'criando') {
          r = await this.$http.post('create.php', { usuario: this.form });
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
    validated() {

      // Verifica se a senha foi definida e confirmada
      if (this.form.senha_nova.length > 0) {
        if (this.form.senha_nova === this.form.senha_confirma) {
          this.form.senha = this.form.senha_nova;
        }
        else {
          this.$bvToast.toast('Senha informada e confirmação estão diferentes.', {
            title: 'Cadastro de Usuários',
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
            title: 'Cadastro de Usuários',
            autoHideDelay: 5000,
            appendToast: true,
            variant: 'danger'
          });
          return false;
        }
      }

      // Limpa o cep
      this.form.cep = this.form.cep.replace(this.$settings.regexp.limpa_valor, '');

      // Seta data de criação
      this.form.datacriacao = this.moment().format(this.$settings.format.date2db)

      return true;
    }
  },
  watch: {
    usuarioObj: function () {
      this.$set(this, 'form', this.usuarioObj);
    },
    state: function () {
      this.$set(this, 'localState', this.state);
    }
  }
}
</script>