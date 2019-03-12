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

/*
 * Update Messages
 */
function udb_widgets_updated_messages( $messages ) {

	$post = get_post();

	$messages['udb_widgets'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Widget updated.', 'ultimate-dashboard' ),
		2  => __( 'Custom field updated.', 'ultimate-dashboard' ),
		3  => __( 'Custom field deleted.', 'ultimate-dashboard' ),
		4  => __( 'Widget updated.', 'ultimate-dashboard' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Widget restored to revision from %s', 'ultimate-dashboard' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Widget published.', 'ultimate-dashboard' ),
		7  => __( 'Widget saved.', 'ultimate-dashboard' ),
		8  => __( 'Widget submitted.', 'ultimate-dashboard' ),
		9  => sprintf(
			__( 'Widget scheduled for: <strong>%1$s</strong>.', 'ultimate-dashboard' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'ultimate-dashboard' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Widget draft updated.', 'ultimate-dashboard' )
	);

	return $messages;

}
add_filter( 'post_updated_messages', 'udb_widgets_updated_messages' );

/**
 * Setup Columns
 */
function set_udb_widget_columns( $columns ) {

	$columns = array(
		'cb'    => '<input type="checkbox" />',
		'title' => __( 'Widget Title', 'ultimate-dashboard' ),
		'type'  => __( 'Widget Type', 'ultimate-dashboard' ),
		'date'  => __( 'Date', 'ultimate-dashboard' )
	);

	return $columns;

}
add_filter( 'manage_udb_widgets_posts_columns', 'set_udb_widget_columns' );

/**
 * Widget Columns
 */
function udb_widget_columns( $column, $post_id ) {
	switch ( $column ) {

		case 'type' :

			$content = get_post_meta( $post_id, 'udb_content', true );
			$html    = get_post_meta( $post_id, 'udb_html', true );

			if( $html ) {
				_e( 'HTML', 'ultimate-dashboard' );
			} elseif( $content ) {
				_e( 'Text', 'ultimate-dashboard' );
			} else {
				echo '<i class="'. get_post_meta( $post_id , 'udb_icon_key', true ) .'"></i>';
			}

		break;

	}

}
add_action( 'manage_udb_widgets_posts_custom_column' , 'udb_widget_columns', 10, 2 );