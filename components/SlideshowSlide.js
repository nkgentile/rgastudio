'use strict';

Vue.component('slideshow-slide', {
    props: {
        src: String,
        width: Number,
        height: Number,
        onClick: Function
    },

    template: `
        <div class="slideshow-slide" @click="onClick">
            <async-image :src="src"
                :width="width"
                :height="height"
            >
            </async-image>
        </div>
    `
});
