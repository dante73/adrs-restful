<template>
    <b-container class="small" fluid="xl">

        <!-- Modal window -->
        <b-modal
                id="modal-form-1"
                size="lg"
                :title="modalTitle"
                v-model="modalShow"
                @close="handleClose"
                no-close-on-backdrop
                hide-footer
                header-bg-variant="success"
                header-text-variant="light">
            <b-row>
                <b-col md="12" class="p-0 m-0">
                    <b-container class="p-0 m-0" fluid="xl">
                        <Form @getAllFunction="getAllData" :state="state" :pessoaObj="selected" />
                    </b-container>
                </b-col>
            </b-row>
        </b-modal>
        <!-- End of modal window -->

        <!-- Show existing data -->
        <b-container class="p-0 py-2" fluid="xl">
            <b-row>
                <b-col md="12">

                    <b-row class="p-0 py-1">
                        <b-col md="11">
                            <h4 class="title">Cadastro de Pessoas<span></span></h4>
                        </b-col>
                        <b-col md="1" class="text-right">
                            <b-button variant="success" @click="create">
                                <strong><b-icon-plus-circle></b-icon-plus-circle></strong>
                            </b-button>
                        </b-col>
                    </b-row>

                    <b-row class="p-0 py-1">
                        <b-col md="12">

                            <b-table-simple>
                                <b-thead>
                                    <b-tr variant="warning">
                                        <b-th class="text-left">#</b-th>
                                        <b-th>&nbsp;</b-th>
                                        <b-th class="text-center"><b-icon icon="camera-fill" aria-hidden="true"></b-icon></b-th>
                                        <b-th class="text-center">Nome</b-th>
                                        <b-th class="text-center">Data de Nascimento</b-th>
                                        <b-th class="text-center" width="1%">Ação</b-th>
                                    </b-tr>
                                </b-thead>
                                <b-tbody>
                                    <b-tr :key="pessoa.id" v-for="pessoa in pessoas">
                                        <b-td class="text-left">{{ pessoa.id }}</b-td>
                                        <b-td class="text-center">
                                            <b-icon :icon="genderIcon(pessoa.genero)" aria-hidden="true"></b-icon>
                                        </b-td>
                                        <b-td class="text-center">
                                            <b-icon v-if="pessoa.imagem" :icon="photoIcon()" aria-hidden="true"></b-icon>
                                        </b-td>
                                        <b-td>{{ pessoa.nome }}</b-td>
                                        <b-td class="text-center">{{ moment(pessoa.nascimento).format($settings.format.date) }}</b-td>
                                        <b-td class="text-right">
                                            <b-button-group size="sm">
                                                <b-button @click="getInfo(pessoa)" variant="outline-default"><b-icon-eye></b-icon-eye></b-button>
                                                <b-button @click="change(pessoa)" variant="outline-default"><b-icon-pencil-square></b-icon-pencil-square></b-button>
                                                <b-button @click="remove(pessoa)" variant="outline-default"><b-icon-trash></b-icon-trash></b-button>
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
                model: 'pessoa',
                state: undefined,
                selected: undefined,
                modalShow: false,
            }
        },
        computed: {
            modalTitle() {
                let st = this.$support.st;
                let s;
                switch (this.state) {
                    case st.SELECTING:
                        s = 'Detalhes da';
                        break;
                    case st.CREATING:
                        s = 'Cadastrando uma nova';
                        break;
                    case st.UPDATING:
                        s = 'Editando dados da';
                        break;
                    case st.DELETING:
                        s = 'Apagando dados da';
                        break;
                }
                s = s + ' ' + this.model;
                return s;
            }
        },
        mounted() {
            this.getAllData()
        },
        methods: {
            async getAllData() {
                let r = await this.$http.get(this.model);

                this.$set(this, 'pessoas', r.data);
                this.$set(this, 'modalShow', false);
            },
            getInfo(pessoa) {
                this.$set(this, 'selected', pessoa);
                this.$set(this, 'state', this.$support.st.SELECTING);
                this.$set(this, 'modalShow', true);
            },
            create() {
                this.$set(this, 'selected', Object.assign({}, emptyForm));
                this.$set(this, 'state', this.$support.st.CREATING);
                this.$set(this, 'modalShow', true);
            },
            change(pessoa) {
                this.$set(this, 'selected', pessoa);
                this.$set(this, 'state', this.$support.st.UPDATING);
                this.$set(this, 'modalShow', true);
            },
            remove(pessoa) {
                this.$set(this, 'selected', pessoa);
                this.$set(this, 'state', this.$support.st.DELETING);
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