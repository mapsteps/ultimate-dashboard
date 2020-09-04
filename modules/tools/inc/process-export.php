<?php
/**
 * Export processing.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings          = array();
	$branding_settings = array();
	$login_settings    = array();

	if ( isset( $_POST['udb_export_settings'] ) && $_POST['udb_export_settings'] ) {
		$settings          = get_option( 'udb_settings' );
		$branding_settings = get_option( 'udb_branding' );
		$login_settings    = get_option( 'udb_login' );
	}

	$widgets = get_posts(
		array(
			'post_type'   => 'udb_widgets',
			'numberposts' => -1,
		)
	);
	$widgets = $widgets ? $widgets : array();

	foreach ( $widgets as &$widget ) {

		unset(
			$widget->to_ping,
			$widget->pinged,
			$widget->guid,
			$widget->filter,
			$widget->ancestors,
			$widget->page_template,
			$widget->post_category,
			$widget->tags_input,
			$widget->post_type
		);

		$widget->meta = array(
			'udb_widget_type'    => get_post_meta( $widget->ID, 'udb_widget_type', true ),
			'udb_link'           => get_post_meta( $widget->ID, 'udb_link', true ),
			'udb_link_target'    => get_post_meta( $widget->ID, 'udb_link_target', true ),
			'udb_icon_key'       => get_post_meta( $widget->ID, 'udb_icon_key', true ),
			'udb_position_key'   => get_post_meta( $widget->ID, 'udb_position_key', true ),
			'udb_priority_key'   => get_post_meta( $widget->ID, 'udb_priority_key', true ),
			'udb_tooltip'        => get_post_meta( $widget->ID, 'udb_tooltip', true ),
			'udb_content'        => get_post_meta( $widget->ID, 'udb_content', true ),
			'udb_content_height' => get_post_meta( $widget->ID, 'udb_content_height', true ),
		);

	}

	$admin_pages = get_posts(
		array(
			'post_type'   => 'udb_admin_page',
			'numberposts' => -1,
		)
	);
	$admin_pages = $admin_pages ? $admin_pages : array();

	foreach ( $admin_pages as &$admin_page ) {
		$meta = get_post_meta( $admin_page->ID );

		foreach ( $meta as $meta_key => $meta_value ) {
			$admin_page->meta = count( $meta_value ) > 1 ? $meta_value : $meta_value[0];
		}
	}

	header( 'Content-disposition: attachment; filename=udb-export-' . date( 'Y-m-d-H.i.s', strtotime( 'now' ) ) . '.json' );
	header( 'Content-type: application/json' );

	echo wp_json_encode(
		array(
			'widgets'           => $widgets,
			'settings'          => $settings,
			'branding_settings' => $branding_settings,
			'login_settings'    => $login_settings,
			'admin_pages'       => $admin_pages,
		)
	);

	exit;

};
