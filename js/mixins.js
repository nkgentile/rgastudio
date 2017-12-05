'use strict';

const header = {
    methods: {
        openMenu(){
            this.$store.commit('menu/open');
        },

        closeMenu(){
            this.$store.commit('menu/close');
        }
    },

    computed: {
        isMenuOpen(){
            return this.$store.state.menu.isOpen;
        }
    }
};
