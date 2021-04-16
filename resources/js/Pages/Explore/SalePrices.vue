<template>
  <div>
    <map-legend
      class="mb-4"
      :pips="legendPips"
    />
    <line-chart
      ref="chartComponent"
      :chart-data="chartData"
      :options="options"
      :height="null"
    />
    <p class="text--secondary mt-4 mb-0">
      [Plotted as a line chart of average home prices by year.
      This should be for the whole area not just the zoom.]
    </p>
  </div>
</template>

<script>
import MapLegend from '../../Shared/MapLegend.vue';
import LineChart from '../../Shared/LineChart.vue';

export default {
  components: {
    MapLegend,
    LineChart,
  },

  data() {
    return {
      chartData: {
        labels: [2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020],
        datasets: [{
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
          borderColor: '#f08',
          backgroundColor: '#ff008833',
          pointBackgroundColor: 'white',
        }],
      },

      legendPips: [
        { name: '100K', color: '#28CAF4' },
        { name: '100K', color: '#377BF4' },
        { name: '100K', color: '#311DF4' },
        { name: '100K', color: '#8104F4' },
        { name: '100K', color: '#C804F4' },
      ],

      options: {
        aspectRatio: 16 / 9,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: (value, index) => {
                let formatted = Math.round(value / 1000);

                if (value !== 0) {
                  formatted += 'K';
                }

                if (index === 0) {
                  return `$${formatted}`;
                }
                return formatted;
              },
            },
          }],
        },
        legend: {
          display: false,
        },
      },
    };
  },
  mounted() {
    this.fetchData()
      .then((result) => result.data)
      .then((jsonApi) => jsonApi.data)
      .then((dataset) => {
        this.chartData.datasets[0].data = dataset.attributes.data;
        this.chartData.labels = dataset.attributes.labels;
      })
      .then(() => {
        this.$nextTick(() => {
          this.$refs.chartComponent.redraw();
        });
      });
  },
  methods: {
    fetchData() {
      /* eslint-disable prefer-template */
      return window.axios.get(window.location.origin + '/chart/sales/1');
    },
  },

};
</script>
