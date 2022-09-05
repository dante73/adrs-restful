<template>
  <div class="columns">
    <div class="column is-one-third">
      <div class="small">

        <line-chart :chart-data="linedata"></line-chart>

      </div>
    </div>
    <div class="column is-one-third">
      <div class="small">

        <bar-chart :chart-data="bardata"></bar-chart>

      </div>
    </div>
    <div class="column is-one-third">
      <div class="small">

        <pie-chart :chart-data="piedata"></pie-chart>

      </div>
    </div>
  </div>
</template>

<script>
import LineChart from './charts/LineChart.js'
import BarChart from './charts/BarChart.js'
import PieChart from './charts/PieChart.js'

export default {
  components: {
    LineChart,
    BarChart,
    PieChart
  },
  data: function () {
    var data = {
      linedata: null,
      bardata: null,
      piedata: null,
    };
    return data;
  },
  mounted () {
    this.fillData()
  },
  methods: {

    async fillData () {

      let r = await this.$http.get('editais/all');

      this.estatistica = r.data.estatistica;

      /* mount data for charts */

      let colors = [
        'aqua',
        'blue',
        'fuchsia',
        'gray',
        'green',
        'lime',
        'maroon',
        'navy',
        'olive',
        'orange',
        'purple',
        'red',
        'silver',
        'teal',
        'white',
        'yellow'
      ]; 

      let porDataDataset = [];
      let porDataColors = [];
      let porDataTotal = this.estatistica.porData['-*-Total-*-'];

      let porPortalDataset = [];
      let porPortalColors = [];
      let porPortalTotal = this.estatistica.porPortal['-*-Total-*-'];

      let porUnidadeCompradoraDataset = [];
      let porUnidadeCompradoraColors = [];
      let porUnidadeCompradoraTotal = this.estatistica.porUnidadeCompradora['-*-Total-*-'];

      delete this.estatistica.porData['-*-Total-*-'];
      delete this.estatistica.porPortal['-*-Total-*-'];
      delete this.estatistica.porUnidadeCompradora['-*-Total-*-'];

      for (let key in this.estatistica) {

        let c = 0;

        let dataset = this.estatistica[key];

        for (let k in dataset) {

          switch (key) {
            case 'porData':
              porDataDataset.push(parseInt(dataset[k], 10));
              porDataColors.push(colors[c]);
              break;
            case 'porPortal':
              porPortalDataset.push(parseInt(dataset[k], 10));
              porPortalColors.push(colors[c]);
              break;
            case 'porUnidadeCompradora':
              porUnidadeCompradoraDataset.push(parseInt(dataset[k], 10));
              porUnidadeCompradoraColors.push(colors[c]);
              break;
          }

          if ( c === colors.length ) {
            c = 0;
          }
          else {
            c++;
          }
        }
      }

      this.linedata = {
        'labels': Object.keys(r.data.estatistica.porData),
        'datasets': [{
          label: 'Editais por Data',
          backgroundColor: porDataColors,
          data: porDataDataset
        }]
      };

      this.bardata = {
        'labels': Object.keys(r.data.estatistica.porPortal),
        'datasets': [{
          label: 'Editais por Portal',
          backgroundColor: porPortalColors,
          data: porPortalDataset
        }]
      };

      this.piedata = {
        'labels': Object.keys(r.data.estatistica.porUnidadeCompradora),
        'datasets': [{
          label: 'Editais por Unidade Compradora',
          backgroundColor: porUnidadeCompradoraColors,
          data: porUnidadeCompradoraDataset
        }]
      };
    }
  }
}

</script>
