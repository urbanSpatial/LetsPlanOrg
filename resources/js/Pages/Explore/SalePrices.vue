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
      The line chart above shows the average sale price
      for properties in Spruce Hill by year.
    </p>
  </div>
</template>

<script>
import numeral from 'numeral';
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
        { name: '', color: '#28CAF4' },
        { name: '', color: '#377BF4' },
        { name: '', color: '#311DF4' },
        { name: '', color: '#8104F4' },
        { name: '', color: '#C804F4' },
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
      saleMeta: {
        min: 0,
        max: 0,
      },
    };
  },
  mounted() {
    this.fetchData()
      .then((result) => result.data)
      .then((jsonApi) => {
        /* add current year min and max sales price to data
          but since this is only the current year then will not match sales price chart legend
          this.saleMeta.min = jsonApi.meta.current_year.min_price;
          this.saleMeta.max = jsonApi.meta.current_year.max_price;
        */

        // use new all_year instead which should match min and max range of map features
        this.saleMeta.min = jsonApi.meta.all_year.min_price;
        this.saleMeta.max = jsonApi.meta.all_year.max_price;
        return jsonApi.data;
      })
      .then((dataset) => {
        this.chartData.datasets[0].data = dataset.attributes.data;
        this.chartData.labels = dataset.attributes.labels;
      })
      .then(() => {
        this.$nextTick(() => {
          this.updateLegend();
          this.$refs.chartComponent.redraw();
        });
      });
  },
  methods: {
    updateLegend() {
      // make 5 buckets at 20% of max-min
      const diff = (this.saleMeta.max - this.saleMeta.min) * 0.075;
      this.legendPips = [
        { color: '#28CAF4' },
        { color: '#377BF4' },
        { color: '#311DF4' },
        { color: '#8104F4' },
        { color: '#C804F4' },
      ].map((d, i) => {
        // eslint-disable-next-line no-param-reassign
        d.name = numeral((i * diff) + this.saleMeta.min).format('$0.0a') + (i === 4 ? '+' : '');
        return d;
      });
    },
    fetchData() {
      /* eslint-disable-next-line prefer-template */
      return window.axios.get(window.location.origin + '/chart/sales/1');
    },
  },

};
</script>
