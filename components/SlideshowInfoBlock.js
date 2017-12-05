'use strict';

Vue.component('slideshow-info-block', {
    props: {
        slides: {
            type: Array,
            default: () => []
        },

        index: {
            type: Number,
            default: 0
        }
    },

    template: `
        <figcaption class="slideshow-info">
            <slot></slot>
        </figcaption>
    `
});
