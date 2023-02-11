<template>
    <b-container class="p-0 m-0 h-100" fluid="xl">

        <!-- Datatable with existing data -->
        <b-row class="p-1 small">
            <b-col md="12">
                <b-table-simple id="table-acessos" borderless small striped>
                    <b-tbody style="height: 200px; display: block; overflow: auto; ">
                        <b-tr>
                            <b-th width="99%" class="bg-info text-white text-center">Chave de Acesso</b-th>
                            <b-th width="1%" class="bg-info text-white text-center">{{ showEditingControllers() ? "Ação" : "" }}</b-th>
                        </b-tr>
                        <b-tr :key="acesso.id" v-for="acesso in acessos">
                            <b-td align="left">{{ acesso.chave }}</b-td>
                            <b-td align="right" nowrap>
                                <b-icon-pencil-square
                                        @click="change(acesso)"
                                        class="btn btn-large p-0 text-primary"
                                        v-if="showEditingControllers()"
                                ></b-icon-pencil-square>&nbsp;
                                <b-icon-trash
                                        @click="remove(acesso)"
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
                            <b-row v-if="state === $support.st.UPDATING || state === $support.st.CREATING">
                                <b-col md="12">
                                    <Form
                                            @get-all-function="getAllData"
                                            @cancel-state="cancelState"
                                            :pessoaId="pessoaId"
                                            :state="state"
                                            :acessoObj="selected" />
                                </b-col>
                            </b-row>
                            <b-row v-else>
                                <b-col md="6">
                                    <b-button
                                            @click="create"
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
                required: true
            },
            pessoaState: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                title: "Cadastro de Pessoas / Acessos",
                acessos: [],
                model: 'acesso',
                state: undefined,
                selected: undefined,
                form: emptyForm
            }
        },
        mounted() {
            this.getAllData();
        },
        methods: {
            async getAllData() {
                let r = await this.$http.get(this.model + '/' + this.pessoaId + '/loadAllByPersonId');

                if (r.status && r.status === 'error') {
                    return false;
                }

                this.$set(this, 'acessos', r.data);
                this.$set(this, 'form', emptyForm);
            },
            create() {
                this.$set(this, 'selected', Object.assign({}, emptyForm));
                this.$set(this, 'state', this.$support.st.CREATING);
            },
            change(acesso) {
                this.$set(this, 'selected', acesso);
                this.$set(this, 'state', this.$support.st.UPDATING);
            },
            async remove(acesso) {
                // Faz o post para o backend
                let id = acesso.id;
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
                            autoHideDelay: this.$settings.config.toastErrorTimeout,
                            appendToast: true,
                            variant: 'success'
                        });
                    }
                }

                await this.getAllData();
            },
            showEditingControllers() {
                let st = this.$support.st;
                return (this.pessoaState === st.UPDATING || this.pessoaState === st.CREATING);
            },
            cancelState() {
                this.$set(this, 'selected', undefined);
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
