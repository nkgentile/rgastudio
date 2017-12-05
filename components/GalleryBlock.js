'use strict';

Vue.component('gallery-block', {
    props: {
        assets: {
            type: Array,
            default: () => []
        }
    },

    watch: {
        assets(payload){
            this.$store.commit('gallery/updateAssets', payload);
        }
    },

    computed: {
        index(){
            return this.$store.state.gallery.index;
        },
        
        sliderTransform(){
            return {
                transform: `translateX(-${this.index}00%)`
            };
        }
    },

    methods: {
        willGoToIndex(index){
            this.$store.dispatch('gallery/go', index);
        }
    },

    template: `
        <header class="gallery">
        	<div class="slideshow-slider"
        		:style="sliderTransform"
        	>
        		<wp-image v-for="(asset, i) in assets"
        			:key="i"
        			:asset="asset"
                    class="slideshow-image"
        			:class="{ active: i === index }"
        		>
        		</wp-image>
            </div>
            <div class="gallery-info">
                <slot></slot>
                <nav class="gallery-navigation">
                    <div v-for="(asset, i) in assets"
                        :class="{ active: i === index }"
                        @click="willGoToIndex(i)"
                    >
                        <wp-image :asset="asset">
                        </wp-image>
                    </div>
                </nav>
            </div>
        </header>
    `
});
