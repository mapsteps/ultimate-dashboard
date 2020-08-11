<?php
/**
 * Setup admin page enqueue.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Enqueue the admin page styles & scripts.
 */
function udb_admin_page_admin_assets() {

	global $pagenow;
	global $typenow;
	global $current_screen;

	// Create new & edit admin page screen.
	if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) && 'udb_admin_page' === $typenow ) {

		// Styles.
		if ( apply_filters( 'udb_font_awesome', true ) ) {
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.7.0' );
		}

		wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/select2.min.css', array(), '4.0.6-rc.1' );

		wp_enqueue_style( 'udb-admin-fields', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/admin-fields.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		wp_enqueue_style( 'udb-edit-admin-page', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/admin-page/assets/css/edit-admin-page.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Scripts.
		wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/js/select2.min.js', array( 'jquery' ), '4.0.6-rc.1', true );

		wp_enqueue_code_editor( array( 'type' => 'text/html' ) ); // CodeMirror.

		wp_enqueue_script( 'udb-edit-admin-page', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/admin-page/assets/js/edit-admin-page.js', array( 'select2' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	} elseif ( 'edit-udb_admin_page' === $current_screen->id ) { // Admin page's post list.

		// Styles.
		if ( apply_filters( 'udb_font_awesome', true ) ) {
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.7.0' );
		}

		wp_enqueue_style( 'udb-admin-fields', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/admin-fields.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		wp_enqueue_style( 'udb-admin-page-list', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/admin-page/assets/css/admin-page-list.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Scripts.
		wp_enqueue_script( 'udb-admin-page-list', ULTIMATE_DASHBOARD_PLUGIN_URL . 'modules/admin-page/assets/js/admin-page-list.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	}

}
add_action( 'admin_enqueue_scripts', 'udb_admin_page_admin_assets' );
