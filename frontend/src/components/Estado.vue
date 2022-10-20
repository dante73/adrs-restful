<template>
    <div>

        <b-container fluid>

            <b-row v-if=" ! estados.length > 0">
                <b-col md="2" offset-md="6">
                    <b-img :src="require('../assets/img/green_spinner.gif')" alt="" />
                </b-col>
            </b-row>

            <b-row v-else>

                <b-col md="6" class="p-0">
                    <b-container align="center">
                        <label class='text-weight-bold text-success'>Unidades Federativas do Brasil</label>
                    </b-container>
                    <b-container class="pr-1">
                        <b-table :items="estados"
                                 :fields="estados_fields"
                                 select-mode="single"
                                 @row-selected="chosenOne"
                                 sticky-header="1024px"
                                 selectable striped hover
                        ></b-table>
                    </b-container>
                </b-col>

                <b-col md="6" class="p-0" v-if="cidades.length > 0">
                    <b-container align="center">
                        <label class='text-weight-bold text-success'>
                            Todas as Cidades do Estado {{ $support.ufPreposicao(selected) }} {{ selected.name }}
                        </label>
                    </b-container>
                    <b-container class="pl-1">
                        <b-table :items="cidades"
                                 :fields="cidadesf"
                                 sticky-header="1024px"
                                 striped hover
                        ></b-table>
                    </b-container>
                </b-col>

            </b-row>
        </b-container>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                estados: [],
                cidades: [],
                estados_fields: [
                    { key: 'state_short', sortable: true },
                    { key: 'name', sortable: true }
                ],
                cidadesf: [
                    { key: 'name', sortable: true}
                ],
                selected: undefined
            }
        },
        mounted() {
            this.getAll();
        },
        methods: {
            async getAll() {
                let r = await this.$http.get('state/loadAllWithCities');

                this.$set(this, 'estados', r.data);
            },
            chosenOne(estado) {
                this.$set(this, 'selected', estado[0]);
                this.$set(this, 'cidades', estado[0].cities);
            }
        }
    }
</script>