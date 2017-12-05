'use strict';

Vue.component('grid-block', {
  props: {
    assets: {
      type: Array,
      default: () => []
    }
  },

  template: `
    <figure class="grid">
      <wp-image v-for="(asset, i) in assets"
        :asset="asset"
        :key="i"
      >
      </wp-image>
    </figure>
  `
});
