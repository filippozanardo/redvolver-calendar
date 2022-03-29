<?php

class Redvolver_Post_Types {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ), 0 );
		add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
	}

	/**
	 * Registers the custom post type and taxonomies.
	 */
	public function register_post_types() {

		//if ( post_type_exists( "jrr_spotlight" ) )
			//return;

		$admin_capability = 'manage_options';

		// $labels = array(
		// 	'name'                  => __( 'Calendar', 'redvolver' ),
		// 	'singular_name'         => __( 'Calendar', 'redvolver' ),
		// 	'add_new'            => __( 'Add New', 'redvolver' ),
		// 	'add_new_item'       => __( 'Add New Calendar', 'redvolver' ),
		// 	'edit_item'          => __( 'Edit Calendar', 'redvolver' ),
		// 	'new_item'           => __( 'New Calendar', 'redvolver' ),
		// 	'all_items'          => __( 'All Calendars', 'redvolver' ),
		// 	'view_item'          => __( 'View Calendar', 'redvolver' ),
		// 	'search_items'       => __( 'Search Calendars', 'redvolver' ),
		// 	'not_found'          => __( 'No Calendars found', 'redvolver' ),
		// 	'not_found_in_trash' => __( 'No Calendars found in Trash', 'redvolver' ),
		// 	'parent_item_colon'  => '',
		// 	'menu_name'          => __( 'Calendar', 'redvolver' )
		// );
		//
		// $args = array(
		// 	'labels'              => $labels,
		// 	'hierarchical'        => false,
		// 	'supports'            => array( 'title', 'editor', 'thumbnail','author' ),
		// 	'taxonomies'          => array(),
		// 	'public'              => true,
		// 	'show_ui'             => true,
		// 	'show_in_menu'        => true,
		// 	'show_in_nav_menus'   => true,
		// 	'publicly_queryable'  => true,
		// 	'exclude_from_search' => true,
		// 	'has_archive'         => true,
		// 	'query_var'           => true,
		// 	'can_export'          => true,
		// 	'rewrite'             => array( 'slug' => 'rvc' ),
		// 	'capability_type'     => 'post',
		// 	'menu_position'       => null,
		// 	'show_in_rest'				=> true,
		// 	'rest_base'          => 'rvc',
    // 	'rest_controller_class' => 'WP_REST_Posts_Controller',
		// 	'capabilities' => array(
		//         'publish_posts' => $admin_capability,
		//         'edit_posts' => $admin_capability,
		//         'edit_others_posts' => $admin_capability,
		//         'delete_posts' => $admin_capability,
		//         'delete_others_posts' => $admin_capability,
		//         'read_private_posts' => $admin_capability,
		//         'edit_post' => $admin_capability,
		//         'delete_post' => $admin_capability,
		//         'read_post' => $admin_capability,
		//     ),
		// );
		// register_post_type( 'rvc', $args );



		$labels = array(
			'name'                  => __( 'Timecard', 'redvolver' ),
			'singular_name'         => __( 'Timecard', 'redvolver' ),
			'add_new'            => __( 'Add New', 'redvolver' ),
			'add_new_item'       => __( 'Add New Timecard', 'redvolver' ),
			'edit_item'          => __( 'Edit Timecard', 'redvolver' ),
			'new_item'           => __( 'New Timecards', 'redvolver' ),
			'all_items'          => __( 'All Timecards', 'redvolver' ),
			'view_item'          => __( 'View Timecard', 'redvolver' ),
			'search_items'       => __( 'Search Timecards', 'redvolver' ),
			'not_found'          => __( 'No Timecards found', 'redvolver' ),
			'not_found_in_trash' => __( 'No Timecards found in Trash', 'redvolver' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Timecard', 'redvolver' )
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title','author' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'timecard' ),
			'capability_type'     => 'post',
			//'menu_position'       => null,
			//'show_in_rest'				=> true,
			//'rest_base'          => 'rvc',
    	//'rest_controller_class' => 'WP_REST_Posts_Controller',
			// 'capabilities' => array(
		  //       'publish_posts' => $admin_capability,
		  //       'edit_posts' => $admin_capability,
		  //       'edit_others_posts' => $admin_capability,
		  //       'delete_posts' => $admin_capability,
		  //       'delete_others_posts' => $admin_capability,
		  //       'read_private_posts' => $admin_capability,
		  //       'edit_post' => $admin_capability,
		  //       'delete_post' => $admin_capability,
		  //       'read_post' => $admin_capability,
		  //  ),
		);
		register_post_type( 'timecard', $args );

		$labels = array(
			'name'                  => __( 'Project', 'redvolver' ),
			'singular_name'         => __( 'Project', 'redvolver' ),
			'add_new'            => __( 'Add New', 'redvolver' ),
			'add_new_item'       => __( 'Add New Project', 'redvolver' ),
			'edit_item'          => __( 'Edit Project', 'redvolver' ),
			'new_item'           => __( 'New Projects', 'redvolver' ),
			'all_items'          => __( 'All Projects', 'redvolver' ),
			'view_item'          => __( 'View Project', 'redvolver' ),
			'search_items'       => __( 'Search Projects', 'redvolver' ),
			'not_found'          => __( 'No Projects found', 'redvolver' ),
			'not_found_in_trash' => __( 'No Projects found in Trash', 'redvolver' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Project', 'redvolver' )
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title','author','editor','thumbnail' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'project' ),
			'capability_type'     => 'post',
			//'menu_position'       => null,
			//'show_in_rest'				=> true,
			//'rest_base'          => 'rvc',
    	//'rest_controller_class' => 'WP_REST_Posts_Controller',
			// 'capabilities' => array(
		  //       'publish_posts' => $admin_capability,
		  //       'edit_posts' => $admin_capability,
		  //       'edit_others_posts' => $admin_capability,
		  //       'delete_posts' => $admin_capability,
		  //       'delete_others_posts' => $admin_capability,
		  //       'read_private_posts' => $admin_capability,
		  //       'edit_post' => $admin_capability,
		  //       'delete_post' => $admin_capability,
		  //       'read_post' => $admin_capability,
		  //  ),
		);
		register_post_type( 'project', $args );


		$labels = array(
			'name'                  => __( 'Client', 'redvolver' ),
			'singular_name'         => __( 'Client', 'redvolver' ),
			'add_new'            => __( 'Add New', 'redvolver' ),
			'add_new_item'       => __( 'Add New Client', 'redvolver' ),
			'edit_item'          => __( 'Edit Client', 'redvolver' ),
			'new_item'           => __( 'New Clients', 'redvolver' ),
			'all_items'          => __( 'All Clients', 'redvolver' ),
			'view_item'          => __( 'View Client', 'redvolver' ),
			'search_items'       => __( 'Search Clients', 'redvolver' ),
			'not_found'          => __( 'No Clients found', 'redvolver' ),
			'not_found_in_trash' => __( 'No Clients found in Trash', 'redvolver' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Client', 'redvolver' )
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title','author','editor','thumbnail' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'client' ),
			'capability_type'     => 'post',
			//'menu_position'       => null,
			//'show_in_rest'				=> true,
			//'rest_base'          => 'rvc',
    	//'rest_controller_class' => 'WP_REST_Posts_Controller',
			// 'capabilities' => array(
		  //       'publish_posts' => $admin_capability,
		  //       'edit_posts' => $admin_capability,
		  //       'edit_others_posts' => $admin_capability,
		  //       'delete_posts' => $admin_capability,
		  //       'delete_others_posts' => $admin_capability,
		  //       'read_private_posts' => $admin_capability,
		  //       'edit_post' => $admin_capability,
		  //       'delete_post' => $admin_capability,
		  //       'read_post' => $admin_capability,
		  //  ),
		);
		register_post_type( 'client', $args );

		$labels = array(
			'name'                  => __( 'Agency', 'redvolver' ),
			'singular_name'         => __( 'Agency', 'redvolver' ),
			'add_new'            => __( 'Add New', 'redvolver' ),
			'add_new_item'       => __( 'Add New Agency', 'redvolver' ),
			'edit_item'          => __( 'Edit Agency', 'redvolver' ),
			'new_item'           => __( 'New Agencies', 'redvolver' ),
			'all_items'          => __( 'All Agencies', 'redvolver' ),
			'view_item'          => __( 'View Agency', 'redvolver' ),
			'search_items'       => __( 'Search Agencies', 'redvolver' ),
			'not_found'          => __( 'No Agencies found', 'redvolver' ),
			'not_found_in_trash' => __( 'No Agencies found in Trash', 'redvolver' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Agency', 'redvolver' )
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title','author','editor','thumbnail' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'agency' ),
			'capability_type'     => 'post',
			//'menu_position'       => null,
			//'show_in_rest'				=> true,
			//'rest_base'          => 'rvc',
    	//'rest_controller_class' => 'WP_REST_Posts_Controller',
			// 'capabilities' => array(
		  //       'publish_posts' => $admin_capability,
		  //       'edit_posts' => $admin_capability,
		  //       'edit_others_posts' => $admin_capability,
		  //       'delete_posts' => $admin_capability,
		  //       'delete_others_posts' => $admin_capability,
		  //       'read_private_posts' => $admin_capability,
		  //       'edit_post' => $admin_capability,
		  //       'delete_post' => $admin_capability,
		  //       'read_post' => $admin_capability,
		  //  ),
		);
		register_post_type( 'agency', $args );


	}

	public function register_taxonomies( ) {

		// $labels = array(
		// 	'name'                       => __( 'Calendar Tag', 'rv_framework' ),
		// 	'singular_name'              => __( 'Calendar Tag', 'rv_framework' ),
		// 	'search_items'               => __( 'Search Calendar Tags', 'rv_framework' ),
		// 	'popular_items'              => __( 'Popular Calendar Tags', 'rv_framework' ),
		// 	'all_items'                  => __( 'All Calendar Tags', 'rv_framework' ),
		// 	'parent_item'                => __( 'Parent Calendar Tag', 'rv_framework' ),
		// 	'parent_item_colon'          => __( 'Parent Calendar Tag:', 'rv_framework' ),
		// 	'edit_item'                  => __( 'Edit Calendar Tag', 'rv_framework' ),
		// 	'update_item'                => __( 'Update Calendar Tag', 'rv_framework' ),
		// 	'add_new_item'               => __( 'Add New Calendar Tag', 'rv_framework' ),
		// 	'new_item_name'              => __( 'New Calendar Tag', 'rv_framework' ),
		// 	'separate_items_with_commas' => __( 'Separate Calendar Tags with commas', 'rv_framework' ),
		// 	'add_or_remove_items'        => __( 'Add or remove Calendar Tags', 'rv_framework' ),
		// 	'choose_from_most_used'      => __( 'Choose from most used Calendar Tags', 'rv_framework' ),
		// 	'menu_name'                  => __( 'Calendar Tags', 'rv_framework' ),
		// );
		//
		// $args = array(
		// 	'labels'            => $labels,
		// 	'public'            => true,
		// 	'show_in_nav_menus' => true,
		// 	'show_ui'           => true,
		// 	'show_tagcloud'     => false,
		// 	'hierarchical'      => true,
		// 	'rewrite'           => array( 'slug' => 'rvc-tag' ),
		// 	'query_var'         => true,
		// 	'show_admin_column' => true,
		// 	'meta_box_cb' => false,
		// 	//'capabilities' => array('manage_terms' => 'soloio','edit_terms'   => 'soloio','delete_terms' => 'soloio',),
		// );
		//
		// register_taxonomy( 'rvc-tag', array('rvc'), apply_filters( 'rv_framework_register_taxonomy_tag', $args ) );

		// $labels = array(
		// 	'name'                       => __( 'Calendar Client', 'rv_framework' ),
		// 	'singular_name'              => __( 'Calendar Client', 'rv_framework' ),
		// 	'search_items'               => __( 'Search Calendar Clients', 'rv_framework' ),
		// 	'popular_items'              => __( 'Popular Calendar Clients', 'rv_framework' ),
		// 	'all_items'                  => __( 'All Calendar Clients', 'rv_framework' ),
		// 	'parent_item'                => __( 'Parent Calendar Client', 'rv_framework' ),
		// 	'parent_item_colon'          => __( 'Parent Calendar Client:', 'rv_framework' ),
		// 	'edit_item'                  => __( 'Edit Calendar Client', 'rv_framework' ),
		// 	'update_item'                => __( 'Update Calendar Client', 'rv_framework' ),
		// 	'add_new_item'               => __( 'Add New Calendar Client', 'rv_framework' ),
		// 	'new_item_name'              => __( 'New Calendar Client', 'rv_framework' ),
		// 	'separate_items_with_commas' => __( 'Separate Calendar Clients with commas', 'rv_framework' ),
		// 	'add_or_remove_items'        => __( 'Add or remove Calendar Clients', 'rv_framework' ),
		// 	'choose_from_most_used'      => __( 'Choose from most used Calendar Clients', 'rv_framework' ),
		// 	'menu_name'                  => __( 'Calendar Clients', 'rv_framework' ),
		// );
		//
		// $args = array(
		// 	'labels'            => $labels,
		// 	'public'            => true,
		// 	'show_in_nav_menus' => true,
		// 	'show_ui'           => true,
		// 	'show_tagcloud'     => false,
		// 	'hierarchical'      => true,
		// 	'rewrite'           => array( 'slug' => 'rvc-client' ),
		// 	'query_var'         => true,
		// 	'show_admin_column' => true,
		// 	'meta_box_cb' => false,
		// 	//'capabilities' => array('manage_terms' => 'soloio','edit_terms'   => 'soloio','delete_terms' => 'soloio',),
		// );
		//
		// register_taxonomy( 'rvc-client', array('rvc'), apply_filters( 'rv_framework_register_taxonomy_client', $args ) );

		$labels = array(
			'name'                       => __( 'Project Status', 'rv_framework' ),
			'singular_name'              => __( 'Project Status', 'rv_framework' ),
			'search_items'               => __( 'Search Project Status', 'rv_framework' ),
			'popular_items'              => __( 'Popular Project Status', 'rv_framework' ),
			'all_items'                  => __( 'All Project Status', 'rv_framework' ),
			'parent_item'                => __( 'Parent Project Status', 'rv_framework' ),
			'parent_item_colon'          => __( 'Parent Project Status:', 'rv_framework' ),
			'edit_item'                  => __( 'Edit Project Status', 'rv_framework' ),
			'update_item'                => __( 'Update Project Status', 'rv_framework' ),
			'add_new_item'               => __( 'Add New Project Status', 'rv_framework' ),
			'new_item_name'              => __( 'New Project Status', 'rv_framework' ),
			'separate_items_with_commas' => __( 'Separate Project Status with commas', 'rv_framework' ),
			'add_or_remove_items'        => __( 'Add or remove Project Status', 'rv_framework' ),
			'choose_from_most_used'      => __( 'Choose from most used Project Status', 'rv_framework' ),
			'menu_name'                  => __( 'Project Status', 'rv_framework' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'projectstatus' ),
			'query_var'         => true,
			'show_admin_column' => true,
			'meta_box_cb' => false,
			//'capabilities' => array('manage_terms' => 'soloio','edit_terms'   => 'soloio','delete_terms' => 'soloio',),
		);

		register_taxonomy( 'project_status', array('project'), apply_filters( 'rv_tax_project_status', $args ) );

		$labels = array(
			'name'                       => __( 'Project Type', 'rv_framework' ),
			'singular_name'              => __( 'Project Type', 'rv_framework' ),
			'search_items'               => __( 'Search Project Type', 'rv_framework' ),
			'popular_items'              => __( 'Popular Project Type', 'rv_framework' ),
			'all_items'                  => __( 'All Project Type', 'rv_framework' ),
			'parent_item'                => __( 'Parent Project Type', 'rv_framework' ),
			'parent_item_colon'          => __( 'Parent Project Type:', 'rv_framework' ),
			'edit_item'                  => __( 'Edit Project Type', 'rv_framework' ),
			'update_item'                => __( 'Update Project Type', 'rv_framework' ),
			'add_new_item'               => __( 'Add New Project Type', 'rv_framework' ),
			'new_item_name'              => __( 'New Project Type', 'rv_framework' ),
			'separate_items_with_commas' => __( 'Separate Project Type with commas', 'rv_framework' ),
			'add_or_remove_items'        => __( 'Add or remove Type Status', 'rv_framework' ),
			'choose_from_most_used'      => __( 'Choose from most used Project Type', 'rv_framework' ),
			'menu_name'                  => __( 'Project Type', 'rv_framework' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'project_type' ),
			'query_var'         => true,
			'show_admin_column' => true,
			'meta_box_cb' => false,
			//'capabilities' => array('manage_terms' => 'soloio','edit_terms'   => 'soloio','delete_terms' => 'soloio',),
		);

		register_taxonomy( 'project_type', array('project'), apply_filters( 'rv_tax_project_type', $args ) );

	}

}
