<?php
/**
 * Setup admin page post type.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	// Labels.
	$labels = array(
		'name'               => _x( 'Admin Pages', 'ultimate-dashboard' ),
		'singular_name'      => _x( 'Admin Page', 'ultimate-dashboard' ),
		'menu_name'          => _x( 'Admin Pages', 'ultimate-dashboard' ),
		'name_admin_bar'     => _x( 'Admin Page', 'ultimate-dashboard' ),
		'add_new_item'       => __( 'Add Admin Page', 'ultimate-dashboard' ),
		'new_item'           => __( 'New Admin Page', 'ultimate-dashboard' ),
		'edit_item'          => __( 'Edit Admin Page', 'ultimate-dashboard' ),
		'view_item'          => __( 'View Admin Page', 'ultimate-dashboard' ),
		'all_items'          => __( 'Admin Pages', 'ultimate-dashboard' ),
		'search_items'       => __( 'Search Admin Pages', 'ultimate-dashboard' ),
		'not_found'          => __( 'No Admin Pages found.', 'ultimate-dashboard' ),
		'not_found_in_trash' => __( 'No Admin Pages in Trash.', 'ultimate-dashboard' ),
	);

	// Change capabilities so only users that can 'manage_options' are able to access the Admin Pages & settings.
	$capabilities = array(
		'edit_post'          => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'read_post'          => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'delete_post'        => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'delete_posts'       => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'edit_posts'         => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'edit_others_posts'  => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'publish_posts'      => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'read_private_posts' => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'create_posts'       => apply_filters( 'udb_settings_capability', 'manage_options' ),
	);

	// Arguments.
	$args = array(
		'labels'              => $labels,
		'menu_icon'           => 'dashicons-format-gallery',
		'public'              => true,
		'exclude_from_search' => true,
		'show_in_menu'        => false,
		'show_in_rest'        => false,
		'query_var'           => false,
		'rewrite'             => false,
		'map_meta_cap'        => false,
		'capabilities'        => $capabilities,
		'has_archive'         => false,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor' ),
	);

	register_post_type( 'udb_admin_page', $args );

};
