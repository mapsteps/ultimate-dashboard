<?php
/**
 * Output
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Ultimate Dashboard Widget Output
 */
function udb_add_dashboard_widgets() {

	// Custom Post Type Loop
	$args = array(
		'post_type'      => 'udb_widgets',
		'posts_per_page' => 100,
	);
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();

	// vars
	$id       = get_the_ID();
	$title    = get_the_title();
	$icon     = get_post_meta( $id, 'udb_icon_key', true );
	$link     = get_post_meta( $id, 'udb_link', true );
	$target   = get_post_meta( $id, 'udb_link_target', true );
	$position = get_post_meta( $id, 'udb_position_key', true );
	$priority = get_post_meta( $id, 'udb_priority_key', true );

	// Widget Output
	$output = '<a href="'. $link .'" target="'. $target .'"><i class="' . $icon . '"></i></a>';

	$function = function() use ( $output ) {
		echo $output;
	};

	// Add Meta Box
	add_meta_box( 'ms-udb' . $id, $title, $function, 'dashboard', $position, $priority );

	endwhile;

}
add_action( 'wp_dashboard_setup', 'udb_add_dashboard_widgets' );

/**
 * Remove Default WordPress Dashboard Widgets
 */
function udb_remove_default_dashboard_widgets() {

	// vars
	$saved_widgets   = udb_get_saved_default_widgets();
	$default_widgets = udb_get_default_widgets();
	$udb_settings    = get_option( 'udb_settings' );

	if( isset( $udb_settings['remove-all'] ) ) {

		remove_action( 'welcome_panel', 'wp_welcome_panel' );

		foreach ( $default_widgets as $id => $widget ) {
			remove_meta_box( $id, 'dashboard', $widget['context'] );
		}
  
    } else {

		if( isset( $udb_settings['welcome_panel'] ) ) {
			remove_action('welcome_panel', 'wp_welcome_panel');
		}

		foreach ( $saved_widgets as $id => $widget ) {
			remove_meta_box( $id, 'dashboard', $widget['context'] );
		}

	}
}
add_action( 'wp_dashboard_setup', 'udb_remove_default_dashboard_widgets', 100 );