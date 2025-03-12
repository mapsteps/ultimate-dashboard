<?php
/**
 * Widget saving process.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post_id ) {

	$post_type = get_post_type( $post_id );

	if ( 'udb_widgets' !== $post_type ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	$widget_type_nonce     = isset( $_POST['udb_widget_type_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['udb_widget_type_nonce'] ) ) : '';
	$active_status_nonce   = isset( $_POST['udb_widget_active_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['udb_widget_active_nonce'] ) ) : '';
	$widget_position_nonce = isset( $_POST['udb_position_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['udb_position_nonce'] ) ) : '';
	$widget_priority_nonce = isset( $_POST['udb_priority_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['udb_priority_nonce'] ) ) : '';

	$is_valid_widget_type_nonce   = wp_verify_nonce( $widget_type_nonce, 'udb_widget_type' ) ? true : false;
	$is_valid_active_status_nonce = wp_verify_nonce( $active_status_nonce, 'udb_widget_active' ) ? true : false;
	$is_valid_position_nonce      = wp_verify_nonce( $widget_position_nonce, 'udb_position' ) ? true : false;
	$is_valid_priority_nonce      = wp_verify_nonce( $widget_priority_nonce, 'udb_priority' ) ? true : false;

	if ( ! $is_valid_widget_type_nonce || ! $is_valid_active_status_nonce || ! $is_valid_position_nonce || ! $is_valid_priority_nonce ) {
		return;
	}

	// Widget type.
	if ( isset( $_POST['udb_widget_type'] ) ) {
		update_post_meta( $post_id, 'udb_widget_type', sanitize_text_field( wp_unslash( $_POST['udb_widget_type'] ) ) );
	}

	// Icon widget.
	if ( isset( $_POST['udb_icon'] ) ) {
		update_post_meta( $post_id, 'udb_icon_key', sanitize_text_field( wp_unslash( $_POST['udb_icon'] ) ) );
	}

	if ( isset( $_POST['udb_link'] ) ) {
		// We're not specifically sanitizing URL's here because we also allow relative URL's.
		update_post_meta( $post_id, 'udb_link', sanitize_text_field( wp_unslash( $_POST['udb_link'] ) ) );
	}

	if ( isset( $_POST['udb_tooltip'] ) ) {
		update_post_meta( $post_id, 'udb_tooltip', sanitize_text_field( wp_unslash( $_POST['udb_tooltip'] ) ) );
	}

	$link_target = isset( $_POST['udb_link_target'] ) ? '_blank' : '_self';
	update_post_meta( $post_id, 'udb_link_target', $link_target );

	// Sidebar.
	if ( isset( $_POST['udb_is_active'] ) ) {
		update_post_meta( $post_id, 'udb_is_active', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_is_active' );
	}

	if ( isset( $_POST['udb_metabox_position'] ) ) {
		update_post_meta( $post_id, 'udb_position_key', sanitize_text_field( wp_unslash( $_POST['udb_metabox_position'] ) ) );
	}

	if ( isset( $_POST['udb_metabox_priority'] ) ) {
		update_post_meta( $post_id, 'udb_priority_key', sanitize_text_field( wp_unslash( $_POST['udb_metabox_priority'] ) ) );
	}

	// Text widget.
	if ( isset( $_POST['udb_content'] ) ) {
		update_post_meta( $post_id, 'udb_content', wp_kses_post( wp_unslash( $_POST['udb_content'] ) ) );
	}

	if ( isset( $_POST['udb_content_height'] ) ) {
		update_post_meta( $post_id, 'udb_content_height', sanitize_text_field( wp_unslash( $_POST['udb_content_height'] ) ) );
	}

	// HTML widget.
	if ( isset( $_POST['udb_html'] ) ) {
		update_post_meta( $post_id, 'udb_html', wp_kses_post( wp_unslash( $_POST['udb_html'] ) ) );
	}

	// User defined widget.
	do_action( 'udb_save_widget', $post_id );

};
