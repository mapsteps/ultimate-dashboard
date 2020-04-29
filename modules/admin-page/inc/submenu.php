<?php
/**
 * Setup admin pages submenu.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Add "Login Customizer" submenu under "Ultimate Dashboard" menu item.
 */
function udb_admin_page_submenu() {

	add_submenu_page( 'edit.php?post_type=udb_widgets', 'Admin Pages', 'Admin Pages', apply_filters( 'udb_settings_capability', 'manage_options' ), 'edit.php?post_type=udb_admin_page' );

}
add_action( 'admin_menu', 'udb_admin_page_submenu' );

/**
 * Hightlight submenu page.
 *
 * @param string $submenu_file The submenu file.
 * @param string $parent_file The parent menu file.
 *
 * @return string The submenu file.
 */
function udb_admin_page_submenu_highlight( $submenu_file, $parent_file ) {

	global $current_screen;
	global $parent_file;

	if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'udb_admin_page' === $current_screen->post_type ) {
		$parent_file  = 'edit.php?post_type=udb_widgets';
		$submenu_file = 'edit.php?post_type=udb_admin_page';
	}

	return $submenu_file;

}
add_filter( 'submenu_file', 'udb_admin_page_submenu_highlight', 10, 2 );
