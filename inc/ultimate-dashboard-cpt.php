<?php
/**
 * Custom Post Type
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Post Type Setup
 */
function udb_post_type() {

	// Labels
	$labels = array(
		'name'               => _x( 'Dashboard Widgets', 'Post type general name', 'ultimate-dashboard' ),
		'singular_name'      => _x( 'Dashboard Widget', 'Post type singular name', 'ultimate-dashboard' ),
		'menu_name'          => _x( 'Widgets', 'Admin Menu text', 'ultimate-dashboard' ),
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

	// change capabilities so only users with 'manage_options' capabilities can mess with dashboard widgets
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

	// Arguments
	$args = array(
		'labels'				=> $labels,
		'menu_icon'				=> 'dashicons-format-gallery',
		'publicly_queryable'	=> false,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'query_var'				=> false,
		'rewrite'				=> false,
		'map_meta_cap'			=> false,
		'capabilities'			=> $capabilities,
		'has_archive'			=> false,
		'hierarchical'			=> false,
		'supports'				=> array( 'title' )
	);

	register_post_type( 'udb_widgets', $args );

}
add_action( 'init', 'udb_post_type' );

/**
 * Setup Columns
 */
function set_udb_widget_columns( $columns ) {

	$columns = array(
		'cb'    => '<input type="checkbox" />',
		'title' => __( 'Widget Title', 'ultimate-dashboard' ),
		'icon'  => __( 'Icon', 'ultimate-dashboard' ),
		'link'  => __( 'Link Target', 'ultimate-dashboard' ),
		'date'  => __( 'Date', 'ultimate-dashboard' ),
	);

	return $columns;

}
add_filter( 'manage_udb_widgets_posts_columns', 'set_udb_widget_columns' );

/**
 * Widget Columns
 */
function udb_widget_columns( $column, $post_id ) {

	switch ( $column ) {

		case 'icon' :

			$content = get_post_meta( $post_id, 'udb_content', true );

			if ( !$content ) {

				$icon = get_post_meta( $post_id, 'udb_icon_key', true );
				echo '<i class="'. esc_attr( $icon ) .'"></i>';

			}

		break;

		case 'link' :

			$link    = get_post_meta( $post_id, 'udb_link', true ); 
			$replace = array( "http://", "https://", "www." );

			echo str_replace( $replace, '', $link );

		break;

	}

}
add_action( 'manage_udb_widgets_posts_custom_column' , 'udb_widget_columns', 10, 2 );