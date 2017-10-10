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
                <gallery-block
                    :assets="attachments"
                >
                    <div class="gallery-info">
                        <h1>{{ title }}</h1>
                    </div>
                </gallery-block>
            </section>
            <i id="open" class="menu-switch fa fa-bars fa-2x" @click="openMenu"></i>
        </main>
		<?php wp_footer(); ?>
        <script type="text/javascript">
            const store = new Vuex.Store({
            	strict: true,
            	
                modules: {
                    menu,
                    post,
                    gallery
                },

                state: {
                    slug: location.href.match(/([^\/]*)\/*$/)[1],
                    attachments: [],
                    project: {}
                },

                getters: {
                },

                mutations: {
                    updateProject( state, payload ){
                        state.project = R.head(payload);
                    }
                },

                actions: {
                    fetchProject({ dispatch, state }){
                        const projects = new wp.api.collections.Projects();

                        const fetchAttachments = (projects) =>
                            dispatch('fetchAttachments', projects);
                            
                    	const withSlug = {
                    		data: {
                    			slug: state.slug
                    		}
                    	};

                        projects.fetch(withSlug)
                        .then(fetchAttachments)
                    },

                    fetchAttachments({ dispatch }, projects){
                        const withParentId = (project) => ({
                            data: {
                                parent: project.id
                            }
                        });

                        const getAttachments = (project) =>
                            new wp.api.collections.Media()
                            .fetch(withParentId(project));

                        const attachments = R.map(getAttachments, projects);

                        const associateAttachments = (attachments) =>
                            dispatch(
                                'associateAttachments',
                                R.zipObj(
                                    ['projects', 'attachments'],
                                    [projects, attachments]
                                )
                            );

                        Promise.all(attachments)
                        .then(associateAttachments);
                    },

                    associateAttachments({ dispatch, commit }, { projects, attachments }){
                        const mapWithIndex = R.addIndex(R.map);

                        const attachmentsLens = R.lensProp('attachments');
                        const associateAttachments = (project, index) =>
                            R.set(attachmentsLens, attachments[index], project);

                        const fetchFeaturedMedia = (a) => dispatch('fetchFeaturedMedia', a);
                        const projectsWithAttachments = mapWithIndex(associateAttachments, projects);

                        fetchFeaturedMedia(projectsWithAttachments);
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

                        const updateProject = (a) => commit('updateProject', a);
                        updateProject(projectsWithFeatures);
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

                    fetchProject(){
                        this.$store.dispatch('fetchProject');
                    },

                    getImage(project, size){
                        size = R.defaultTo('full', size);

                        return R.path([
                            'media_details',
                            'sizes',
                            size
                        ], project);
                    },

                    getThumbnail(project){
                        const featuredMedia = R.prop('featured_media', project);

                        const size = 'medium_large';

                        return this.getImage(featuredMedia, size);
                    },

                    getFeaturedImage(project){
                        const featuredMedia = R.prop('featured_media', project);

                        const size = 'full';

                        return this.getImage(featuredMedia, size);
                    },

                    getCity(project){
                        const city = R.path([
                            'meta',
                            'city'
                        ], project);

                        return R.head(city);
                    },

                    getTitle(project){
                        const city = R.path([
                            'title',
                            'rendered'
                        ]);

                        return city(project);
                    }
                },

                computed: {
                    isMenuOpen(){
                        return this.$store.state.menu.isOpen;
                    },

                    project(){
                        return this.$store.state.project;
                    },

                    attachments(){
                        return R.prop(
                            'attachments',
                            this.project
                        );
                    },

                    title(){
                        return R.path(
                            ['title', 'rendered'],
                            this.project
                        );
                    }
                },

                mounted(){
                    this.fetchProject();
                },
            });

            const mountApp = () => app.$mount('#app');
            wp.api.loadPromise.done(mountApp);
        </script>
	</body>
</html>
