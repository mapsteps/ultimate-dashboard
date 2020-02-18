<?php
/**
 * Plugin Name: Ultimate Dashboard
 * Plugin URI: https://ultimatedashboard.io/
 * Description: Create a Custom WordPress Dashboard.
 * Version: 2.6
 * Author: David Vongries
 * Author URI: https://mapsteps.com/
 * Text Domain: ultimate-dashboard
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Plugin constants.
define( 'ULTIMATE_DASHBOARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ULTIMATE_DASHBOARD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Admin scripts & styles.
 */
function udb_admin_scripts() {

	global $pagenow, $typenow;

	$current_screen = get_current_screen();

	$plugin_data = get_plugin_data( __FILE__ );

	// Create new & edit widget screen.
	if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) && 'udb_widgets' === $typenow ) {

		// FontAwesome CSS.
		wp_register_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.7.0' );
		wp_enqueue_style( 'font-awesome' );

		// Select2 CSS.
		wp_register_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/select2.min.css', array(), '4.0.6-rc.1' );
		wp_enqueue_style( 'select2' );

		// Custom Post Type CSS.
		wp_register_style( 'ultimate-dashboard-cpt', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/ultimate-dashboard-cpt.css', array(), $plugin_data['Version'] );
		wp_enqueue_style( 'ultimate-dashboard-cpt' );

		do_action( 'udb_edit_styles' );

		// Select2 JS.
		wp_register_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/js/select2.min.js', array( 'jquery' ), '4.0.6-rc.1', true );
		wp_enqueue_script( 'select2' );

		// Custom Post Type JS.
		wp_register_script( 'ultimate-dashboard-cpt', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/js/ultimate-dashboard-cpt.js', array( 'jquery' ), $plugin_data['Version'], true );
		wp_enqueue_script( 'ultimate-dashboard-cpt' );

		do_action( 'udb_edit_scripts' );

	}

	// Widget post list & settings page.
	if ( 'edit.php' === $pagenow && 'udb_widgets' === $typenow ) {

		// FontAwesome.
		wp_register_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.7.0' );
		wp_enqueue_style( 'font-awesome' );

	}

	// Widget post list.
	if ( 'edit-udb_widgets' === $current_screen->id ) {

		wp_enqueue_style( 'ultimate-dashboard-post-list', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/post-list.css', array(), $plugin_data['Version'] );

	}

	// Settings page.
	if ( 'udb_widgets_page_settings' === $current_screen->id || 'udb_widgets_page_tools' === $current_screen->id ) {

		// Settings page styles.
		wp_enqueue_style( 'settings-page', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/settings-page.css', array(), $plugin_data['Version'] );
		wp_enqueue_style( 'settings-fields', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/setting-fields.css', array(), $plugin_data['Version'] );
		wp_enqueue_style( 'ultimate-dashboard-settings', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/ultimate-dashboard-settings.css', array(), $plugin_data['Version'] );

		// CodeMirror.
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		// Settings page JS.
		wp_register_script( 'ultimate-dashboard-settings', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/js/ultimate-dashboard-settings.js', array( 'jquery' ), $plugin_data['Version'], true );
		wp_enqueue_script( 'ultimate-dashboard-settings' );

	}

	// WordPress dashboard.
	if ( 'index.php' === $pagenow ) {

		// FontAwesome CSS.
		wp_register_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.7.0' );
		wp_enqueue_style( 'font-awesome' );

		// Dashboard CSS.
		wp_register_style( 'ultimate-dashboard-index', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/ultimate-dashboard-index.css', array(), $plugin_data['Version'] );
		wp_enqueue_style( 'ultimate-dashboard-index' );

		do_action( 'udb_dashboard_styles' );

		// Dashboard JS.
		wp_register_script( 'ultimate-dashboard-index', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/js/ultimate-dashboard-index.js', array( 'jquery' ), $plugin_data['Version'], true );
		wp_enqueue_script( 'ultimate-dashboard-index' );

		do_action( 'udb_dashboard_scripts' );

	}

	// Highlight PRO link in sub-menu.
	echo '<style>#adminmenu #menu-posts-udb_widgets a[href="https://ultimatedashboard.io/pro/"] { color: #47D87C; }</style>';

}
add_action( 'admin_enqueue_scripts', 'udb_admin_scripts' );

/**
 * Action links.
 *
 * @param array $links The action links array.
 *
 * @return array The action links array.
 */
function udb_add_action_links( $links ) {

	$settings = array( '<a href="' . admin_url( 'edit.php?post_type=udb_widgets&page=settings' ) . '">' . __( 'Settings', 'ultimate-dashboard' ) . '</a>' );

	return array_merge( $links, $settings );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'udb_add_action_links' );

/**
 * Plugin deactivation.
 */
function udb_deactivate() {

	$udb_settings = get_option( 'udb_settings' );

	if ( isset( $udb_settings['remove-on-uninstall'] ) ) {

		delete_option( 'udb_settings' );
		delete_option( 'udb_pro_settings' );
		delete_option( 'udb_import' );
		delete_option( 'udb_compact_widget_widget_type' );

	}

}
register_deactivation_hook( plugin_basename( __FILE__ ), 'udb_deactivate' );

// Required files.
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/init.php';
