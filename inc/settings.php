<?php
/**
 * Settings
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Settings
 */
add_action( 'admin_init', 'udb_settings' );

/**
 * Register setting
 */
function udb_settings() {

	register_setting( 'udb-settings-group', 'udb_settings' );
	register_setting( 'udb-settings-group', 'udb_pro_settings' );

	// Settings Sections.
	add_settings_section( 'udb-remove-all-widgets', __( 'WordPress Dashboard Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard' );
	add_settings_section( 'udb-remove-single-widgets', '', '', 'ultimate-dashboard' );
	add_settings_section( 'udb-advanced-settings', __( 'Advanced', 'ultimatedashboard' ), '', 'ultimate-dashboard' );

	// Settings Fields.
	add_settings_field( 'remove-all-widgets', __( 'Remove all Widgets', 'ultimate-dashboard' ), 'remove_all_widgets_callback', 'ultimate-dashboard', 'udb-remove-all-widgets' );
	add_settings_field( 'remove-single-widgets', __( 'Remove individual Widgets', 'ultimate-dashboard' ), 'remove_single_widgets_callback', 'ultimate-dashboard', 'udb-remove-single-widgets' );
	add_settings_field( 'remove-3rd-party-widgets', __( 'Remove 3rd Party Widgets', 'ultimate-dashboard' ), 'remove_3rd_party_widgets_callback', 'ultimate-dashboard', 'udb-remove-single-widgets' );
	add_settings_field( 'custom-dashboard-css', __( 'Custom Dashboard CSS', 'ultimatedashboard' ), 'udb_custom_dashboard_css_callback', 'ultimate-dashboard', 'udb-advanced-settings' );

}

/**
 * Output Settings
 */
function remove_all_widgets_callback() {

	$udb_settings = get_option( 'udb_settings' );

	if ( ! isset( $udb_settings['remove-all'] ) ) {
		$removeallwidgets = 0;
	} else {
		$removeallwidgets = 1;
	}

	echo '<p><label><input type="checkbox" name="udb_settings[remove-all]" value="1" ' . checked( $removeallwidgets, 1, false ) . ' />' . __( 'All', 'ultimate-dashboard' ) . '</label></p>';

}

/**
 * Remove Single Widgets Callback
 */
function remove_single_widgets_callback() {

	$udb_settings = get_option( 'udb_settings' );

	if ( ! isset( $udb_settings['welcome_panel'] ) ) {
		$welcome = 0;
	} else {
		$welcome = 1;
	}

	echo '<p><label><input type="checkbox" name="udb_settings[welcome_panel]" value="1" ' . checked( $welcome, 1, false ) . ' />' . __( 'Welcome Panel', 'ultimate-dashboard' ) . ' (<code>welcome_panel</code>)</label></p>';

	$widgets = udb_get_default_widgets();

	foreach ( $widgets as $id => $widget ) {

		if ( ! isset( $udb_settings[ $id ] ) ) {
			$value = 0;
		} else {
			$value = 1;
		}

		echo '<p><label><input type="checkbox" name="udb_settings[' . esc_attr( $id ) . ']" value="1" ' . checked( $value, 1, false ) . ' />' . esc_attr( $widget['title_stripped'] ) . ' (<code>' . esc_attr( $id ) . '</code>)</label></p>';

	}

}

/**
 * Remove 3rd Party Widgets Callback
 */
function remove_3rd_party_widgets_callback() {
	echo sprintf( __( 'Get %s!', 'ultimate-dashboard' ), '<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=remove_3rd_party_widgets_link&utm_campaign=udb" target="_blank">Ultimate Dashboard PRO</a>' );
}

/**
 * Custom Dashboard CSS Callback
 */
function udb_custom_dashboard_css_callback() {

	$udb_pro_settings = get_option( 'udb_pro_settings' );

	if ( !isset( $udb_pro_settings['custom_css'] ) ) {
		$custom_css = false;
	} else {
		$custom_css = $udb_pro_settings['custom_css'];
	}

	?>

	<textarea id="udb-custom-dashboard-css" class="widefat textarea" name="udb_pro_settings[custom_css]"><?php echo wp_unslash( $custom_css ); ?></textarea>

	<?php

}
