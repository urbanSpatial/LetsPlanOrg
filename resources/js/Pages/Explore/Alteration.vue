<template>
  <div>
    <bar-chart
      ref="chartComponent"
      :chart-data="chartData"
      :options="options"
      :height="null"
    />
    <p class="text--secondary mt-4 mb-0">
      The bar plot above shows the number of parcels
      in Spruce Hill that received alteration permits
      in the past ten years.
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
        labels: ['0', '1', '2+'],
        datasets: [{
          data: [0.0, 0.0, 0.0],
          backgroundColor: [
            '#C0C0C0',
            '#28CAF4',
            '#8104F4',
          ],
        }],
      },
      options: {
        aspectRatio: 16 / 9,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
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
      /* eslint-disable-next-line prefer-template */
      return window.axios.get(window.location.origin + '/chart/permits-a/1');
    },
  },
};
</script>
