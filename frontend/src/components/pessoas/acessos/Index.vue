<template>
    <b-container fluid class="p-0 m-0 h-100">

        <!-- Datatable with existing data -->
        <b-row class="p-1 small">
            <b-col md="12">
                <b-table-simple id="table-acessos" borderless responsive small striped>
                    <b-tbody style="height: 200px; display: block; overflow: auto; ">
                        <b-tr>
                            <b-th width="99%" class="bg-info text-white text-center">Chave de Acesso</b-th>
                            <b-th width="1%" class="bg-info text-white text-center">{{ showEditingControllers() ? "Ação" : "" }}</b-th>
                        </b-tr>
                        <b-tr :key="acesso.id" v-for="acesso in acessos">
                            <b-td align="left">{{ acesso.chave }}</b-td>
                            <b-td align="right" nowrap>
                                <b-icon-pencil-square
                                        @click="editar(acesso)"
                                        class="btn btn-large p-0 text-primary"
                                        v-if="showEditingControllers()"
                                ></b-icon-pencil-square>&nbsp;
                                <b-icon-trash
                                        @click="apagar(acesso)"
                                        class="btn btn-large p-0 text-danger"
                                        v-if="showEditingControllers()"
                                ></b-icon-trash>
                            </b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
            </b-col>
        </b-row>
        <!-- End of datatable -->

        <!-- Toolbar -->
        <b-row class="p-1 small" v-if="showEditingControllers()">
            <b-col md="12">
                <b-card-group class="shadow-lg">
                    <b-card no-body class="bg-light">
                        <b-card-body class="p-1 m-1 bg-light">
                            <b-row v-if="state === 'editando' || state === 'criando'">
                                <b-col md="12">
                                    <Form
                                            @getAll="getAll"
                                            @cancelState="cancelState"
                                            :pessoaId="pessoaId"
                                            :state="state"
                                            :acessoObj="selecionado" />
                                </b-col>
                            </b-row>
                            <b-row v-else>
                                <b-col md="6">
                                    <b-button
                                            @click="criar"
                                            variant="outline-success"
                                            size="sm"
                                    >Adicionar {{ acessos.length > 0 ? "um Novo " : ""}}Acesso</b-button>
                                </b-col>
                                <b-col md="6" align="right">
                                    <CloseButtom size="sm" />
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
    import Form from './Form.vue';
    import CloseButtom from "@/components/app/frontpage/CloseButtom";

    const emptyForm = {
        'id': 0,
        'pessoa:': null,
        'chave': "",
        'senha': ""
    };

    export default {
        name: "Index",
        components: {
            CloseButtom,
            Form
        },
        props: {
            pessoaId: {
                type: Number,
                required: false
            },
            pessoaState: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                title: "Cadastro de Pessoas / Acessos",
                acessos: [],
                state: undefined,
                selecionado: undefined,
                carregando: true,
                form: emptyForm
            }
        },
        mounted() {
            this.getAll();
        },
        methods: {
            async getAll() {
                let r = await this.$http.get('acesso/loadAllByPersonId/' + this.pessoaId);

                if (r.status && r.status === 'error') {
                    return;
                }

                this.$set(this, 'acessos', r.data);
                this.$set(this, 'carregando', false);

                this.$set(this, 'form', emptyForm);
            },
            criar() {
                this.$set(this, 'selecionado', Object.assign({}, emptyForm));
                this.$set(this, 'state', 'criando');
            },
            editar(acesso) {
                this.$set(this, 'selecionado', acesso);
                this.$set(this, 'state', 'editando');
            },
            async apagar(acesso) {
                // Faz o post para o backend
                let id = acesso.id;
                let r = await this.$http.delete('acesso/' + id, this.form);

                if (r && r.status && r.status === 200) {

                    if (r.data.status === 'error') {
                        this.$bvToast.toast(r.data.data, {
                            title: this.title,
                            autoHideDelay: this.$settings.config.toastErrorTimeout,
                            appendToast: true,
                            variant: 'danger'
                        });
                    } else {
                        this.$bvToast.toast(r.data.data.text, {
                            title: this.title,
                            autoHideDelay: this.$settings.config.toastErrorTimeout,
                            appendToast: true,
                            variant: 'success'
                        });
                    }
                }

                this.getAll();
            },
            dismissFormData() {
                this.$root.$emit('bv::hide::modal', 'modal-form-1');
            },
            showEditingControllers() {
                return (this.pessoaState === 'criando' || this.pessoaState === 'editando');
            },
            cancelState() {
                this.$set(this, 'selecionado', undefined);
                this.$set(this, 'state', undefined);
            }
        }
    }
</script>

<style>
    #table-acessos {
        height: 200px;
        max-height: 200px;
    }
</style>