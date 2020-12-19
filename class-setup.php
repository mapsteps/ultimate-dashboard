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
	 * Init the class setup.
	 */
	public static function init() {

		$instance = new Setup();
		$instance->setup();

	}

	/**
	 * Get saved/default modules.
	 *
	 * @return array The saved/default modules.
	 */
	public static function saved_modules() {

		$defaults = array(
			'white_label'       => "true",
			'login_customizer'  => "true",
			'admin_pages'       => "true",
			'admin_menu_editor' => "true",
		);

		$saved_modules = get_option( 'udb_modules', $defaults );
		$new_modules   = array_diff_key( $defaults, $saved_modules );

		if ( ! empty( $new_modules ) ) {
			$updated_modules = array_merge( $saved_modules, $new_modules );
			update_option( 'udb_modules', $updated_modules );
		}

		return apply_filters( 'udb_saved_modules', get_option( 'udb_modules', $defaults ) );

	}

	/**
	 * Setup the class.
	 */
	public function setup() {

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		add_action( 'plugins_loaded', array( $this, 'load_modules' ), 20 );
		add_action( 'admin_menu', array( $this, 'pro_submenu' ), 20 );
		register_deactivation_hook( plugin_basename( __FILE__ ), array( $this, 'deactivation' ), 20 );

		$content_helper = new Content_Helper();
		add_filter( 'wp_kses_allowed_html', array( $content_helper, 'allow_iframes_in_html' ) );

	}

	/**
	 * Add action links displayed in plugins page.
	 *
	 * @param array $links The action links array.
	 * @return array The modified action links array.
	 */
	public function action_links( $links ) {

		$settings = array( '<a href="' . admin_url( 'edit.php?post_type=udb_widgets&page=settings' ) . '">' . __( 'Settings', 'ultimate-dashboard' ) . '</a>' );

		return array_merge( $links, $settings );

	}

	/**
	 * Load Ultimate Dashboard modules.
	 */
	public function load_modules() {

		$modules['Udb\\Widget\\Widget_Module'] = __DIR__ . '/modules/widget/class-widget-module.php';

		if( ! defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' ) || ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION >= '3.1' ) {
			$modules['Udb\\Feature\\Feature_Module'] = __DIR__ . '/modules/feature/class-feature-module.php';
		}

		$modules['Udb\\Setting\\Setting_Module'] = __DIR__ . '/modules/setting/class-setting-module.php';

		$saved_modules = Setup::saved_modules();

		if ( "true" === $saved_modules['white_label'] ) {
			$modules['Udb\\Branding\\Branding_Module'] = __DIR__ . '/modules/branding/class-branding-module.php';
		}

		if ( "true" === $saved_modules['admin_pages'] ) {
			$modules['Udb\\AdminPage\\Admin_Page_Module'] = __DIR__ . '/modules/admin-page/class-admin-page-module.php';
		}

		if ( "true" === $saved_modules['login_customizer'] ) {
			$modules['Udb\\LoginCustomizer\\Login_Customizer_Module'] = __DIR__ . '/modules/login-customizer/class-login-customizer-module.php';
		}

		if ( "true" === $saved_modules['admin_menu_editor'] ) {
			$modules['Udb\\AdminMenu\\Admin_Menu_Module'] = __DIR__ . '/modules/admin-menu/class-admin-menu-module.php';
		}

		$modules['Udb\\Tool\\Tool_Module'] = __DIR__ . '/modules/tool/class-tool-module.php';

		$modules = apply_filters( 'udb_modules', $modules );

		foreach ( $modules as $class => $file ) {
			$splits      = explode( '/', $file );
			$module_name = $splits[count( $splits ) - 2];
			$filter_name = str_ireplace( '-', '_', $module_name );
			$filter_name = 'udb_' . $filter_name;

			if ( apply_filters( $filter_name, true ) ) {

				require_once $file;
				$module = new $class();
				$module->setup();

			}
		}

	}

	/**
	 * Generate PRO submenu link.
	 */
	public function pro_submenu() {

		// Stop if PRO version is active.
		if ( udb_is_pro_active() ) {
			return;
		}

		// Stop if user isn't an admin.
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

		$settings = get_option( 'udb_settings' );

		$remove_on_uninstall = isset( $settings['remove-on-uninstall'] ) ? true : false;
		$remove_on_uninstall = apply_filters( 'udb_clean_uninstall', $remove_on_uninstall );

		if ( $remove_on_uninstall ) {

			delete_option( 'udb_settings' );
			delete_option( 'udb_branding' );
			delete_option( 'udb_login' );
			delete_option( 'udb_import' );
			delete_option( 'udb_modules' );

			delete_option( 'udb_compat_widget_type' );
			delete_option( 'udb_compat_widget_status' );
			delete_option( 'udb_compat_delete_login_customizer_page' );
			delete_option( 'udb_compat_settings_meta' );
			delete_option( 'udb_compat_old_option' );

			delete_option( 'udb_login_customizer_flush_url' );

		}

	}
}
