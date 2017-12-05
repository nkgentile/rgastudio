'use strict';

Vue.component('tag-block', {
    props: {
        tags: {
            type: Array,
            default: () => []
        }
    },

    computed: {
        names(){
            return R.map(
                R.prop('name'),
                this.tags
            );
        }
    },

    template: `
        <ul class="tags">
            <li v-for="(tag, i) in tags"
                :key="i"
            >
                {{ names[i] }}
            </li>
        </ul>
    `
});
