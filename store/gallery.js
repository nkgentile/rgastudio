'use strict';

const gallery = {
    namespaced: true,

    state: {
        index: 0,
        assets: []
    },

    getters: {
    },

    mutations: {
        updateAssets(state, payload){
            state.assets = payload;
        },

        increment(state, by = 1){
            state.index += by;
        },

        decrement(state, by = 1){
            state.index -= by;
        },

        updateIndex(state, payload){
            state.index = payload;
        }
    },

    actions: {
        next({ commit, state }){
            const lastIndex = state.assets.length - 1;
            const isLastIndex = R.lt(
                state.index,
                lastIndex
            );

            console.log(isLastIndex);
        },

        prev({ commit, state }){
        },

        go({ commit }, payload){
            commit('updateIndex', payload);
        }
    }
};
