<template>
    <b-container class="h-100" fluid="xl">

        <b-row class="h-100" v-if=" ! estados.length > 0">
            <b-col md="2" offset-md="6">
                <b-img :src="require('../../assets/img/green_spinner.gif')" alt="" />
            </b-col>
        </b-row>

        <b-row v-else>

            <b-col md="6" class="p-0">
                <b-container class="p-1 text-center">
                    <label class='small text-weight-bold text-success'>Unidades Federativas do Brasil</label>
                </b-container>
                <b-container class="pr-1 small">
                    <b-table
                            :items="estados"
                            :fields="estadosFields"
                            select-mode="single"
                            @row-selected="chosenOne"
                            sticky-header="635px"
                            selectable
                            hover>
                    </b-table>
                </b-container>
            </b-col>

            <b-col md="6" class="p-0" v-if="cidades.length > 0">
                <b-container class="p-1 text-center">
                    <label class='small text-weight-bold text-success'>
                        Todas as Cidades do Estado {{ $support.ufPreposicao(selected) }} {{ selected.name }}
                    </label>
                </b-container>
                <b-container class="pl-1 small">
                    <b-table
                            :items="cidades"
                            :fields="cidadesFields"
                            select-mode="single"
                            @row-selected="chosenOneCity"
                            sticky-header="630px"
                            selectable
                            hover>
                    </b-table>
                </b-container>
            </b-col>

        </b-row>

        <!-- Modal window -->
        <b-modal
                id="modal-1"
                size="xl"
                :title="modalTitle"
                v-model="modalShow"
                @close="handleClose"
                no-close-on-backdrop
                hide-footer
                header-bg-variant="success"
                header-text-variant="light"
                body-class="py-0 my-0">
            <b-row>
                <b-col md="12" class="p-0 m-0" v-html="wikiData"></b-col>
            </b-row>
        </b-modal>
        <!-- End of modal window -->

    </b-container>
</template>

<script>
    export default {
        data() {
            return {
                estados: [],
                cidades: [],
                estadosFields: [
                    { key: 'state_short', sortable: true },
                    { key: 'name', sortable: true }
                ],
                cidadesFields: [
                    { key: 'name', sortable: true}
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
                //this.$set(this, 'modalTitle', cidade[0].name + " (" + this.selected.name + ")");
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