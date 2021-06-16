<template>
  <div>
    <bar-chart
      ref="chartComponent"
      :chart-data="chartData"
      :options="options"
      :height="null"
    />
    <p class="text--secondary mt-4 mb-0">
      [A bar plot showing the rate of each zoning designation by parcel.]
    </p>
  </div>
</template>

<script>
import BarChart from '../../Shared/BarChart.vue';

export default {
  components: {
    BarChart,
  },
  data() {
    return {
      chartData: {
        labels: ['Industrial', 'Low Res', 'Hi Res', 'Low Com', 'Hi Com', 'Special'],
        datasets: [{
          data: [0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
          backgroundColor: [
            '#28CAF4',
            '#377BF4',
            '#311DF4',
            '#8104F4',
            '#C804F4',
            '#CCC',
          ],
        }],
      },

      options: {
        aspectRatio: 16 / 9,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: (value, index, values) => value * 100 + (index === values.length - 1 ? '%' : ''),
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
      return window.axios.get(window.location.origin + '/chart/zoning/1');
    },
  },
};
</script>
