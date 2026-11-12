<?php
/**
 * Delete Ultimate Dashboard's data when the plugin is deleted, if "Remove Data on Uninstall" is checked.
 *
 * Deactivating the plugin never deletes anything.
 *
 * @package Ultimate_Dashboard
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Whether "Remove Data on Uninstall" is checked on the current site.
 *
 * @return bool
 */
function udb_uninstall_remove_data_checked() {

	$settings = get_option( 'udb_settings' );

	return is_array( $settings ) && ! empty( $settings['remove-on-uninstall'] );

}

/**
 * Delete the free plugin's data on the current site.
 *
 * PRO's own options are deleted by PRO. "udb_multisite_blueprint" is kept for PRO as well.
 */
function udb_uninstall_delete_site_data() {

	global $wpdb;

	$options = array(
		'udb_settings',
		'udb_branding',
		'udb_login',
		'udb_login_redirect',
		'udb_import',
		'udb_modules',
		'udb_recent_admin_menu',
		'udb_compat_widget_type',
		'udb_compat_widget_status',
		'udb_compat_delete_login_customizer_page',
		'udb_compat_settings_meta',
		'udb_compat_old_option',
		'udb_migration_from_erident',
		'udb_referred_by_kirki',
		'udb_login_customizer_flush_url',
		'review_notice_dismissed',
		'udb_install_date',
		'udb_plugin_activated',
		'udb_onboarding_wizard_redirect',
		'udb_onboarding_wizard_completed',
	);

	foreach ( $options as $option ) {
		delete_option( $option );
	}

	// Black Friday notice dismissals (one option per year).
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time clean-up on uninstall.
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( 'udb_bfcm_notice_dismissed' ) . '%' ) );

	// Dashboard widgets and admin pages in every status (incl. trash and auto-drafts), with their meta.
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time clean-up on uninstall.
	$post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type IN ( 'udb_widgets', 'udb_admin_page' )" );

	foreach ( $post_ids as $post_id ) {
		wp_delete_post( $post_id, true );
	}

	// PRO is still active: keep the checkbox, so PRO deletes its own data when it's removed.
	if ( defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' ) ) {
		update_option( 'udb_settings', array( 'remove-on-uninstall' => 1 ) );
	}

}

if ( ! is_multisite() ) {

	if ( udb_uninstall_remove_data_checked() ) {
		udb_uninstall_delete_site_data();
	}

	return;

}

// Multisite: with a blueprint site (PRO), the blueprint's checkbox decides for every site.
$udb_blueprint = (int) get_site_option( 'udb_multisite_blueprint' );

if ( $udb_blueprint ) {
	switch_to_blog( $udb_blueprint );
	$udb_remove_everywhere = udb_uninstall_remove_data_checked();
	restore_current_blog();

	if ( ! $udb_remove_everywhere ) {
		return;
	}
}

foreach ( get_sites( array( 'fields' => 'ids' ) ) as $udb_site_id ) {
	switch_to_blog( $udb_site_id );

	if ( $udb_blueprint || udb_uninstall_remove_data_checked() ) {
		udb_uninstall_delete_site_data();
	}

	restore_current_blog();
}
