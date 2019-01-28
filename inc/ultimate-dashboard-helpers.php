<?php
/**
 * Helpers
 *
 * @package Ultimate Dashboard PRO
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get DB Widgets
 */
function udb_get_db_widgets() {

	global $wp_meta_boxes;

	if ( ! is_array( $wp_meta_boxes['dashboard'] ) ) {
		require_once ABSPATH . '/wp-admin/includes/dashboard.php';

		$current_screen = get_current_screen();

		set_current_screen( 'dashboard' );

		remove_action( 'wp_dashboard_setup', 'udb_remove_default_dashboard_widgets', 100 );
		remove_action( 'wp_dashboard_setup', 'udb_extras_remove_third_party_widgets', 100 );

		wp_dashboard_setup();

		add_action( 'wp_dashboard_setup', 'udb_remove_default_dashboard_widgets', 100 );
		add_action( 'wp_dashboard_setup', 'udb_extras_remove_third_party_widgets', 100 );

		set_current_screen( $current_screen );

	}

	$widgets = $wp_meta_boxes['dashboard'];

	return $widgets;

}

/**
 * Get Widgets
 */
function udb_get_widgets() {

	$widgets = udb_get_db_widgets();

	foreach ( $widgets as $context => $priority ) {
		foreach ( $priority as $data ) {
			foreach ( $data as $id => $widget ) {
				$widget['title_stripped'] = wp_strip_all_tags( $widget['title'] );
				$widget['context']        = $context;

				$flat_widgets[ $id ] = $widget;
			}
		}
	}

	$widgets = wp_list_sort( $flat_widgets, array( 'title_stripped' => 'ASC' ), null, true );

	return $widgets;

}

/**
 * Get Default Widgets
 */
function udb_get_default_widgets() {

	$widgets = udb_get_widgets();

	$default_widgets = array(
		'dashboard_primary'         => array(),
		'dashboard_quick_press'     => array(),
		'dashboard_right_now'       => array(),
		'dashboard_activity'        => array(),
		'dashboard_incoming_links'  => array(),
		'dashboard_plugins'         => array(),
		'dashboard_secondary'       => array(),
		'dashboard_recent_drafts'   => array(),
		'dashboard_recent_comments' => array(),
	);

	$widgets = array_intersect_key( $widgets, $default_widgets );

	return $widgets;

}

/**
 * Get Saved Default Widgets
 */
function udb_get_saved_default_widgets() {

	$widgets = udb_get_widgets();

	if( get_option( 'udb_settings' ) ) {
		$settings = get_option( 'udb_settings' );
	} else {
		$settings = array();
	}

	$widgets = array_intersect_key( $widgets, $settings );

	return $widgets;

}