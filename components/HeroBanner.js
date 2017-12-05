'use strict';

Vue.component('hero-banner', {
    props: {
        assets: {
            type: Array,
            default: () => []
        }
    },

    data(){
        return {
            activeIndex: 0,
            metronome: null
        }
    },

    methods: {
        increment(){
            const lastIndex = this.assets.length - 1;

            this.activeIndex = this.activeIndex < lastIndex ?
                this.activeIndex += 1 :
                0;
        },

        createMetronome(f, interval){
            this.metronome = setInterval(f, interval);
        },

        destroyMetronome(){
            this.metronome = clearInterval(this.metronome);
        }
    },

    created(){
        this.createMetronome(this.increment, 5000);
    },

    beforeDestroy(){
        this.destroyMetronome();
    },

    template: `
        <header class="hero-banner">
            <section class="hero-banner__slideshow">
                <wp-image v-for="(asset, i) in assets"
                    class="hero-banner__slideshow__slide"
                    :class="{ active: activeIndex === i }"
                    :key="i"
                    :asset="asset"
                >
                </wp-image>
            </section>
            <div class="container">
                <slot></slot>
            </div>
        </header>
    `
});
