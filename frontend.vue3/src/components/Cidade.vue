<template>

    <div class="container">

        <div class="columns" v-if=" ! cidades.length > 0">
          <div class="column">&nbsp;</div>
          <div class="column has-text-centered">
              <img src="@/assets/6.gif" alt="" />
          </div>
          <div class="column">&nbsp;</div>
        </div>

        <div class="columns" v-else>

          <div class="column is-full">
            <div class='container'>
              <p class='subtitle'>Cidades do Brasil</p>
              <b-table :data="cidades">
                <template slot-scope="props">

                  <b-table-column field="nome" label="Nome da Cidade" searchable>
                    {{ props.row.nome }}
                  </b-table-column>

                  <b-table-column field="estado.nome" label="Estado" searchable>
                    {{ props.row.estado.nome }}
                  </b-table-column>

                  <b-table-column field="estado.sigla" label="Unidade Federativa" searchable>
                    {{ props.row.estado.sigla }}
                  </b-table-column>

                  <b-table-column field="regiao.nome" label="Região" searchable>
                    {{ props.row.regiao.nome }}
                  </b-table-column>

                  <b-table-column field="regiao.sigla" label="Sigla da Região" searchable>
                    {{ props.row.regiao.sigla }}
                  </b-table-column>

                </template>
              </b-table>
            </div>
          </div>

          <!--
          <div class="column is-full">

            <div class='container'>
              <p class='subtitle'>Cidades do Brasil</p>
              <table class="table is-fullwidth is-striped is-hoverable">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Estado</th>
                    <th>UF</th>
                    <th>Região</th>
                    <th>Sigla</th>
                  </tr>
                </thead>
                <tbody>
                  <tr :key="cidade._id" v-for="cidade in cidades" @click="chosenOne(cidade)">
                    <td>{{ cidade.nome }}</td>
                    <td>{{ cidade.estado.nome }}</td>
                    <td>{{ cidade.estado.sigla }}</td>
                    <td>{{ cidade.regiao.nome }}</td>
                    <td>{{ cidade.regiao.sigla }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
          -->

        </div>
    </div>

</template>

<script>
export default {
  data() {
    return {
      cidades: []
    } 
  },
  mounted() {
    this.getAll();
  },
  methods: {
    async getAll() {
      let r = await this.$http.get('cidade/all');
      this.cidades = r.data;
    }
  }
}
</script>
