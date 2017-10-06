'use strict';

Vue.component( 'async-image', {
    props: {
        src: String,
        width: Number,
        height: Number
    },

    data(){
        return {
            image: new Image(this.width, this.height),
            loaded: false
        }
    },

    methods: {
        onLoad(){
            this.loaded = true;
        }
    },

    computed: {
        classObject(){
            return R.objOf('loaded', this.loaded);
        }
    },

    mounted(){
        this.image.addEventListener('load', this.onLoad);
        this.image.src = this.src;
    },

    beforeDestroy(){
        this.image.removeEventListener('load', this.onLoad);
    },

    template: `
        <img :src="src"
            :width="width"
            :height="height"
            :class="classObject"
        />
    `
});
