<?php
/**
 * Setup Ultimate Dashboard plugin.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper;

/**
 * Class to setup Ultimate Dashboard plugin.
 */
class Setup {
	/**
	 * Static method to ease the class usage.
	 */
	public static function run() {

		$instance = new Setup();
		$instance->setup();

	}

	/**
	 * Setup the class.
	 */
	public function setup() {

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );
		add_action( 'plugins_loaded', array( $this, 'load_modules' ) );
		add_action( 'admin_menu', array( $this, 'pro_submenu' ), 20 );
		register_deactivation_hook( plugin_basename( __FILE__ ), array( $this, 'deactivation' ) );

		$content_helper = new Content_Helper();
		add_filter( 'wp_kses_allowed_html', array( $content_helper, 'allow_iframes_in_html' ) );

	}

	/**
	 * Add action links displayed in plugins page.
	 *
	 * @param array $links The action links array.
	 * @return array The action links array.
	 */
	public function add_action_links( $links ) {

		$settings = array( '<a href="' . admin_url( 'edit.php?post_type=udb_widgets&page=settings' ) . '">' . __( 'Settings', 'ultimate-dashboard' ) . '</a>' );

		return array_merge( $links, $settings );

	}

	/**
	 * Load Ultimate Dashboard modules.
	 */
	public function load_modules() {

		$modules = array(
			'Udb\\Widgets\\Module'         => __DIR__ . '/modules/widgets/class-module.php',
			'Udb\\Settings\\Module'        => __DIR__ . '/modules/settings/class-module.php',
			'Udb\\Branding\\Module'        => __DIR__ . '/modules/branding/class-module.php',
			'Udb\\LoginCustomizer\\Module' => __DIR__ . '/modules/login-customizer/class-module.php',
			'Udb\\AdminPage\\Module'       => __DIR__ . '/modules/admin-page/class-module.php',
			'Udb\\AdminMenu\\Module'       => __DIR__ . '/modules/admin-menu/class-module.php',
			'Udb\\Tools\\Module'           => __DIR__ . '/modules/tools/class-module.php',
		);

		$modules = apply_filters( 'udb_modules', $modules );

		foreach ( $modules as $class => $file ) {
			require_once $file;
			$module = new $class();
			$module->setup();
		}

	}

	/**
	 * Generate PRO submenu link.
	 */
	public function pro_submenu() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $submenu;

		$submenu['edit.php?post_type=udb_widgets'][] = array( 'PRO', 'manage_options', 'https://ultimatedashboard.io/pro/' );

	}

	/**
	 * Plugin deactivation.
	 */
	public function deactivation() {

		$udb_settings = get_option( 'udb_settings' );

		if ( isset( $udb_settings['remove-on-uninstall'] ) ) {

			delete_option( 'udb_settings' );
			delete_option( 'udb_branding' );
			delete_option( 'udb_login' );
			delete_option( 'udb_import' );

			delete_option( 'udb_compat_widget_type' );
			delete_option( 'udb_compat_widget_status' );
			delete_option( 'udb_compat_delete_login_customizer_page' );
			delete_option( 'udb_compat_settings_meta' );
			delete_option( 'udb_compat_old_option' );

			delete_option( 'udb_login_customizer_flush_url' );

		}

	}
}
