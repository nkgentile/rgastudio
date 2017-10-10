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
                <slideshow-block
                    :images="featuredProjectImages"
                    :index="index"
                >
                    <article>
                        <h1>{{ title }}</h1>
                        <h2>{{ city }}</h2>
                        <p v-html="excerpt"></p>
                        <a :href="link">View Project</a>
                    </article>
                </slideshow-block>
            </section>
            <i id="open" class="menu-switch fa fa-bars fa-2x" @click="openMenu"></i>
        </main>
		<?php wp_footer(); ?>
        <script type="text/javascript">
            const store = new Vuex.Store({
                modules: {
                    menu,
                    showcase
                },

                state: {
                    projects: [],
                    media: [],
                    featured: []
                },

                getters: {
                    attachments: (state) => (post) => {
                        const isAttachment = R.propEq('post', post.id);
                        const filterAttachments = R.filter(isAttachment);

                        return filterAttachments(state.media);
                    },

                    featuredProjects(state){
                        return R.slice(0, 5, state.projects);
                    },
                },

                mutations: {
                    setFeaturedProjects(state, payload){
                        state.featured = payload;
                    },

                    updateProjects (state, payload) {
                        state.projects = [...state.projects, ...payload];
                    },

                    updateMedia (state, payload) {
                        state.media = [...state.media, ...payload];
                    }
                },

                actions: {
                    fetchFeaturedProjects({ state, commit, dispatch }){
                        const filter = R.objOf('orderby', 'date');
                        const projects = new wp.api.collections.Projects(filter);
                        const fetchFeaturedMedia = (r) => 
                            dispatch('filterFeaturedProjects', r);
                        
                        projects.fetch()
                        .then(fetchFeaturedMedia);
                    },

                    filterFeaturedProjects({ state, commit, dispatch }, projects){
                        const hasNoFeaturedMedia = R.propEq('featured_media', 0);

                        const filteredProjects = R.reject(
                            hasNoFeaturedMedia,
                            projects
                        );

                        dispatch('fetchFeaturedMedia', filteredProjects);
                    },

                    fetchFeaturedMedia({ state, commit, dispatch }, projects){
                        const withId = R.objOf('id');
                        const Media = (id) => new wp.api.models.Media(
                            withId(id)
                        );

                        const allFeaturedMedia = projects.map( (p) => {
                            const featuredMedia = Media(p.featured_media);
                            return featuredMedia.fetch();
                        });

                        Promise.all(allFeaturedMedia)
                        .then( (media) => {
                            const featuredMediaPath = R.lensProp('featured_media');
                            const featuredProjects = R.zipWith(
                                R.set(featuredMediaPath),
                                media,
                                projects
                            );

                            commit('setFeaturedProjects', featuredProjects);
                        });
                    }
                }
            });
        </script>
        <script type="text/javascript">
            Vue.use(Vuex);

            const app = new Vue({
                store,

                methods: {
                    openMenu(){
                        this.$store.commit('menu/open');
                    },

                    closeMenu(){
                        this.$store.commit('menu/close');
                    },

                    fetchFeaturedProjects(){
                        this.$store.dispatch('fetchFeaturedProjects');
                    }
                },

                computed: {
                    isMenuOpen(){
                        return this.$store.state.menu.isOpen;
                    },

                    featuredProjects(){
                        return this.$store.state.featured;
                    },

                    featuredProjectImages(){
                        const sizeLarge = R.path([
                            'featured_media',
                            'media_details',
                            'sizes',
                            'full'
                        ]);

                        return R.map(sizeLarge, this.featuredProjects);
                    },

                    title(){
                        const title = R.path(['title', 'rendered']);
                        return title(this.activeProject);
                    },

                    link(){
                        const link = R.prop('link');
                        const emptyLink = R.objOf('link', '');
                        const project = R.defaultTo(emptyLink, this.activeProject);
                        return link(project);
                    },

                    city(){
                        const city = R.path(['meta', 'city']);
                        const emptyCity = R.objOf('meta', { city: [''] });
                        const project = R.defaultTo(emptyCity, this.activeProject);

                        return R.head(city(project));
                    },

                    excerpt(){
                        const excerpt = R.path(['excerpt', 'rendered']);
                        return excerpt(this.activeProject);
                    },

                    index(){
                        return this.$store.state.showcase.index;
                    },

                    activeProject(){
                        return this.featuredProjects[this.index];
                    }
                },

                created(){
                    this.fetchFeaturedProjects();
                },
            });

            const mountApp = () => app.$mount('#app');
            wp.api.loadPromise.done(mountApp);
        </script>
	</body>
</html>
