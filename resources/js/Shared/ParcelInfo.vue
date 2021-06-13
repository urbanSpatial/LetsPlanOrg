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
          :value="landUse"
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
      land_use_lookup: {
        "12": "Special",
        "2002": "Special",
        "RSD1": "Low Res",
        "RSD2": "Low Res",
        "RSD3": "Low Res",
        "RMX1": "Low Res",
        "RMX2": "Low Res",
        "RSA": "Low Res",
        "RSA1": "Low Res",
        "RSA2": "Low Res",
        "RSA3": "Low Res",
        "RS3": "Low Res",
        "RSA4": "Low Res",
        "RSA5": "Low Res",
        "RTA1": "Low Res",
        "RM1": "Hi Res",
        "RM2": "Hi Res",
        "RM3": "Hi Res",
        "RM4": "Hi Res",
        "RMX3": "Hi Res",
        "IRMX": "Industrial",
        "ICMX": "Industrial",
        "I1": "Industrial",
        "I2": "Industrial",
        "I3": "Industrial",
        "IP": "Industrial",
        "CMX1": "Low Com",
        "CMX2": "Low Com",
        "CMX25": "Low Com",
        "CMX3": "Hi Com",
        "CMX4": "Hi Com",
        "CMX5": "Hi Com",
        "CA1": "Special",
        "CA2": "Special",
        "SPINS": "Special",
        "SPENT": "Special",
        "SPSTA": "Special",
        "SPPOP": "Special",
        "SPPOA": "Special",
        "SPAIR": "Special",
        "SC": "Special",
        "SP": "Special"
      }
    };
  },

  computed: {
    formattedSalePrice() {
      const price = this.parcel.sale_price_adj;
      return price ? numeral(price).format('$0.0a') : null;
    },
    landUse() {
      return this.land_use_lookup[this.parcel.zoning]
    }
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
