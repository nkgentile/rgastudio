'use strict';

Vue.component('fa-icon', {
    props: {
        name: {
            type: String,
            default: 'circle'
        },

        size: {
            type: Number,
            default: 1
        }
    },

    computed: {
        classObj(){
        }
    },

    template: `
        <i class="fa" aria-hidden="true"></i>
    `
});
