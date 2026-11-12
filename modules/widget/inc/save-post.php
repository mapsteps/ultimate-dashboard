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

	// One check for the whole widget form (the nonce sits in the main widget box).
	$nonce = isset( $_POST['udb_widget_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['udb_widget_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'udb_save_widget' ) || ! current_user_can( 'edit_post', $post_id ) ) {
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
		$widget_helper = new \Udb\Helpers\Widget_Helper();
		update_post_meta( $post_id, 'udb_html', wp_kses( wp_unslash( $_POST['udb_html'] ), $widget_helper->get_allowed_tags() ) );
	}

	// User defined widget.
	do_action( 'udb_save_widget', $post_id );

};
