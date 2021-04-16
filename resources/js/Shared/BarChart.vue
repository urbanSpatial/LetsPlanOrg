<script>
import { Bar } from 'vue-chartjs';
import ChartComponent from './ChartComponent';

export default {
  extends: Bar,
  mixins: [ChartComponent],

  props: {
    chartData: {
      type: Object,
      default: null,
    },

    options: {
      type: Object,
      default: null,
    },
  },
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
};
</script>
