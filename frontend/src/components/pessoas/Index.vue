<template>
    <div class="small">

        <b-modal
                id="modal-form-1"
                size="lg"
                :title="titulo"
                v-model="modalShow"
                @close="handleClose"
                no-close-on-backdrop
                hide-footer
        >
            <b-row>
                <b-col md="12" class="p-0 m-0">
                    <b-container fluid class="p-0 m-0">
                        <Form @getAll="getAll" :state="state" :pessoaObj="selecionado" />
                    </b-container>
                </b-col>
            </b-row>
        </b-modal>

        <b-container class="p-3" fluid>
            <b-row>
                <b-col md="12">

                    <b-row class="p-2">

                        <b-col sm="5" xs="12">
                            <h4 class="title">Cadastro de Pessoas<span></span></h4>
                        </b-col>

                        <b-col sm="7" xs="12" align="right">
                            <b-button variant="success" @click="criar"><b-icon-plus-circle></b-icon-plus-circle></b-button>
                            <!--
                            <b-input-group>
                                <b-input-group-prepend>
                                    <b-button variant="default" @click="criar"><b-icon-plus-circle></b-icon-plus-circle> Adicionar</b-button>
                                </b-input-group-prepend>
                                <input type="text" class="form-control" placeholder="Pesquisar por nome">
                                <b-input-group-append>
                                    <b-button variant="default"><b-icon-archive></b-icon-archive> Pdf</b-button>
                                    <b-button variant="default"><b-icon-archive></b-icon-archive> Excel</b-button>
                                </b-input-group-append>
                            </b-input-group>
                            -->
                        </b-col>

                    </b-row>

                    <b-row class="p-2">
                        <b-col>
                            <b-table-simple responsive>
                                <b-thead>
                                    <b-tr variant="default" class="">
                                        <b-th>#</b-th>
                                        <b-th>&nbsp;</b-th>
                                        <b-th><b-icon icon="camera-fill" aria-hidden="true"></b-icon></b-th>
                                        <b-th class="text-center">Nome</b-th>
                                        <b-th class="text-center">Data de Nascimento</b-th>
                                        <b-th class="text-center" width="1%">Ação</b-th>
                                    </b-tr>
                                </b-thead>
                                <b-tbody>
                                    <b-tr :key="pessoa.numero" v-for="pessoa in pessoas">
                                        <b-td>{{ pessoa.id }}</b-td>
                                        <b-td>
                                            <b-icon :icon="genderIcon(pessoa.genero)" aria-hidden="true"></b-icon>
                                        </b-td>
                                        <b-td>
                                            <b-icon v-if="pessoa.imagem" :icon="photoIcon()" aria-hidden="true"></b-icon>
                                        </b-td>
                                        <b-td>{{ pessoa.nome }}</b-td>
                                        <b-td>{{ moment(pessoa.nascimento).format($settings.format.date) }}</b-td>
                                        <b-td align="right">
                                            <b-button-group size="sm">
                                                <b-button @click="consultar(pessoa)" variant="info"><b-icon-eye></b-icon-eye></b-button>
                                                <b-button @click="editar(pessoa)" variant="warning"><b-icon-pencil-square></b-icon-pencil-square></b-button>
                                                <b-button @click="apagar(pessoa)" variant="danger"><b-icon-trash></b-icon-trash></b-button>
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
const emptyForm = {
        'id': 0,
        'name': "",
        'gender': "",
        'birthday': ""
    };

//import axios from 'axios';
import Form from './Form.vue';

export default {
    name: "Index",
    components: {
        Form
    },
    data() {
        return {
            pessoas: [],
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
                    s = 'Detalhes da ';
                    break;
                case 'criando':
                    s = 'Cadastrando uma nova ';
                    break;
                case 'editando':
                    s = 'Editando dados da ';
                    break;
                case 'apagando':
                    s = 'Apagando dados da ';
                    break;
            }
            s = s + 'pessoa';
            return s;
        }
    },
    mounted() {
        this.getAll()
    },
    methods: {
        async getAll() {
            let r1 = await this.$http.get('pessoa');

            // It doesn't work (CORS problems) :
            //let r1 = await axios.get("http://localhost:8000/pessoa");

            // It works :
            //let r1 = await fetch("http://localhost:8000/pessoa");
            //let r2 = await r1.json();
            //console.log(r1);
            //console.log(r2.data);

            this.$set(this, 'pessoas', r1.data);
            this.$set(this, 'carregando', false);
            this.$set(this, 'modalShow', false);
        },
        consultar(pessoa) {
            this.$set(this, 'selecionado', pessoa);
            this.$set(this, 'state', 'selecionado');
            this.$set(this, 'modalShow', true);
        },
        criar() {
            this.$set(this, 'selecionado', Object.assign({}, emptyForm));
            this.$set(this, 'state', 'criando');
            this.$set(this, 'modalShow', true);
        },
        editar(pessoa) {
            this.$set(this, 'selecionado', pessoa);
            this.$set(this, 'state', 'editando');
            this.$set(this, 'modalShow', true);
        },
        apagar(pessoa) {
            this.$set(this, 'selecionado', pessoa);
            this.$set(this, 'state', 'apagando');
            this.$set(this, 'modalShow', true);
        },
        handleClose() {
            this.$set(this, 'modalShow', false);
        },
        handleOk() {
            this.$set(this, 'modalShow', false);
        },
        genderIcon(gender) {
            return 'gender-' + ( gender === 'M' ? 'male' : 'female' );
        },
        photoIcon() {
            return  'camera';
        }
    }
}
</script>