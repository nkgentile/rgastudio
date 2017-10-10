'use strict';

Vue.component('wp-site-icon', {
    props: {
        url: String
    },

    template: `
        <async-image :src="url"
            :width="512"
            :height="512"
            class="site-icon"
        >
        </async-image>
    `
});
