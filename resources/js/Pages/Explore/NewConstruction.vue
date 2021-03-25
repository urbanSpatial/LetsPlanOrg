<template>
  <div>
    <map-legend
      class="mb-4"
      :pips="legendPips"
    />
    <line-chart
      :chart-data="chartData"
      :options="options"
      :height="null"
    />
    <p class="text--secondary mt-4 mb-0">
      [A line plot with the count of new construction permits over the last ten years.]
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
          data: [500, 600, 700, 750, 800, 1000, 1200, 1800, 2000, 2600],
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
