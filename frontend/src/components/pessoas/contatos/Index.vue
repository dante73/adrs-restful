<template>
    <b-container class="p-0 m-0 h-100" fluid="xl">

        <!-- Datatable with existing data -->
        <b-row class="p-1 small">
            <b-col md="12">
                <b-table-simple id="table-contatos" borderless small striped>
                    <b-tbody style="height: 200px; display: block; overflow: auto; ">
                        <b-tr>
                            <b-th width="33%" class="bg-info text-white text-center">Meio de contato</b-th>
                            <b-th class="bg-info text-white text-center">Identificação</b-th>
                            <b-th width="1%" class="bg-info text-white text-center">{{ showEditingControllers() ? "Ação" : "" }}</b-th>
                        </b-tr>
                        <b-tr :key="contato.id" v-for="contato in contatos">
                            <b-td class="text-right">
                                <strong>
                                    {{ $support.typeTextByKey($settings.tipos.contatos, contato.tipo) }}
                                </strong>
                            </b-td>
                            <b-td class="text-left">{{ contato.valor }}</b-td>
                            <b-td class="text-right" nowrap>
                                <b-icon-pencil-square
                                        @click="change(contato)"
                                        class="btn btn-large p-0 text-primary"
                                        v-if="showEditingControllers()"
                                ></b-icon-pencil-square>&nbsp;
                                <b-icon-trash
                                        @click="remove(contato)"
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
                                            @getAllFunction="getAllData"
                                            @cancelState="cancelState"
                                            :pessoaId="pessoaId"
                                            :state="state"
                                            :contatoObj="selected" />
                                </b-col>
                            </b-row>
                            <b-row v-else>
                                <b-col md="6">
                                    <b-button
                                            @click="create"
                                            variant="outline-success"
                                            size="sm"
                                    >Adicionar {{ contatos.length > 0 ? "um Novo " : ""}}Contato</b-button>
                                </b-col>
                                <b-col md="6" class="text-right">
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
        'tipo': "",
        'valor': ""
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
                type: Number,
                required: true
            }
        },
        data() {
            return {
                title: "Cadastro de Pessoas / Contatos",
                contatos: [],
                model: 'contato',
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
                let r = await this.$http.get(this.model + '/loadAllByPersonId/' + this.pessoaId);

                if (r.status && r.status === 'error') {
                    return;
                }

                this.$set(this, 'contatos', r.data);
                this.$set(this, 'form', emptyForm);
            },
            create() {
                this.$set(this, 'selected', Object.assign({}, emptyForm));
                this.$set(this, 'state', this.$support.st.CREATING);
            },
            change(contato) {
                this.$set(this, 'selected', contato);
                this.$set(this, 'state', this.$support.st.UPDATING);
            },
            async remove(contato) {
                // Faz o post para o backend
                let id = contato.id;
                let r = await this.$http.delete(this.model + '/' + id, this.form);

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

                await this.getAllData();
            },
            dismissFormData() {
                this.$root.$emit('bv::hide::modal', 'modal-form-1');
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
    #table-contatos {
        height: 200px;
        max-height: 200px;
    }
</style>