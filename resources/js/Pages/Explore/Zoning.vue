<template>
  <div>
    <bar-chart
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
        labels: ['Industrial', 'Lo Res', 'Hi Res', 'Lo Com', 'Hi Com', 'Special'],
        datasets: [{
          data: [0.1, 0.23, 0.13, 0.19, 0.18, 0.17],
          backgroundColor: [
            '#28CAF4',
            '#377BF4',
            '#311DF4',
            '#8104F4',
            '#C804F4',
            '#FF0088',
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
};
</script>
