<?php
/**
 * Custom Post Type
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// Register Ultimate Dashboard Post Type
add_action( 'init', 'udb_post_type' );

function udb_post_type() {

	// Labels
	$labels = array(
		'name'					=> _x( 'Dashboard Widgets', 'ultimatedashboard' ),
		'singular_name'			=> _x( 'Dashboard Widget', 'ultimatedashboard' ),
		'menu_name'				=> _x( 'Widgets', 'ultimatedashboard' ),
		'name_admin_bar'		=> _x( 'Dashboard Widget', 'ultimatedashboard' ),
		'add_new_item'			=> __( 'Add Dashboard Widget', 'ultimatedashboard' ),
		'new_item'				=> __( 'New Dashboard Widget', 'ultimatedashboard' ),
		'edit_item'				=> __( 'Edit Dashboard Widget', 'ultimatedashboard' ),
		'view_item'				=> __( 'View Dashboard Widget', 'ultimatedashboard' ),
		'all_items'				=> __( 'All Widgets', 'ultimatedashboard' ),
		'search_items'			=> __( 'Search Dashboard Widgets', 'ultimatedashboard' ),
		'not_found'				=> __( 'No Dashboard Widgets found.', 'ultimatedashboard' ),
		'not_found_in_trash'	=> __( 'No Dashboard Widgets in Trash.', 'ultimatedashboard' )
	);

	// change capabilities so only users with 'manage_options' capabilities can mess with dashboard widgets
	$capabilities = array( 
		'edit_post'				=> 'manage_options',
		'read_post'				=> 'manage_options',
		'delete_post'			=> 'manage_options',
		'delete_posts'			=> 'manage_options',
		'edit_posts'			=> 'manage_options',
		'edit_others_posts'		=> 'manage_options',
		'publish_posts'			=> 'manage_options',
		'read_private_posts'	=> 'manage_options',
		'create_posts'			=> 'manage_options'
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

// Columns
add_filter( 'manage_udb_widgets_posts_columns', 'set_udb_widget_columns' );

function set_udb_widget_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Widget Title', 'ultimatedashboard' ),
		'icon' => __( 'Icon', 'ultimatedashboard' ),
		'link' => __( 'Link Target', 'ultimatedashboard' ),
		'date' => __( 'Date', 'ultimatedashboard' )
	);

	return $columns;

}


// Columns output
add_action( 'manage_udb_widgets_posts_custom_column' , 'udb_widget_columns', 10, 2 );

function udb_widget_columns( $column, $post_id ) {
	switch ( $column ) {

		case 'icon' :

			$content = get_post_meta( $post_id, 'udb_content', true );
			if ( !$content ) {
				$icon = get_post_meta( $post_id , 'udb_icon_key', true ); 
				echo '<i class="'. $icon .'"></i>';
			}

		break;

		case 'link' :

			$link = get_post_meta( $post_id, 'udb_link', true ); 
			$replace = array("http://", "https://", "www.");
			echo str_replace($replace, "", $link);

		break;

	}
}