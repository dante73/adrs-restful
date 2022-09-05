<template>
  <div class="container">

    <div class="container">
        <charts />
    </div>

    <div class="container">

      <section v-if="editais.length > 0">
        <b-table :data="editais">
          <template slot-scope="props">

            <b-table-column field="Portal" label="Portal">
              {{ props.row.Portal }}
            </b-table-column>

            <b-table-column field="NumeroEdital" label="Nº do Edital" numeric>
              {{ props.row.NumeroEdital }}
            </b-table-column>

            <b-table-column field="UnidadeCompradora" label="Unidade Compradora" searchable>
              {{ props.row.UnidadeCompradora }}
            </b-table-column>

            <b-table-column field="DataPublicacao" label="Data de Publicacao" centered searchable>
              {{ moment(props.row.DataPublicacao.substr(0,19)).format('DD/MM/YYYY hh:mm') }}
            </b-table-column>

            <b-table-column field="Items" label="Itens" centered searchable>
              {{ props.row.Items.length }}
            </b-table-column>

          </template>
        </b-table>
      </section>
      <p v-else>Reunindo todos os editais em uma lista...</p>
      
    </div>
  </div>

</template>

<script>
import Charts from './Charts.vue';

export default {
    components: {
      Charts
    },
    data() {
        return {
            editais: []
        } 
    },
    mounted() {
        this.getAll();
    },
    methods: {
        async getAll() {

            let r = await this.$http.get('editais/all');

            this.editais = r.data.editais;
        }
    }
}
</script>
