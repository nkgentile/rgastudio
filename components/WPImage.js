'use strict';

Vue.component('wp-image', {
    props: {
        asset: {
            type: Object,
            default: R.zipObj(
                [
                    'source_url',
                    'width',
                    'height'
                ],
                [
                    '',
                    '',
                    ''
                ]
            )
        }
    },

    template: `
        <async-image :src="asset.source_url"
            :width="asset.width"
            :height="asset.height"
        >
        </async-image>

    `
});
