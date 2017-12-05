'use strict';

const menu = {
    namespaced: true,

    state: {
        isOpen: false
    },
    
    getters: {
    },

    mutations: {
        open(state){
            state.isOpen = true;
        },

        close(state){
            state.isOpen = false;
        },

        toggle(state){
            state.isOpen = !state.isOpen;
        }
    },

    actions: {
    }
}
