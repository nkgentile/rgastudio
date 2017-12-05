'use strict';

const post = {
    namespaced: true,

    state: {
    },
    
    getters: {
    },

    mutations: {
		updateProjects(state, payload){
			state.projects = state.projects.concat(payload);
		}
    },

    actions: {
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

			return Promise.all(attachments);
		},

		associateAttachments({ dispatch, commit }, { projects, attachments }){
			const mapWithIndex = R.addIndex(R.map);

			const attachmentsLens = R.lensProp('attachments');
			const associateAttachments = (project, index) =>
				R.set(attachmentsLens, attachments[index], project);

			const projectsWithAttachments = mapWithIndex(associateAttachments, projects);

			return projectsWithAttachments;
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

			return  Promise.all(allFeaturedMedia);
		},

		associateFeaturedMedia({ commit }, { projects, featured }){
			const featuredMediaLens = R.lensProp('featured_media');
	
			const projectsWithFeatures = R.zipWith(
				R.set(featuredMediaLens),
				featured,
				projects
			);
			
			return projectsWithFeatures;
		}
    }
}
