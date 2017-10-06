'use strict';

Vue.component('slideshow-block', {
    props: {
        index: {
            type: Number,
            default: 0
        },

        images: {
            type: Array,
            required: true,
            default: () => [
                {
                    source_url: '',
                    width: 0,
                    height: 0
                }
            ],
            validator: (a) => {
                const aIsEmpty = R.isEmpty(a);
                const addEmptyImage = R.append({
                    source_url: '',
                    width: 0,
                    height: 0
                });
                const returnA = () => a;

                return aIsEmpty ?
                    addEmptyImage(a) :
                    returnA;
            }
        }
    },

    computed: {
        sliderTransform(){
            return {
                transform: `translateX(-${this.index}00%)`
            };
        },

        classObjects(){
            const classObj = R.objOf('active');
            const makeActive = classObj(true);
            const makeInactive = classObj(false);

            const classes = new Array(this.images.length);

            return R.update(
                this.index, 
                makeActive,
                classes
            );
        },

    },

    methods: {
        willGoToIndex(i){
            return () => this.$store.commit('goToIndex', i);
        }
    },

    template: `
        <figure class="slideshow">
            <div class="slideshow-slider"
                :style="sliderTransform"
            >
                <async-image v-for="(image, key) in images"
                    :key="key"
                    :src="image.source_url"
                    :width="image.width"
                    :height="image.height"
                    class="slideshow-image"
                    :class="classObjects[key]"
                >
                </async-image>
            </div>
            <slideshow-info-block>
                <slot></slot>
                <nav class="slideshow-navigation">
                    <slideshow-slide v-for="(image, key) in images"
                        :key="key"
                        :src="image.source_url"
                        :width="image.width"
                        :height="image.height"
                        :onClick="willGoToIndex(key)"
                        :class="classObjects[key]"
                    >
                    </slideshow-slide>
                </nav>
            </slideshow-info-block>
        </figure>
    `
});
