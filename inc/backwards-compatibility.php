<?php
/**
 * Backwards compatibility.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$udb_settings = get_option( 'udb_settings' );

if ( ! $udb_settings ) {
	update_option( 'udb_settings', array() );
}

if ( get_option( 'removeallwidgets' ) ) {
	$udb_settings['remove-all'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'removeallwidgets' );
}

if ( get_option( 'welcome' ) ) {
	$udb_settings['welcome_panel'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'welcome' );
}

if ( get_option( 'primary' ) ) {
	$udb_settings['dashboard_primary'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'primary' );
}

if ( get_option( 'quickpress' ) ) {
	$udb_settings['dashboard_quick_press'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'quickpress' );
}

if ( get_option( 'rightnow' ) ) {
	$udb_settings['dashboard_right_now'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'rightnow' );
}

if ( get_option( 'activity' ) ) {
	$udb_settings['dashboard_activity'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'activity' );
}

if ( get_option( 'incominglinks' ) ) {
	$udb_settings['dashboard_incoming_links'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'incominglinks' );
}

if ( get_option( 'plugins' ) ) {
	$udb_settings['dashboard_plugins'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'plugins' );
}

if ( get_option( 'secondary' ) ) {
	$udb_settings['dashboard_secondary'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'secondary' );
}

if ( get_option( 'drafts' ) ) {
	$udb_settings['dashboard_recent_drafts'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'drafts' );
}

if ( get_option( 'comments' ) ) {
	$udb_settings['dashboard_recent_comments'] = 1;
	update_option( 'udb_settings', $udb_settings );
	delete_option( 'comments' );
}

/**
 * Handle udb_widget_type.
 *
 * Can be used for "whole checking" or "partial checking".
 *
 * @param int $post_id The post ID.
 */
function udb_handle_widget_type( $post_id ) {

	$widget_type = get_post_meta( $post_id, 'udb_widget_type', true );

	if ( ! $widget_type ) {

		$html    = get_post_meta( $post_id, 'udb_html', true );
		$content = get_post_meta( $post_id, 'udb_content', true );

		if ( $html ) {
			$widget_type = 'html';
		} elseif ( $content ) {
			$widget_type = 'text';
		} else {
			$widget_type = 'icon';
		}

		update_post_meta( $post_id, 'udb_widget_type', $widget_type );

	}

}
add_action( 'udb_compat_widget_type', 'udb_handle_widget_type' );

/**
 * Whole checking udb_widget_type compatibility.
 */
function udb_compat_widget_type() {

	// Make sure we don't check again.
	if ( get_option( 'udb_compat_widget_type' ) ) {
		return;
	}

	$widgets = get_posts(
		array(
			'post_type'   => 'udb_widgets',
			'numberposts' => -1,
			'post_status' => 'any',
		)
	);

	if ( ! $widgets ) {
		return;
	}

	foreach ( $widgets as $widget ) {
		do_action( 'udb_compat_widget_type', $widget->ID );
	}

	// Make sure we don't check again.
	update_option( 'udb_compat_widget_type', 1 );

}
add_action( 'admin_init', 'udb_compat_widget_type' );

/**
 * Meta compatibilities.
 */
function udb_meta_compatibility() {

	// Don't run checking on heartbeat request.
	if ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) {
		return;
	}

	udb_settings_meta_adjustment();

}
add_action( 'admin_init', 'udb_meta_compatibility' );

/**
 * Move udb_pro_settings to udb_settings.
 */
function udb_settings_meta_adjustment() {

	// Make sure we don't check again.
	if ( get_option( 'udb_compat_settings_meta' ) ) {
		return;
	}

	$setting_opts = get_option( 'udb_settings', array() );
	$pro_opts     = get_option( 'udb_pro_settings', array() );

	$update_setting_opts = false;
	$update_pro_opts     = false;

	// Dashboard's custom css.
	if ( isset( $pro_opts['custom_css'] ) ) {
		$setting_opts['custom_css'] = $pro_opts['custom_css'];
		$update_setting_opts        = true;
		$update_pro_opts            = true;

		unset( $pro_opts['custom_css'] );
	}

	// Delete udb_pro_settings, since we don't use it anymore.
	delete_option( 'udb_pro_settings' );

	// Update the settings meta if necessary.
	if ( $update_setting_opts ) {
		update_option( 'udb_settings', $setting_opts );
	}

	// Make sure we don't check again.
	update_option( 'udb_compat_settings_meta', 1 );
}

/**
 * Delete un-used auto-generated "Login Customizer" page (with 'udb-login-page' slug).
 * That page existed in 2.7 of the FREE version.
 */
function udb_delete_unused_page() {
	// Make sure we don't check again.
	if ( get_option( 'udb_compat_delete_login_customizer_page' ) ) {
		return;
	}

	$page = get_page_by_path( 'udb-login-page' );

	if ( ! empty( $page ) && is_object( $page ) ) {
		wp_delete_post( $page->ID, true );
	}

	// Make sure we don't check again.
	update_option( 'udb_compat_delete_login_customizer_page', 1 );
}
add_action( 'admin_init', 'udb_delete_unused_page' );
