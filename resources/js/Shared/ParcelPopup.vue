<script>
import mapboxgl from 'mapbox-gl';

/**
 * @see https://github.com/soal/vue-mapbox/blob/development/src/components/UI/Popup.js
 */
export default {
  name: 'ParcelPopup',

  props: {
    mapbox: {
      default: null,
      type: Object,
    },
    marker: {
      default: null,
      type: Object,
    },

    /**
     * If `true`, a close button will appear in the top right corner of the popup.
     * Mapbox GL popup option.
     */
    closeButton: {
      type: Boolean,
      default: true,
    },

    /**
     * Mapbox GL popup option.
     * If `true`, the popup will closed when the map is clicked. .
     */
    closeOnClick: {
      type: Boolean,
      default: true,
    },
    /**
     * Mapbox GL popup option.
     * A string indicating the popup's location relative to the coordinate set.
     * If unset the anchor will be dynamically set to ensure the popup falls
     * within the map container with a preference for 'bottom' .
     *  'top', 'bottom', 'left', 'right', 'top-left', 'top-right', 'bottom-left', 'bottom-right'
     */
    anchor: {
      validator(value) {
        const allowedValues = [
          'top',
          'bottom',
          'left',
          'right',
          'top-left',
          'top-right',
          'bottom-left',
          'bottom-right',
        ];
        return typeof value === 'string' && allowedValues.includes(value);
      },
      default: undefined,
    },

    /**
     * Mapbox GL popup option.
     * A pixel offset applied to the popup's location
     * a single number specifying a distance from the popup's location
     * a PointLike specifying a constant offset
     * an object of Points specifing an offset for each anchor position
     * Negative offsets indicate left and up.
     */
    offset: {
      type: [Number, Object, Array],
      default: () => [0, 0],
    },
    /**
     * Component option.
     * If `true`, popup treats data in deafult slot as plain text
     */
    onlyText: {
      type: Boolean,
      default: false,
    },

    showed: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      initial: true,
      popup: undefined,
      coordinates: undefined,
      map: null,
    };
  },

  computed: {
    open: {
      cache: false,
      get() {
        if (this.popup !== undefined) {
          return this.popup.isOpen();
        }
        return false;
      },
      set(value) {
        if (this.map && this.popup) {
          if (!value) {
            this.popup.remove();
          } else {
            this.popup.addTo(this.map);
          }
        }
      },
    },
  },

  watch: {
    coordinates(lngLat) {
      if (this.initial) return;
      this.popup.setLngLat(lngLat);
    },

    showed(next, prev) {
      if (next !== prev) {
        this.open = next;
        if (this.marker) {
          this.marker.togglePopup();
        }
      }
    },
  },

  created() {
    // this.popup = new this.mapbox.Popup(this.$props);
  },

  mounted() {
    this.initial = false;
  },

  beforeDestroy() {
    if (this.map) {
      this.popup.remove();
      this.$_emitEvent('removed');
    }
  },

  methods: {
    setCoords(coords) {
      this.coordinates = coords;
      this.popup.setLngLat(coords);
      this.popup.addTo(this.map);
    },
    setMapboxMap(map) {
      if (this.map !== null) {
        return;
      }
      this.map = map;
      this.$_addPopup();
    },
    $_addPopup() {
      this.popup = new mapboxgl.Popup(this.$props);
      if (this.coordinates !== undefined) {
        this.popup.setLngLat(this.coordinates);
      }
      if (this.$slots.default !== undefined) {
        if (this.onlyText) {
          if (this.$slots.default[0].elm.nodeType === 3) {
            const tmpEl = document.createElement('span');
            tmpEl.appendChild(this.$slots.default[0].elm);
            this.popup.setText(tmpEl.innerText);
          } else {
            this.popup.setText(this.$slots.default[0].elm.innerText);
          }
        } else {
          this.popup.setDOMContent(this.$slots.default[0].elm);
        }
      }

      this.$_bindSelfEvents(['open', 'close'], this.popup);

      if (this.marker) {
        this.marker.setPopup(this.popup);
      }
      if (this.showed) {
        this.open = true;

        if (this.marker) {
          this.marker.togglePopup();
        }
      }
    },

    $_bindSelfEvents(events, emitter) {
      // https://github.com/soal/vue-mapbox/blob/e0edd17bde5fe8d1db7f44029e0e3399e2bf35ea/src/components/UI/withSelfEvents.js
      Object.keys(this.$listeners).forEach((eventName) => {
        if (events.includes(eventName)) {
          emitter.on(eventName, this.$_emitSelfEvent);
        }
      });
    },

    $_emitSelfEvent(event) {
      this.$_emitMapEvent(event, { popup: this.popup });
    },

    $_emitEvent(name, data = {}) {
      this.$emit(name, {
        map: this.map,
        component: this,
        ...data,
      });
    },

    $_emitMapEvent(event, data = {}) {
      this.$_emitEvent(event.type, { mapboxEvent: event, ...data });
    },

    remove() {
      this.popup.remove();
      this.$_emitEvent('remove', { popup: this.popup });
    },
  },

  render(h) {
    return h(
      'div',
      {
        style: {
          display: 'block',
        },
      },
      [this.$slots.default],
    );
  },

};
</script>
