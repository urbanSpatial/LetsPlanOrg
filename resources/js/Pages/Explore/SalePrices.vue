<template>
  <div>
    <p>
      Plotted as a line chart of average home prices by year.
      This should be for the whole area not just the zoom.
    </p>
    <line-chart
      :chart-data="chartData"
      :options="options"
      :height="null"
    />
  </div>
</template>

<script>
import LineChart from '../../Shared/LineChart.vue';

export default {
  components: {
    LineChart,
  },

  data() {
    return {
      chartData: {
        labels: [2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020],
        datasets: [{
          data: [80000, 150000, 175000, 180000, 200000, 190000, 210000, 250000, 260000, 320000],
        }],
      },

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
};
</script>