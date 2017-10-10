<?php

add_theme_support( 'post-thumbnails' );

//Plug in front-end frameworks
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );
function enqueue_scripts () {
		$template_uri = get_template_directory_uri();
        $vendor_path = "{$template_uri}/vendor";

	    wp_enqueue_script( 'wp-api' );
        wp_enqueue_script(
            'ramda-js',
            "{$vendor_path}/ramda.min.js"
        );
		wp_enqueue_script(
            'vue-js',
            "{$vendor_path}/vue.js"
        );
        wp_enqueue_script(
            'hammer-js',
            "{$vendor_path}/hammer.min.js"
        );
        wp_enqueue_script(
            'vuex-js',
            "{$vendor_path}/vuex.min.js"
        );
        wp_enqueue_script(
            'font-awesome',
            'https://use.fontawesome.com/13b62f3e97.js'
        );
}

add_action( 'wp_enqueue_scripts', 'enqueue_components' );
function enqueue_components () {
    enqueue_component('gallery-block', 'GalleryBlock');
    enqueue_component('slideshow-block', 'SlideshowBlock.js');
    enqueue_component('async-image', 'AsyncImage.js');
    enqueue_component('slideshow-info-block', 'SlideshowInfoBlock.js');
    enqueue_component('navigation-block', 'NavigationBlock.js');
    enqueue_component('menu-toggle', 'MenuToggle.js');
    enqueue_component('slideshow-slide', 'SlideshowSlide.js');
    enqueue_component('hero-banner', 'HeroBanner.js');
    enqueue_component('card-block', 'CardBlock.js');
    enqueue_component('wp-image', 'WPImage.js');
    enqueue_component('wp-site-icon', 'WPSiteIcon.js');
}

add_action( 'wp_enqueue_scripts', 'enqueue_store_modules' );
function enqueue_store_modules () {
    enqueue_module('menu');
    enqueue_module('showcase');
	enqueue_module('post');
    enqueue_module('gallery');
}

add_action( 'wp_enqueue_scripts', 'enqueue_styles' );
function enqueue_styles () {
    enqueue_style('normalize-css', 'normalize.css');
    enqueue_style('css-app', 'app.css');
    enqueue_style('slideshow-block', 'slideshow-block.css');
    enqueue_style('css-header', 'header.css');
    enqueue_style('hero-banner', 'hero-banner.css');
    enqueue_style('card-block', 'card-block.css');
}

// Create Custom RGA Posts
add_action( 'init', 'create_post_types' );
function create_post_types () {
	// Project
	register_post_type(
		'rga_project',
		array(
			'labels'			=>	array(
				'name'			=>	__( 'Projects' ),
				'singular_name'	=>	__( 'Project' )
			),
			'public'			=>	true,
            'publicly_queryable'    =>  true,
            'query_var'             =>  true,
            'show_in_menu'          =>  true,
            'show_in_rest'      =>  true,
            'rest_base'         =>  'projects',
            'rest_controller_class' =>  'WP_REST_Posts_Controller',
			'rewrite'			=>	array('slug' => 'projects'),
			'menu_position'		=>	5,
			'menu_icon'			=>	'dashicons-location',
			'taxonomies'		=>	array('category'),
			'delete_with_user'	=>	false,
            'has_archive'       =>  true
		)
	);
	add_post_type_support(
		'rga_project',
		array(
			'title',
			'thumbnail',
			'editor',
			'excerpt',
			'post-formats',
            'custom-fields',
            'revisions'
		)
	); 
    register_taxonomy(
        'tag',
        'rga_project',
        [
            'show_ui'   =>  true,
        ]
    );
	
	// Member
	register_post_type(
		'rga_member',
		array(
			'labels'			=>	array(
				'name'			=>	__( 'Studio' ),
				'singular_name'	=>	__( 'Member' )
			),
			'public'			=>	true,
			'has_archive'		=>	true,
			'rewrite'			=>	array('slug' => 'studio'),
			'menu_position'		=>	6,
			'menu_icon'			=>	'dashicons-businessman',
			'delete_with_user'	=>	false
		)
	);
	add_post_type_support(
		'rga_member',
		array(
			'title',
			'thumbnail',
			'editor',
			'excerpt',
			'custom-fields'
		)
	); 
}

// Add custom endpoints to WP-API
add_action( 'rest_api_init', 'register_custom_endpoints');
function register_custom_endpoints () {
    register_rest_field( 'rga_project', 'meta', [
        'get_callback'  =>  'get_post_meta_for_api',
        'schema'        =>  null
    ]);

    function get_post_meta_for_api( $object ){
        $post_id = $object['id'];

        return get_post_meta( $post_id );
    }
}

add_action( 'customize_register', 'register_theme_customizer' );
function register_theme_customizer( $wp_customize ) {
    $wp_customize->add_panel( 'text_blocks', [
        'priority'          =>  100,
        'theme_supports'    =>  '',
        'title'             =>  __( 'Text Blocks', 'rga' ),
        'description'       =>  __( 'Set editable text for certain content.', 'rga')
    ]);

	/* Projects Text Block */
    $wp_customize->add_section( 'projects_page', [
        'priority'  =>  10,
        'title'     =>  __('Projects Page Text', 'rga'),
        'panel'     =>  'text_blocks'
    ]);
    
    $wp_customize->add_setting( 'projects_text_block', [
        'default'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam in ultricies dui, vitae sodales sapien. Cras quis metus quis magna efficitur laoreet non a urna. Proin interdum arcu vitae porta luctus. Donec lorem sem, elementum eu porta vel, elementum congue mauris. Nunc hendrerit iaculis dui sit amet tincidunt. Donec varius dignissim velit a vulputate. Nullam eget arcu massa. Etiam ac arcu ut ligula ultricies convallis. Duis eu erat sit amet ipsum lobortis scelerisque vitae eget tellus. In lacinia mauris sed lobortis porttitor. Pellentesque aliquam vitae sapien et fringilla.', 'rga' )
    ]);
    
    $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize,
        'projects_page',
        [
            'label'     =>  __( 'Projects Page', 'rga' ),
            'section'   =>  'projects_page',
            'settings'  =>  'projects_text_block',
            'type'      =>  'textarea'
        ]
    ));
    
	/* Studio Text Block */
    $wp_customize->add_section( 'studio_page', [
        'priority'  =>  11,
        'title'     =>  __('Studio Page Text', 'rga'),
        'panel'     =>  'text_blocks'
    ]);

    $wp_customize->add_setting( 'studio_text_block', [
        'default'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam in ultricies dui, vitae sodales sapien. Cras quis metus quis magna efficitur laoreet non a urna. Proin interdum arcu vitae porta luctus. Donec lorem sem, elementum eu porta vel, elementum congue mauris. Nunc hendrerit iaculis dui sit amet tincidunt. Donec varius dignissim velit a vulputate. Nullam eget arcu massa. Etiam ac arcu ut ligula ultricies convallis. Duis eu erat sit amet ipsum lobortis scelerisque vitae eget tellus. In lacinia mauris sed lobortis porttitor. Pellentesque aliquam vitae sapien et fringilla.', 'rga' ),
        'sanitize_callback' =>  'sanitize_textarea_field'
    ]);

    $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize,
        'studio_page',
        [
            'label'     =>  __( 'Studio Page', 'rga' ),
            'section'   =>  'studio_page',
            'settings'  =>  'studio_text_block',
            'type'      =>  'textarea'
        ]
    ));
}

/* Utility Functions */
function enqueue_component ($name, $filename) {
	$template = get_template_directory_uri();
	$components = "{$template}/components";
	wp_enqueue_script(
		$name,
		"{$components}/{$filename}",
		[ 'vue-js' ]
	);
}

function enqueue_style ($name, $filename) {
	$template_uri = get_template_directory_uri();
	$css_uri = "{$template_uri}/css";

	wp_enqueue_style(
		$name,
		"{$css_uri}/{$filename}",
		[]
	);
}

function enqueue_module($name) {
	$template = get_template_directory_uri();
	$components = "{$template}/store";
	wp_enqueue_script(
		$name,
		"{$components}/{$name}.js"
	);
}
