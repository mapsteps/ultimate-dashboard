<?php
/**
 * Settings
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Settings
 */
add_action('admin_init', 'udb_settings');

function udb_settings() {

	register_setting( 'udb-settings-group', 'udb_settings' );

	// Settings Sections
	add_settings_section( 'udb-remove-all-widgets', __( 'WordPress Dashboard Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard' );
	add_settings_section( 'udb-remove-single-widgets', '', '', 'ultimate-dashboard' );

	// Settings Fields
	add_settings_field( 'remove-all-widgets', __( 'Remove all Widgets', 'ultimate-dashboard' ), 'remove_all_widgets_callback', 'ultimate-dashboard', 'udb-remove-all-widgets' );
	add_settings_field( 'remove-single-widgets', __( 'Remove individual Widgets', 'ultimate-dashboard' ), 'remove_single_widgets_callback', 'ultimate-dashboard', 'udb-remove-single-widgets' );

}

/**
 * Output Settings
 */
function remove_all_widgets_callback() {

	$udb_settings = get_option( 'udb_settings' );

	if ( !isset( $udb_settings['remove-all'] ) ) {
		$removeallwidgets = 0;
	} else {
		$removeallwidgets = 1;
	}

	echo '<p><label><input type="checkbox" name="udb_settings[remove-all]" value="1" '. checked( $removeallwidgets, 1, false ) .' />'. __( 'All', 'ultimate-dashboard' ) .'</label></p>';

}

/**
 * Remove Single Widgets Callback
 */
function remove_single_widgets_callback() {

	$udb_settings = get_option( 'udb_settings' );

	if ( !isset( $udb_settings['welcome_panel'] ) ) {
		$welcome = 0;
	} else {
		$welcome = 1;
	}

	echo '<p><label><input type="checkbox" name="udb_settings[welcome_panel]" value="1" '. checked( $welcome, 1, false ) .' />'. __( 'Welcome Panel', 'ultimate-dashboard' ) .' (<code>welcome_panel</code>)</label></p>';

	$widgets = udb_get_default_widgets();

	foreach ( $widgets as $id => $widget ) {

		if ( !isset( $udb_settings[$id] ) ) {
			$value = 0;
		} else {
			$value = 1;
		}

		echo '<p><label><input type="checkbox" name="udb_settings['. esc_attr( $id ) .']" value="1" '. checked( $value, 1, false ) .' />'. esc_attr( $widget['title_stripped'] ) .' (<code>'. esc_attr( $id ) .'</code>)</label></p>';

	}

}