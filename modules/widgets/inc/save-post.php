<?php
/**
 * Widget saving process.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post_id ) {

	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );

	$is_valid_metabox_nonce  = ( isset( $_POST['udb_metabox_nonce'] ) && wp_verify_nonce( $_POST['udb_metabox_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';
	$is_valid_position_nonce = ( isset( $_POST['udb_position_nonce'] ) && wp_verify_nonce( $_POST['udb_position_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';
	$is_valid_priority_nonce = ( isset( $_POST['udb_priority_nonce'] ) && wp_verify_nonce( $_POST['udb_priority_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';

	if ( $is_autosave || $is_revision || ! $is_valid_metabox_nonce || ! $is_valid_position_nonce || ! $is_valid_priority_nonce ) {
		return;
	}

	if ( isset( $_POST['udb_widget_type'] ) ) {
		update_post_meta( $post_id, 'udb_widget_type', sanitize_text_field( $_POST['udb_widget_type'] ) );
	}

		// Icon widget.
	if ( isset( $_POST['udb_icon'] ) ) {
		update_post_meta( $post_id, 'udb_icon_key', sanitize_text_field( $_POST['udb_icon'] ) );
	}

	if ( isset( $_POST['udb_link'] ) ) {
		update_post_meta( $post_id, 'udb_link', sanitize_text_field( $_POST['udb_link'] ) );
	}

		$check = isset( $_POST['udb_link_target'] ) && $_POST['udb_link_target'] ? '_blank' : '_self';
		update_post_meta( $post_id, 'udb_link_target', $check );

		// Sidebar.
	if ( isset( $_POST['udb_is_active'] ) ) {
		update_post_meta( $post_id, 'udb_is_active', sanitize_text_field( $_POST['udb_is_active'] ) );
	}

	if ( isset( $_POST['udb_metabox_position'] ) ) {
		update_post_meta( $post_id, 'udb_position_key', sanitize_text_field( $_POST['udb_metabox_position'] ) );
	}

	if ( isset( $_POST['udb_metabox_priority'] ) ) {
		update_post_meta( $post_id, 'udb_priority_key', sanitize_text_field( $_POST['udb_metabox_priority'] ) );
	}

	if ( isset( $_POST['udb_tooltip'] ) ) {
		update_post_meta( $post_id, 'udb_tooltip', sanitize_text_field( $_POST['udb_tooltip'] ) );
	}

		// Text widget.
	if ( isset( $_POST['udb_content'] ) ) {
		update_post_meta( $post_id, 'udb_content', wp_kses_post( $_POST['udb_content'] ) );
	}

	if ( isset( $_POST['udb_content_height'] ) ) {
		update_post_meta( $post_id, 'udb_content_height', sanitize_text_field( $_POST['udb_content_height'] ) );
	}

		// HTML widget.
	if ( isset( $_POST['udb_html'] ) ) {
		update_post_meta( $post_id, 'udb_html', wp_kses_post( $_POST['udb_html'] ) );
	}

	// User defined widget.
	do_action( 'udb_save_widget', $post_id );

};
