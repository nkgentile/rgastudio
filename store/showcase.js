'use strict';

const showcase = {
    namespaced: true,

    state: {
        index: 0
    },

    getters: {
    },

    mutations: {
        incrementIndex (state) {
            state.index += 1;
        },

        decrementIndex (state) {
            state.index -= 1;
        },

        goToIndex (state, payload) {
            state.index = payload;
        }
    },

    actions: {
        nextIndex ({ state, getters, commit }){
            const lastIndex = R.length(getters.featuredProjects);
            const isLastIndex = R.equals(state.index, lastIndex);
            const increment = () => commit('incrementIndex');

            if(!isLastIndex){
                increment();
            }
        },

        prevIndex ({ state, getters, commit }){
            const firstIndex = 0;
            const isFirstIndex = R.equals(state.index, 0);
            const decrement = () => commit('decrementIndex');

            if(!isFirstIndex){
                decrement();
            }
        }

    }
}
