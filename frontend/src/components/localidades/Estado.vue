<template>
    <b-container fluid>

        <!-- Modal window -->
        <b-modal
                :header-bg-variant="$settings.colors.bgModalTitleBar"
                :header-text-variant="$settings.colors.fgModalTitleBar"
                :title="modalTitle"
                @close="handleClose"
                id="modal-1"
                size="xl"
                v-model="modalShow"
                body-class="py-0 my-0"
                no-close-on-backdrop hide-footer
        >
            <b-row>
                <b-col md="12" class="p-0 m-0" v-html="wikiData"></b-col>
            </b-row>
        </b-modal>
        <!-- End of modal window -->

        <!-- Show existing data -->
        <b-container class="p-1" fluid>
            <b-row>
                <b-col md="12">

                    <b-row class="h-100" v-if=" ! estados.length > 0">
                        <b-col md="2" offset-md="6">
                            <b-img :src="require('../../assets/img/green_spinner.gif')" alt="" />
                        </b-col>
                    </b-row>

                    <b-row v-else>
                        <b-col md="12">
                            <b-container id='searchbar-container' class="m-0 p-0 small" fluid>
                                <b-row class="p-0 shadow rounded" :class="$settings.colors.bgTitleBar">
                                    <b-col md="5" class="p-0 pl-2 pt-2">
                                        <h5 class="title text-white">Unidades Federativas do Brasil<span></span></h5>
                                    </b-col>
                                    <b-col md="7">
                                        &nbsp;
                                    </b-col>
                                </b-row>
                            </b-container>
                        </b-col>
                    </b-row>

                </b-col>
            </b-row>

            <b-row>
                <b-col md="6" class="p-0">
                    <b-container class="p-1 text-center">
                        <label class='text-weight-bold text-success'>
                            Todos os Estados da Federação
                        </label>
                    </b-container>
                    <b-container class="pr-1 small">
                        <b-table
                                :items="estados"
                                :fields="estadosFields"
                                :sticky-header="($support.mainHeight() - gapAfterTable) + 'px'"
                                @row-selected="chosenOne"
                                select-mode="single"
                                style="direction: rtl"
                                class="border border-success rounded"
                                selectable hover
                        >
                        </b-table>
                    </b-container>
                </b-col>

                <b-col md="6" class="p-0" v-if="cidades.length > 0">
                    <b-container class="p-1 text-center">
                        <label class='text-weight-bold text-success'>
                            Todas as Cidades do Estado {{ $support.ufPreposicao(selected) }} {{ selected.name }}
                        </label>
                    </b-container>
                    <b-container class="pl-1 small">
                        <b-table
                                :items="cidades"
                                :fields="cidadesFields"
                                :sticky-header="($support.mainHeight() - gapAfterTable) + 'px'"
                                @row-selected="chosenOneCity"
                                select-mode="single"
                                style="direction: ltr"
                                class="border border-success rounded"
                                selectable hover
                        >
                        </b-table>
                    </b-container>
                </b-col>
            </b-row>
        </b-container>
    </b-container>
</template>

<script>
    export default {
        data() {
            return {
                gapAfterTable: 60,
                estados: [],
                cidades: [],
                estadosFields: [
                    { key: 'state_short', label: 'Sigla' },
                    { key: 'name', label: 'Nome' }
                ],
                cidadesFields: [
                    { key: 'name', label: 'Nome' }
                ],
                selected: undefined,
                selectedCity: undefined,
                modalTitle: "",
                modalShow: false,
                wikiData: ""
            }
        },
        mounted() {
            this.getAllData();
        },
        methods: {
            async getAllData() {
                let r = await this.$http.get('state/loadAllWithCities');

                this.$set(this, 'estados', r.data);
            },
            chosenOne(estado) {
                this.$set(this, 'selected', estado[0]);
                this.$set(this, 'cidades', estado[0].cities);
            },
            chosenOneCity(cidade) {
                let h = this.$support.wHeight() * 0.80;

                this.$set(this, 'selectedCity', cidade[0]);
                this.$set(this, 'modalTitle', cidade[0].name);
                this.$set(this, 'modalShow', true);
                this.$set(this, 'wikiData',
                    "<iframe src=\"" + this.$settings.wikipedia + this.modalTitle.replace(' ', '_')
                    + "\" class='border-0 w-100' height='" + h + "'></iframe>");
            },
            handleClose() {
                this.$set(this, 'selectedCity', undefined);
                this.$set(this, 'modalTitle', "");
                this.$set(this, 'modalShow', false);
                this.$set(this, 'wikiData', "");
            }
        }
    }
</script>