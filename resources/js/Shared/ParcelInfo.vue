<template>
<div>
  <v-progress-circular indeterminate 
  v-if="loading"/>
   
  <div v-if="!loading">
  <h1 class="popup__title pink--text text--darken-1">{{parcel.address_1}}</h1>

  <div class="popup__subtitle text--secondary">
    {{parcel.property_type}}
  </div>

  <div class="figure-group">
    <figure class="figure" v-html="parcelFigure('Built', parcel.year_built)"/>
    <figure class="figure" v-html="parcelFigure('Zoning', parcel.bldg_code)"/>
    <figure class="figure" v-html="parcelFigure('Permits', parcel.total_permits)"/>
  </div>

  <div class="figure-group">
    <figure class="figure" v-html="parcelFigure('Last Sale', parcel.sale_price_adj)"/>
    <figure class="figure" v-html="parcelFigure('Sale Date', parcel.sale_year)"/>
  </div>

  <div class="figure-group -scores pb-0">
    <figure class="figure" v-html="parcelFigure('Preservation', '0.5', null, 'teal--text')"/>
    <figure class="figure" v-html="parcelFigure('Community', '0.5', null, 'purple--text')"/>
    <figure class="figure" v-html="parcelFigure('Variance', '0.5', null, 'orange--text')"/>
    <figure class="figure" v-html="parcelFigure('Development', '0.5', null, 'lime--text text--darken-2')"/>
  </div>
  </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      parcel: {},
      loading: false,
    }
  },
  methods: {
    fetchParcel(parcelId) {
      this.loading = true;
      axios.get('/parcel/'+parcelId)
      .then( (response) => {
        return response.data.data;
      }).then( (data) => {
        this.parcel = Object.assign({id: data.id}, data.attributes);
      }).catch( (response) => {
        this.parcel = {};
      }).finally(() => {
        this.loading = false;
      });
    },
    parcelFigure(key, value, descriptionCls, contentCls) {
      return `
        <figcaption class="figure__description ${descriptionCls || 'text--secondary'}">${key}</figcaption>
        <div class="figure__content ${contentCls}">${value || 'N/A'}</div>
      `;
    }
  },
};
</script>
