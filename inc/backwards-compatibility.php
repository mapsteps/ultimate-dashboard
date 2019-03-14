<?php
/**
 * Backwards Compatibility
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
 * Check widget type compatibility if necessary
 */
function udb_compat_widget_type() {
	if ( get_option( 'udb_compat_widget_type' ) ) {
		return;
	}

	$widgets = get_posts(
		[
			'post_type'   => 'udb_widgets',
			'numberposts' => -1,
			'post_status' => 'any',
		]
	);

	if ( ! $widgets ) {
		return;
	}

	foreach ( $widgets as $widget ) {
		$widget_type = get_post_meta( $widget->ID, 'udb_widget_type', true );

		if ( ! $widget_type ) {
			$html    = get_post_meta( $widget->ID, 'udb_html', true );
			$content = get_post_meta( $widget->ID, 'udb_content', true );

			if ( $html ) {
				$widget_type = 'html';
			} elseif ( $content ) {
				$widget_type = 'text';
			} else {
				$widget_type = 'icon';
			}

			update_post_meta( $widget->ID, 'udb_widget_type', $widget_type );
		}
	}

	update_option( 'udb_compat_widget_type', 1 );
}
add_action( 'admin_init', 'udb_compat_widget_type' );
