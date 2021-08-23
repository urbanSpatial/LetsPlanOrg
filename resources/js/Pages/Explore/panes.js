import Alteration from './Alteration.vue';
import NewConstruction from './NewConstruction.vue';
import SalePrices from './SalePrices.vue';
import Zoning from './Zoning.vue';
import Layers from './Layers.vue';

export default [{
  title: 'Sale Prices',
  component: SalePrices,
  route: 'sales',
}, {
  title: 'Zoning',
  component: Zoning,
  route: 'zoning',
}, {
  title: 'New Construction Permits',
  component: NewConstruction,
  route: 'construction',
}, {
  title: 'Alteration Permits',
  component: Alteration,
  route: 'alteration',
}, {
  title: 'Planning Overlays',
  component: Layers,
  route: 'layers',
}];
