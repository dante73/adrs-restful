<template>
    <b-container fluid class="small">

        <!-- Modal window -->
        <b-modal
                id="modal-form-1"
                size="lg"
                :title="titulo"
                v-model="modalShow"
                @close="handleClose"
                no-close-on-backdrop
                hide-footer
                header-bg-variant="success"
                header-text-variant="light"
        >
            <b-row>
                <b-col md="12" class="p-0 m-0">
                    <b-container fluid class="p-0 m-0">
                        <Form @getAll="getAll" :state="state" :pessoaObj="selecionado" />
                    </b-container>
                </b-col>
            </b-row>
        </b-modal>
        <!-- End of modal window -->

        <!-- Show existing data -->
        <b-container fluid class="p-0 py-2">
            <b-row>
                <b-col md="12">

                    <b-row class="p-0 py-1">
                        <b-col md="11">
                            <h4 class="title">Cadastro de Pessoas<span></span></h4>
                        </b-col>
                        <b-col md="1" align="right">
                            <b-button variant="success" @click="criar"><b-icon-plus-circle></b-icon-plus-circle></b-button>
                        </b-col>
                    </b-row>

                    <b-row class="p-0 py-1">
                        <b-col md="12">

                            <b-table-simple responsive>
                                <b-thead>
                                    <b-tr variant="default" class="">
                                        <b-th class="text-left">#</b-th>
                                        <b-th>&nbsp;</b-th>
                                        <b-th class="text-center"><b-icon icon="camera-fill" aria-hidden="true"></b-icon></b-th>
                                        <b-th class="text-center">Nome</b-th>
                                        <b-th class="text-center">Data de Nascimento</b-th>
                                        <b-th class="text-center" width="1%">Ação</b-th>
                                    </b-tr>
                                </b-thead>
                                <b-tbody>
                                    <b-tr :key="pessoa.numero" v-for="pessoa in pessoas">
                                        <b-td class="text-left">{{ pessoa.id }}</b-td>
                                        <b-td class="text-center">
                                            <b-icon :icon="genderIcon(pessoa.genero)" aria-hidden="true"></b-icon>
                                        </b-td>
                                        <b-td class="text-center">
                                            <b-icon v-if="pessoa.imagem" :icon="photoIcon()" aria-hidden="true"></b-icon>
                                        </b-td>
                                        <b-td>{{ pessoa.nome }}</b-td>
                                        <b-td class="text-center">{{ moment(pessoa.nascimento).format($settings.format.date) }}</b-td>
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

    </b-container>
</template>

<script>
    import Form from './Form.vue';

    const emptyForm = {
        'id': 0,
        'name': "",
        'gender': "",
        'birthday': "",
        'imagem': ""
    };

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
                        s = 'Detalhes da';
                        break;
                    case 'criando':
                        s = 'Cadastrando uma nova';
                        break;
                    case 'editando':
                        s = 'Editando dados da';
                        break;
                    case 'apagando':
                        s = 'Apagando dados da';
                        break;
                }
                s = s + ' pessoa';
                return s;
            }
        },
        mounted() {
            this.getAll()
        },
        methods: {
            async getAll() {
                let r = await this.$http.get('pessoa');

                console.log(r);
                this.$set(this, 'pessoas', r.data);
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
            genderIcon(gender) {
                return 'gender-' + ( gender === 'M' ? 'male' : 'female' );
            },
            photoIcon() {
                return  'camera';
            }
        }
    }
</script>