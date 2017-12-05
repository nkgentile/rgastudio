'use strict';

Vue.component('menu-toggle', {
    props: {
        isToggled: {
            type: Boolean,
            default: false
        },

        onClick: {
            type: Function,
            default: () => {}
        }
    },

    computed: {
        isToggledClass(){
            return this.isToggled ?
                'fa-times' :
                'fa-bars'
        }
    },

    created(){
    },

    template: `
        <i class="fa"
            :class="isToggledClass"
            @click="onClick"
        >
        </i>
    `
});
