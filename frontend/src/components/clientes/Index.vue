<template>
    <div>

        <b-modal
                id="modal-form-1"
                size="xl"
                :title="titulo"
                centered
                v-model="modalShow"
                @ok="handleOk"
                @close="handleClose"
                no-close-on-backdrop ok-only
        >
            <b-row>
                <b-col md="12" class="p-0">
                    <b-container fluid class="bg-light p-0">
                        <Form @getAll="getAll" :state="state" :clienteObj="selecionado" />
                    </b-container>
                </b-col>
            </b-row>
        </b-modal>

        <b-container class="p-3" fluid>
            <b-row>
                <b-col md="12">

                    <b-row class="p-2">

                        <b-col sm="5" xs="12">
                            <h4 class="title">Clientes <span> </span></h4>
                        </b-col>

                        <b-col sm="7" xs="12" text-right>
                            <b-input-group>
                                <b-input-group-prepend>
                                    <b-button variant="default" @click="criar"><b-icon-plus-circle></b-icon-plus-circle> Adicionar</b-button>
                                </b-input-group-prepend>
                                <input type="text" class="form-control" placeholder="Pesquisar por Nº, Cliente, Plano ou Período">
                                <b-input-group-append>
                                    <b-button variant="default"><b-icon-archive></b-icon-archive> Pdf</b-button>
                                    <b-button variant="default"><b-icon-archive></b-icon-archive> Excel</b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-col>

                    </b-row>

                    <b-row class="p-2">
                        <b-col>
                            <b-table-simple responsive>
                                <b-thead>
                                    <b-tr variant="success" class="">
                                        <b-th>Status</b-th>
                                        <b-th>ID Cliente</b-th>
                                        <b-th>Cadastro</b-th>
                                        <b-th>Cliente</b-th>
                                        <b-th>CNPJ/CPF</b-th>
                                        <b-th>T. Comercial</b-th>
                                        <b-th>T. Celular</b-th>
                                        <b-th class="text-center">Ação</b-th>
                                    </b-tr>
                                </b-thead>
                                <b-tbody>
                                    <b-tr :key="cliente.numero" v-for="cliente in clientes">
                                        <b-td>
                                            <b-checkbox :checked="cliente.situacao == 'A'"></b-checkbox>
                                        </b-td>
                                        <b-td>{{ cliente.idc }}</b-td>
                                        <b-td>{{ moment(cliente.data_criacao).format($settings.format.date) }}</b-td>
                                        <b-td>{{ cliente.nome_fantasia }}</b-td>
                                        <b-td>{{ cliente.cnpj }}</b-td>
                                        <b-td>{{ cliente.telefone_comercial1 }}</b-td>
                                        <b-td>{{ cliente.telefone_celular1 }}</b-td>
                                        <b-td align="right">
                                            <b-button-group size="md">
                                                <b-button @click="consultar(cliente)" variant="info"><b-icon-eye></b-icon-eye></b-button>
                                                <b-button @click="editar(cliente)" variant="warning"><b-icon-pencil-square></b-icon-pencil-square></b-button>
                                                <b-button @click="apagar(cliente)" variant="danger"><b-icon-trash></b-icon-trash></b-button>
                                                <b-button><b-icon-credit-card></b-icon-credit-card></b-button>
                                            </b-button-group>
                                        </b-td>
                                    </b-tr>
                                </b-tbody>
                            </b-table-simple>
                        </b-col>
                    </b-row>

                </b-col>
            </b-row>
        </b-container>

    </div>
</template>

<script>
const
    emptyForm = {
        'pessoafj': "J",
        'idc': "",
        'idl': "",
        'tipo': "C",
        'situacao': "A",
        'nome_fantasia': "",
        'razao_social': "",
        'cnpj': "",
        'inscricao_estadual': "",
        'inscricao_municipal': "",
        'tipo_contribuinte': "I",
        'telefone_comercial1': "",
        'telefone_comercial2': "",
        'telefone_celular1': "",
        'telefone_celular2': "",
        'email_empresa': "",
        'email_comercial': "",
        'email_financeiro': "",
        'email_diretoria': "",
        'email_ti': "",
        'senha': "",
        'senha_nova': "",
        'senha_confirma': "",
        'foto': "imagem.png",
        'data_criacao': ""
    };

import Form from './Form.vue';

export default {
    name: "Index",
    components: {
        Form
    },
    data() {
        return {
            clientes: [],
            state: undefined,
            selecionado: undefined,
            carregando: true,
            modalShow: false,
        }
    },
    computed: {
        titulo() {
            let s;
            switch (this.state) {
                case 'selecionado':
                    s = 'Detalhes do ';
                    break;
                case 'criando':
                    s = 'Cadastrando um novo ';
                    break;
                case 'editando':
                    s = 'Editando dados do ';
                    break;
                case 'apagando':
                    s = 'Apagando dados do ';
                    break;
            }
            s = s + 'cliente';
            return s;
        }
    },
    mounted() {
        this.getAll()
    },
    methods: {
        async getAll() {
            let r1 = await this.$http.get('pessoa/all');

            this.$set(this, 'clientes', r1.data);
            this.$set(this, 'carregando', false);
            this.$set(this, 'modalShow', false);
        },
        consultar(cliente) {
            this.$set(this, 'selecionado', cliente);
            this.$set(this, 'state', 'selecionado');
            this.$set(this, 'modalShow', true);
        },
        criar() {
            this.$set(this, 'selecionado', Object.assign({}, emptyForm));
            this.$set(this, 'state', 'criando');
            this.$set(this, 'modalShow', true);
        },
        editar(cliente) {
            this.$set(this, 'selecionado', cliente);
            this.$set(this, 'state', 'editando');
            this.$set(this, 'modalShow', true);
        },
        apagar(cliente) {
            this.$set(this, 'selecionado', cliente);
            this.$set(this, 'state', 'apagando');
            this.$set(this, 'modalShow', true);
        },
        handleClose() {
            this.$set(this, 'modalShow', false);
        },
        handleOk() {
            this.$set(this, 'modalShow', false);
        }
    }
}
</script>