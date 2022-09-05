<template>
  <div>

    <b-container fluid>

      <b-row v-if=" ! regioes.length > 0">
        <b-col md="2" offset-md="6">
          <b-img src="require('../assets/green_spinner.gif" alt="" />
        </b-col>
      </b-row>

      <b-row v-else>

        <b-col md="4" class="p-0">
          <b-container align="center">
            <h5 class='text-weight-bold text-success'>Regiões no Brasil</h5>
          </b-container>
          <b-container class="pr-1">
            <b-table :items="regioes"
                     :fields="regioesf"
                     select-mode="single"
                     @row-selected="chosenOneRegiao"
                     sticky-header="1024px"
                     selectable striped hover
            ></b-table>
          </b-container>
        </b-col>

        <b-col md="4" class="p-0" v-if="estados.length > 0">
          <b-container align="center">
            <h5 class='text-weight-bold text-success'>
              Estados da Região {{ selected.nome }}
            </h5>
          </b-container>
          <b-container class="px-0">
            <b-table :items="estados"
                     :fields="estadosf"
                     select-mode="single"
                     @row-selected="chosenOneEstado"
                     sticky-header="1024px"
                     selectable striped hover
            ></b-table>
          </b-container>
        </b-col>

        <b-col md="4" class="p-0" v-if="cidades.length > 0">
          <b-container align="center">
            <h5 class='text-weight-bold text-success'>
              Todas as Cidades do Estado {{ $support.ufPreposicao(estadoSelected) }} {{ estadoSelected.nome }}
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
          regioes: [],
          regioesf: [
            { key: 'sigla', sortable: true },
            { key: 'nome', sortable: true }
          ],
          estados: [],
          estadosf: [
            { key: 'sigla', sortable: true },
            { key: 'nome', sortable: true }
          ],
          cidades: [],
          cidadesf: [
            { key: 'nome', sortable: true}
          ],
          selected: undefined,
          estadoSelected: undefined,
        }
    },
    mounted() {
        this.getAll();
    },
    methods: {
        async getAll() {
            let r = await this.$http.get('regiao/all');
            this.$set(this, 'regioes', r.data);
        },
        chosenOneRegiao(regiao) {
          this.$set(this, 'estadoSelected', undefined);
          this.$set(this, 'cidades', []);
          this.$set(this, 'selected', regiao[0]);
          this.$set(this, 'estados', regiao[0].estados);
        },
        chosenOneEstado(estado) {
          this.$set(this, 'estadoSelected', estado[0]);
          this.$set(this, 'cidades', estado[0].cidades);
        }
    }
}
</script>