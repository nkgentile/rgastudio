<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php wp_head(); ?>
    </head>
	<body>
        <main id="app">
            <?php get_template_part( 'template-parts/header' ); ?>
            <section class="view">
                <hero-banner class="blue">
                    <wp-site-icon
                        url="<?php echo get_site_icon_url(); ?>"
                    >
                    </wp-site-icon>
                    <h1><?php post_type_archive_title(); ?></h1>
                    <?php echo get_theme_mod( 'studio_text_block' ); ?>
                </hero-banner>
                <section class="grid">
                    <card-block v-for="(member, index) in members"
                        :key="index"
                        :href="member.link"
                        :src="thumbnails[index].source_url"
                        :width="thumbnails[index].width"
                        :height="thumbnails[index].height"
                    >
                        <figcaption>
                            <p>{{ member.title.rendered }}</p>
                        </figcaption>
                    </card-block>
                </section>
            </section>
            <i id="open" class="menu-switch fa fa-bars fa-2x" @click="openMenu"></i>
        </main>
		<?php wp_footer(); ?>
        <script type="text/javascript">
            const store = new Vuex.Store({
                modules: {
                    menu
                },

                state: {
                    members: []
                },

                getters: {
                },

                mutations: {
                    updateMembers(state, payload){
                        state.members = state.members.concat(payload);
                    }
                },

                actions: {
                    fetchMembers({ dispatch }, payload){
                        const fetchFeaturedMedia = ({ data }) =>
                            dispatch('fetchFeaturedMedia', data);

                        axios('http://localhost/~nkgentile/clients/RalphGentileArchitects/wp-json/wp/v2/studio')
                        .then(fetchFeaturedMedia);
                    },

                    fetchFeaturedMedia({ dispatch, commit }, projects){
                        const withId = R.objOf('id');
                        const Media = (id) => new wp.api.models.Media(
                            withId(id)
                        );

                        const allFeaturedMedia = projects.map( (p) => {
                            const featuredMedia = Media(p.featured_media);
                            return featuredMedia.fetch();
                        });

                        const associateFeaturedMedia = (featured) =>
                            dispatch(
                                'associateFeaturedMedia',
                                R.zipObj(
                                    ['projects', 'featured'],
                                    [projects, featured]
                                )
                            );

                        Promise.all(allFeaturedMedia)
                        .then(associateFeaturedMedia);
                    },

                    associateFeaturedMedia({ commit }, { projects, featured }){
                        const featuredMediaLens = R.lensProp('featured_media');
                        
                        const projectsWithFeatures = R.zipWith(
                            R.set(featuredMediaLens),
                            featured,
                            projects
                        );

                        const updateProjects = (a) => commit('updateMembers', a);
                        updateProjects(projectsWithFeatures);
                    }
                }
            });
        </script>
        </script>
        <script type="text/javascript">
            Vue.use(Vuex);
            
            const app = new Vue({
                store,

                mixins: [
                    header
                ],

                methods: {
                    fetchMembers(){
                        this.$store.dispatch('fetchMembers');
                    },

                    getImage(member, size){
                        size = R.defaultTo('full', size);

                        return R.path([
                            'media_details',
                            'sizes',
                            size
                        ], member);
                    },

                    getThumbnail(member){
                        const featuredMedia = R.prop('featured_media', member);

                        const size = 'medium';

                        return this.getImage(featuredMedia, size);
                    },

                    getFeaturedImage(member){
                        const featuredMedia = R.prop('featured_media', member);

                        const size = 'full';

                        return this.getImage(featuredMedia, size);
                    }
                },

                computed: {
                    members(){
                        return this.$store.state.members;
                    },

                    thumbnails(){
                        return R.map(this.getThumbnail, this.$store.state.members);
                    }
                },

                created(){
                    this.fetchMembers();
                },
            });

            const mountApp = () => app.$mount('#app');
            wp.api.loadPromise.done(mountApp);
        </script>
	</body>
</html>
