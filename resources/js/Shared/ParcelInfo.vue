<template>
  <div>
    <v-progress-circular
      v-if="loading"
      class="ma-5"
      color="grey"
      indeterminate
    />

    <div v-if="!loading">
      <h1 class="popup__title pink--text text--darken-1">
        {{ parcel.address_1 }}
      </h1>

      <div class="popup__subtitle text--secondary">
        {{ parcel.property_type }}
      </div>

      <div class="figure-group">
        <parcel-figure
          name="Built"
          :value="parcel.year_built"
        />
        <parcel-figure
          name="Zoning"
          :value="parcel.bldg_code"
        />
        <parcel-figure
          name="Permits"
          :value="parcel.total_permits"
        />
      </div>

      <div class="figure-group">
        <parcel-figure
          name="Last Sale"
          :value="formattedSalePrice"
        />
        <parcel-figure
          name="Sale Date"
          :value="parcel.sale_year"
        />
      </div>

      <div class="figure-group -scores pb-0">
        <parcel-figure
          name="Preservation"
          value="0.5"
          content-class="teal--text"
        />
        <parcel-figure
          name="Community"
          value="0.5"
          content-class="purple--text"
        />
        <parcel-figure
          name="Variance"
          value="0.5"
          content-class="orange--text"
        />
        <parcel-figure
          name="Development"
          value="0.5"
          content-class="lime--text text--darken-2"
        />
      </div>
    </div>
  </div>
</template>

<script>
import numeral from 'numeral';
import ParcelFigure from './ParcelFigure.vue';

export default {
  components: {
    ParcelFigure,
  },

  data() {
    return {
      cache: {},
      parcel: {},
      loading: false,
    };
  },

  computed: {
    formattedSalePrice() {
      const price = this.parcel.sale_price_adj;
      return price ? numeral(price).format('$0.0a') : null;
    },
  },

  methods: {
    fetchParcel(parcelId) {
      // avoid having to load the same parcel data twice in a session
      if (this.cache[parcelId]) {
        this.parcel = this.cache[parcelId];
        return;
      }

      this.loading = true;
      window.axios.get(`/parcel/${parcelId}`)
        .then((response) => response.data.data).then((data) => {
          const parcel = { id: data.id, ...data.attributes };
          this.parcel = parcel;
          this.cache[parcelId] = parcel;
        }).catch(() => {
          this.parcel = {};
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
