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
                        <Form @getAll="getAll" :state="state" :usuarioObj="selecionado" />
                    </b-container>
                </b-col>
            </b-row>
        </b-modal>

        <b-container class="p-3" fluid>
            <b-row>
                <b-col md="12">

                    <b-row class="p-2">

                        <b-col sm="5" xs="12">
                            <h4 class="title">Usuários <span> </span></h4>
                        </b-col>

                        <b-col sm="7" xs="12" text-right>
                            <b-input-group>
                                <b-input-group-prepend>
                                    <b-button variant="default" @click="criar"><b-icon-plus-circle></b-icon-plus-circle> Adicionar</b-button>
                                </b-input-group-prepend>
                                <input type="text" class="form-control" placeholder="Pesquisar por Nº, Usuário, Plano ou Período">
                                <b-input-group-append>
                                    <b-button variant="default"><b-icon-archive></b-icon-archive> Pdf</b-button>
                                    <b-button variant="default"><b-icon-archive></b-icon-archive> Excel</b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-col>

                    </b-row>

                    <b-row class="p-2">
                        <b-table-simple responsive>
                            <b-thead>
                                <b-tr>
                                    <b-th>Acesso</b-th>
                                    <b-th>Nº</b-th>
                                    <b-th>Cadastro</b-th>
                                    <b-th>Usuário</b-th>
                                    <b-th>CPF</b-th>
                                    <b-th>T. Fixo</b-th>
                                    <b-th>T. Celular</b-th>
                                    <b-th class="text-center">Ação</b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <b-tr :key="usuario.id" v-for="usuario in usuarios">
                                    <b-td>
                                        <b-checkbox :checked="usuario.situacao == 'A'"></b-checkbox>
                                    </b-td>
                                    <b-td>{{ usuario.id }}</b-td>
                                    <b-td>{{ usuario.datacriacao }}</b-td>
                                    <b-td>{{ usuario.nome }}</b-td>
                                    <b-td>{{ usuario.cpf }}</b-td>
                                    <b-td>{{ usuario.telfixo }}</b-td>
                                    <b-td>{{ usuario.telcel1 }}</b-td>
                                    <b-td align="right">
                                        <b-button-group size="md">
                                            <b-button @click="consultar(usuario)"><b-icon-eye></b-icon-eye></b-button>
                                            <b-button @click="editar(usuario)"><b-icon-pencil-square></b-icon-pencil-square></b-button>
                                            <b-button @click="apagar(usuario)"><b-icon-trash></b-icon-trash></b-button>
                                            <b-button><b-icon-credit-card></b-icon-credit-card></b-button>
                                        </b-button-group>
                                    </b-td>
                                </b-tr>
                            </b-tbody>
                        </b-table-simple>
                    </b-row>

                </b-col>
            </b-row>
        </b-container>

    </div>
</template>

<script>
const
    emptyForm = {
        'idc': "",
        'idl': "",
        'idg': "",
        'acesso': "A",
        'nome': "",
        'sexo': "F",
        'rg': "",
        'cpf': "",
        'datanasc': "",
        'senha': "",
        'senha_nova': "",
        'senha_confirma': "",
        'email': "",
        'telfixo': "",
        'telcel1': "",
        'telcel2': "",
        'logradouro': "",
        'numero': "",
        'complemento': "",
        'bairro': "",
        'cidade': "",
        'uf': "",
        'cep': "",
        'foto': "imagem.png",
        'extrac1': "",
        'extrav1': "",
        'extrac2': "",
        'extrav2': "",
        'semana': "",
        'horae': "",
        'horaia': "",
        'horafa': "",
        'horas': "",
        'datacriacao': "",
    };

import Form from './Form.vue';

export default {
    name: "Index",
    components: {
        Form
    },
    data() {
        return {
            usuarios: [],
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
                    s = 'Criando um novo ';
                    break;
                case 'editando':
                    s = 'Editando dados do ';
                    break;
                case 'apagando':
                    s = 'Apagando dados do ';
                    break;
            }
            s = s + 'Usuário';
            return s;
        }
    },
    mounted() {
        this.getAll()
    },
    methods: {
        async getAll() {
            let r1 = await this.$http.get('load_usuarios.php');

            this.$set(this, 'usuarios', r1.data);
            this.$set(this, 'carregando', false);
            this.$set(this, 'modalShow', false);
        },
        consultar(usuario) {
            this.$set(this, 'selecionado', usuario);
            this.$set(this, 'state', 'selecionado');
            this.$set(this, 'modalShow', true);
        },
        criar() {
            this.$set(this, 'selecionado', Object.assign({}, emptyForm));
            this.$set(this, 'state', 'criando');
            this.$set(this, 'modalShow', true);
        },
        editar(usuario) {
            this.$set(this, 'selecionado', usuario);
            this.$set(this, 'state', 'editando');
            this.$set(this, 'modalShow', true);
        },
        apagar(usuario) {
            this.$set(this, 'selecionado', usuario);
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