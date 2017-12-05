'use strict';

Vue.component('card-block', {
    props: {
        src: String,
        width: Number,
        height: Number,
        href: String
    },

    template: `
        <a :href="href" class="card">
            <async-image :src="src"
                :width="width"
                :height="height"
            >
            </async-image>
            <slot></slot>
        </a>
    `
});
