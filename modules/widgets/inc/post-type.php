<?php
/**
 * Setup widget post type.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	// Labels.
	$labels = array(
		'name'               => _x( 'Dashboard Widgets', 'Post type general name', 'ultimate-dashboard' ),
		'singular_name'      => _x( 'Dashboard Widget', 'Post type singular name', 'ultimate-dashboard' ),
		'menu_name'          => _x( 'Ultimate Dash...', 'Admin Menu text', 'ultimate-dashboard' ),
		'name_admin_bar'     => _x( 'Dashboard Widget', 'Add New on Toolbar', 'ultimate-dashboard' ),
		'add_new_item'       => __( 'Add Dashboard Widget', 'ultimate-dashboard' ),
		'new_item'           => __( 'New Dashboard Widget', 'ultimate-dashboard' ),
		'edit_item'          => __( 'Edit Dashboard Widget', 'ultimate-dashboard' ),
		'view_item'          => __( 'View Dashboard Widget', 'ultimate-dashboard' ),
		'all_items'          => __( 'All Widgets', 'ultimate-dashboard' ),
		'search_items'       => __( 'Search Dashboard Widgets', 'ultimate-dashboard' ),
		'not_found'          => __( 'No Dashboard Widgets found.', 'ultimate-dashboard' ),
		'not_found_in_trash' => __( 'No Dashboard Widgets in Trash.', 'ultimate-dashboard' ),
	);

	// Change capabilities so only users that can 'manage_options' are able to access the dashboard widgets & settings.
	$capabilities = array(
		'edit_post'          => 'manage_options',
		'read_post'          => 'manage_options',
		'delete_post'        => 'manage_options',
		'delete_posts'       => 'manage_options',
		'edit_posts'         => 'manage_options',
		'edit_others_posts'  => 'manage_options',
		'publish_posts'      => 'manage_options',
		'read_private_posts' => 'manage_options',
		'create_posts'       => 'manage_options',
	);

	// Arguments.
	$args = array(
		'labels'             => $labels,
		'menu_icon'          => 'dashicons-format-gallery',
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => false,
		'rewrite'            => false,
		'map_meta_cap'       => false,
		'capabilities'       => $capabilities,
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title' ),
	);

	register_post_type( 'udb_widgets', $args );
};
