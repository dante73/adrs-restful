<template>
    <b-container class="small" fluid>

        <!-- Modal window -->
        <b-modal
                :header-bg-variant="$settings.colors.bgModalTitleBar"
                :header-text-variant="$settings.colors.fgModalTitleBar"
                :title="modalTitle"
                @close="handleClose"
                id="modal-form-1"
                size="lg"
                v-model="modalShow"
                no-close-on-backdrop hide-footer
        >
            <b-row>
                <b-col md="12" class="p-0 m-0">
                    <b-container class="p-0 m-0" fluid>
                        <Form @get-all-function="getAllData" :state="state" :pessoaObj="selected" />
                    </b-container>
                </b-col>
            </b-row>
        </b-modal>
        <!-- End of modal window -->

        <!-- Show existing data -->
        <b-container class="p-1" fluid>
            <b-row>
                <b-col md="12">

                    <b-container id='searchbar-container' class="m-0 p-0 small" fluid>
                        <b-row class="p-0 shadow rounded" :class="$settings.colors.bgTitleBar">
                            <b-col md="5" class="p-0 pl-2 pt-2">
                                <h5 class="title text-white">Cadastro de Pessoas<span></span></h5>
                            </b-col>
                            <b-col md="7">
                                <b-row class="p-1">
                                    <b-col md="2" class="text-right">
                                        <b-form-checkbox
                                                v-model="compacto"
                                                size="sm"
                                                :button-variant="(compacto ? 'danger' : 'light')"
                                                class="p-0 pt-1 small w-100"
                                                button
                                        >
                                            Compacta
                                        </b-form-checkbox>
                                    </b-col>
                                    <b-col md="9">
                                        <b-input-group
                                                size="sm"
                                                class="pt-1"
                                        >

                                            <b-input-group-prepend>
                                                <b-input-group-text>
                                                    <b-icon icon="filter-circle" variant="success"></b-icon>
                                                </b-input-group-text>
                                            </b-input-group-prepend>

                                            <b-form-input
                                                    id="filter-input"
                                                    v-model="filter"
                                                    type="search"
                                                    class="small form-control"
                                                    placeholder="Filtrar por Nº, Nome ou Data de Nascimento."
                                            ></b-form-input>
                                        </b-input-group>
                                    </b-col>
                                    <b-col md="1" class=" pt-1 pr-1">
                                        <b-container class="m-0 p-0 text-right" fluid>
                                            <b-icon
                                                    icon="plus-circle"
                                                    class="m-0 p-0 pr-1 text-white btn btn-lg font-weight-bold"
                                                    style="width: 32px; height:32px;"
                                                    @click="create"
                                            ></b-icon>
                                        </b-container>
                                    </b-col>
                                </b-row>
                            </b-col>
                        </b-row>
                    </b-container>

                    <b-container class="pt-1" fluid>
                        <b-row>
                            <b-col md="10" offset-md="1">

                                <!-- Data table -->
                                <b-table
                                        id="tabela"
                                        :items="pessoas"
                                        :fields="fields"
                                        :filter="filter"
                                        :small="compacto"
                                        :show-empty="true"
                                        :sticky-header="$support.mainHeight() + 'px'"
                                        borderless responsive striped
                                >
                                    <template #cell(genero)="data">
                                        <b-icon :icon="genderIcon(data.item.genero)" aria-hidden="true"></b-icon>
                                    </template>

                                    <template #cell(idade)="data">
                                        {{ $support.yearsBetween(data.item.nascimento, new Date()) }} anos
                                    </template>

                                    <template #cell(nascimento)="data">
                                        {{ moment(data.item.nascimento).format($settings.format.date) }}
                                    </template>

                                    <template #cell(actions)="pessoa">
                                        <b-row class="m-0 p-0">
                                            <b-col md="3">
                                                <b-button @click="getInfo(pessoa.item)" variant="info" size="sm" class="shadow">
                                                    <b-icon-eye></b-icon-eye>
                                                </b-button>
                                            </b-col>
                                            <b-col md="3">
                                                <b-button @click="change(pessoa.item)" variant="warning" size="sm" class="shadow">
                                                    <b-icon-pencil-square></b-icon-pencil-square>
                                                </b-button>
                                            </b-col>
                                            <b-col md="3">
                                                <b-button @click="remove(pessoa.item)" variant="danger" size="sm" class="shadow">
                                                    <b-icon-trash></b-icon-trash>
                                                </b-button>
                                            </b-col>
                                        </b-row>
                                    </template>
                                </b-table>
                                <!-- End of Data table -->

                            </b-col>
                        </b-row>
                    </b-container>

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
                compacto: false,
                filter: '',
                fields: [
                    {
                        key: 'id',
                        label: '#',
                        stickyColumn: true,
                        thAttr: { width: '1%', bgcolor: 'white' },
                        class: 'text-right'
                    },
                    {
                        key: 'genero',
                        label: 'Gênero',
                        filterByFormatted: true,
                        stickyColumn: true,
                        thAttr: { width: '1%', bgcolor: 'white' },
                        class: 'text-right'
                    },
                    {
                        key: 'nome',
                        label: 'Nome',
                        thAttr: { width: '20%', bgcolor: 'white' },
                        stickyColumn: true
                    },
                    {
                        key: 'idade',
                        label: 'Idade',
                        filterByFormatted: true,
                        stickyColumn: true,
                        thAttr: { width: '10%', bgcolor: 'white' },
                        class: 'text-center'
                    },
                    {
                        key: 'nascimento',
                        label: 'Data de Nascimento',
                        stickyColumn: true,
                        thAttr: { width: '15%', bgcolor: 'white' },
                        class: 'text-center'
                    },
                    {
                        key: 'actions',
                        label: 'Acões',
                        thAttr: { width: '10%', bgcolor: 'white' },
                        stickyColumn: true,
                        class: 'text-center'
                    }
                ]
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

                this.$root.$emit('bv::refresh::table', 'tabela')
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