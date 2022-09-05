<template>
    <div>

        <b-container fluid>

            <b-row v-if=" ! estados.length > 0">
                <b-col md="2" offset-md="6">
                    <b-img src="require('../assets/green_spinner.gif" alt="" />
                </b-col>
            </b-row>

            <b-row v-else>

                <b-col md="6" class="p-0">
                    <b-container align="center">
                        <h5 class='text-weight-bold text-success'>Unidades Federativas do Brasil</h5>
                    </b-container>
                    <b-container class="pr-1">
                        <b-table :items="estados"
                                 :fields="estadosf"
                                 select-mode="single"
                                 @row-selected="chosenOne"
                                 sticky-header="1024px"
                                 selectable striped hover
                        ></b-table>
                    </b-container>
                </b-col>

                <b-col md="6" class="p-0" v-if="cidades.length > 0">
                    <b-container align="center">
                        <h5 class='text-weight-bold text-success'>
                            Todas as Cidades do Estado {{ $support.ufPreposicao(selected) }} {{ selected.nome }}
                        </h5>
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
                estadosf: [
                    { key: 'sigla', sortable: true },
                    { key: 'nome', sortable: true }
                ],
                cidadesf: [
                    { key: 'nome', sortable: true}
                ],
                selected: undefined
            }
        },
        mounted() {
            this.getAll();
        },
        methods: {
            async getAll() {
                let r = await this.$http.get('estado/all');
                this.$set(this, 'estados', r.data);
            },
            chosenOne(estado) {
                this.$set(this, 'selected', estado[0]);
                this.$set(this, 'cidades', estado[0].cidades);
            }
        }
    }
</script>