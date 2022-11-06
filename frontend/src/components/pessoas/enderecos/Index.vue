<template>
    <b-container fluid class="p-0 m-0 h-100">

        <!-- Form to edit or change a record data -->
        <b-row v-if="state === 'editando' || state === 'criando'">
            <b-col md="12">
                <Form
                        @getAll="getAll"
                        @cancelState="cancelState"
                        :pessoaId="pessoaId"
                        :state="state"
                        :enderecoObj="selecionado"
                />
            </b-col>
        </b-row>
        <!-- End of form -->

        <!-- Datatable with existing data -->
        <b-row class="p-1 small" v-else>
            <b-col md="12">
                <b-table-simple id="table-enderecos" borderless responsive small striped>
                    <b-tbody style="height: 200px; display: block; overflow: auto;">
                        <b-tr :key="endereco.id" v-for="endereco in enderecos">
                            <b-td>
                                {{ endereco.rua }}, {{ endereco.numero }}<br/>
                                {{ endereco.bairro }} - {{ endereco.cidade_nome }}<br/>
                                {{ endereco.estado_nome }} - Cep. {{ endereco.cep }}
                            </b-td>
                            <b-td width="1%" align="right" nowrap>
                                <b-icon-pencil-square
                                        @click="editar(endereco)"
                                        class="btn btn-large p-0 text-primary"
                                        v-if="showEditingControllers()"
                                ></b-icon-pencil-square>&nbsp;
                                <b-icon-trash
                                        @click="apagar(endereco)"
                                        class="btn btn-large p-0 text-danger"
                                        v-if="showEditingControllers()"
                                ></b-icon-trash>
                            </b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
            </b-col>
        </b-row>
        <!-- End of Datatable -->

        <!-- Toolbar -->
        <b-row class="p-1 small" v-if="showEditingControllers() && state !== 'editando' && state !== 'criando'">
            <b-col md="12">
                <b-card-group class="shadow-lg">
                    <b-card no-body class="bg-light">
                        <b-card-body class="p-1 m-1 bg-light">
                            <b-row v-if="state !== 'editando' || state !== 'criando'">
                                <b-col md="6">
                                    <b-button
                                            @click="criar"
                                            variant="outline-success"
                                            size="sm"
                                    >Adicionar {{ enderecos.length > 0 ? "um Novo " : "" }}Endereço</b-button>
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
        <!-- End of toolbar -->

    </b-container>
</template>

<script>
    import Form from './Form.vue';
    import CloseButtom from "@/components/app/frontpage/CloseButtom";

    const emptyForm = {
        'id': 0,
        'pessoa': null,
        'cep': "",
        'rua': "",
        'numero': "",
        'bairro': "",
        'cidade': null,
        'principal': false
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
                required: true
            },
            pessoaState: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                title: "Cadastro de Pessoas / Endereços",
                enderecos: [],
                state: undefined,
                selecionado: undefined,
                carregando: true,
                form: emptyForm
            }
        },
        computed: {
        },
        mounted() {
            this.getAll();
        },
        methods: {
            async getAll() {
                let r = await this.$http.get('endereco/loadAllByPersonId/' + this.pessoaId);

                if (r.status && r.status === 'error') {
                    return;
                }

                this.$set(this, 'enderecos', r.data);
                this.$set(this, 'carregando', false);

                this.$set(this, 'form', emptyForm);
            },
            criar() {
                this.$set(this, 'selecionado', Object.assign({}, emptyForm));
                this.$set(this, 'state', 'criando');
            },
            editar(endereco) {
                this.$set(this, 'selecionado', endereco);
                this.$set(this, 'state', 'editando');
            },
            async apagar(endereco) {
                // Faz o post para o backend
                let id = endereco.id;
                let r = await this.$http.delete('endereco/' + id, this.form);

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
    #table-enderecos {
        height: 200px;
        max-height: 200px;
    }
</style>